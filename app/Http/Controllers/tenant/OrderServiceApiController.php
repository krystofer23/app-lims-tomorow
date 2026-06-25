<?php

namespace App\Http\Controllers\tenant;

use App\Exports\OrderServiceExport;
use App\Http\Controllers\Controller;
use App\Models\tenant\Essays;
use App\Models\tenant\Item;
use App\Models\tenant\ItemsOrderService;
use App\Models\tenant\OrderService;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\tenant\ConnectionParameter;
use Carbon\Carbon;

class OrderServiceApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $order_id = $request->input('order_id');
            $company_id = $request->input('company_id');
            $application_id = $request->input('application_id');
            $comercial_id = $request->input('comercial_id');

            $data = OrderService::query()
                ->with([
                    'user',
                    'company:id,ruc,business_name',
                    'application:id,ruc,business_name',
                    'contactCompany.user'
                ])
                ->when($order_id, fn($q) => $q->where('id', $order_id))
                ->when($company_id, fn($q) => $q->where('company_id', $company_id))
                ->when($application_id, fn($q) => $q->where('application_id', $application_id))
                ->when($comercial_id, fn($q) => $q->where('user_id', $comercial_id))
                ->paginate(15);

            return $this->sendResponse($data, 'Enviando ordenes de servicio');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $os = OrderService::query()
                ->findOrFail($id);

            if (!$os) {
                return $this->sendError('Orden de servicio no encontrada.');
            }

            $items = $os->items
                ->where('type', 'matrix')
                ->map(function ($item) {
                    return $item->item;
                })
                ->values();

            $mapData = [
                "id" => $os?->id,
                "quote_id" => $os?->quote_id,
                "company_id" => $os?->company_id,
                "contact_company" => $os?->contact_company,
                "direction" => $os?->direction,
                "date_attention" => $os?->date_attention,
                "application_id" => $os?->application_id,
                "contact_application" => $os?->contact_application,
                "department" => $os?->department,
                "district" => $os?->district,
                "province" => $os?->province,
                "reference" => $os?->reference,
                "origin" => $os?->origin,
                "project" => $os?->project,
                "date_init_service" => $os?->date_init_service,
                "date_end_monitoring" => $os?->date_end_monitoring,
                "users" => $os?->users ?? [],
                "details" => $os?->details,
                "monitoring" => $os?->monitoring,
                "projects" => $os?->projects,
                "service_includes" => $os?->service_includes,
                "accommodation" => $os?->accommodation,
                "travel_expenses" => $os?->travel_expenses,
                "days_service" => $os?->days_service,
                "personal_transport" => $os?->personal_transport,
                "send_sampling" => $os?->send_sampling,
                "surveillance" => $os?->surveillance,
                "electric_generator" => $os?->electric_generator,
                "company_emission_id" => $os?->company_emission_id,
                "type_document_required" => $os?->type_document_required,
                "number_copy" => $os?->number_copy,
                "version" => $os?->version,
                "code" => $os?->code,
                "items" => $items,
                "observations" => $os?->observations,
            ];

            return $this->sendResponse($mapData, 'Enviando orden de servicio');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $userId = Auth::guard('api')->id();
            $input = $request->all();

            $period = Carbon::now('America/Lima')->format('ym');

            $prefix = 'OS-' . $period . '-';

            $lastOrder = OrderService::query()
                ->whereNotNull('code')
                ->where('code', 'like', $prefix . '%')
                ->lockForUpdate()
                ->orderByDesc('id')
                ->first();

            $nextNumber = 1;

            if ($lastOrder && !empty($lastOrder->code)) {
                $lastNumber = (int) str_replace($prefix, '', $lastOrder->code);
                $nextNumber = $lastNumber + 1;
            }

            $code = $prefix . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);

            $orderService = OrderService::create([
                'quote_id' => $input['quote_id'],
                'user_id' => $userId,
                'reviwed' => 'RONALD RAMIREZ',
                'company_id' => $input['company_id'],
                'contact_company' => $input['contact_company'],
                'direction' => $input['direction'],
                'date_attention' => $input['date_attention'],
                'application_id' => $input['application_id'],
                'contact_application' => $input['contact_application'],
                'department' => $input['department'],
                'district' => $input['district'],
                'province' => $input['province'],
                'reference' => $input['reference'],
                'origin' => $input['origin'],
                'project' => $input['project'],
                'date_init_service' => $input['date_init_service'],
                'date_end_monitoring' => $input['date_end_monitoring'],
                'users' => $input['users'],
                'details' => $input['details'],
                'monitoring' => $input['monitoring'],
                'projects' => $input['projects'],
                'service_includes' => $input['service_includes'],
                'accommodation' => $input['accommodation'],
                'travel_expenses' => $input['travel_expenses'],
                'days_service' => $input['days_service'],
                'personal_transport' => $input['personal_transport'],
                'send_sampling' => $input['send_sampling'],
                'surveillance' => $input['surveillance'],
                'electric_generator' => $input['electric_generator'],
                'company_emission_id' => $input['company_emission_id'],
                'type_document_required' => $input['type_document_required'],
                'number_copy' => $input['number_copy'],
                'code' => $code,
                'observations' => $input['observations'],
            ]);

            if (!empty($input['items']) && is_array($input['items'])) {
                foreach ($input['items'] as $item) {
                    ItemsOrderService::create([
                        'order_service_id' => $orderService->id,
                        'filable_type' => Item::class,
                        'filable_id' => $item['id'],
                        'item' => $item,
                        'type' => 'matrix',
                        'amount' => $item['number_samples'],
                        'price_unit' => $item['unit_price'] ?? 0,
                        'total' => $item['price'] ?? 0,
                    ]);
                }
            }

            DB::commit();
            return $this->sendSuccess('Orden de servicio generado con éxito');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    public function update($id, Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $input = $request->all();

            $orderService = OrderService::findOrFail($id);

            $orderService->update([
                'company_id' => $input['company_id'],
                'contact_company' => $input['contact_company'],
                'direction' => $input['direction'],
                'application_id' => $input['application_id'],
                'contact_application' => $input['contact_application'],
                'department' => $input['department'],
                'district' => $input['district'],
                'province' => $input['province'],
                'reference' => $input['reference'],
                'origin' => $input['origin'],
                'project' => $input['project'],
                'date_init_service' => $input['date_init_service'],
                'date_end_monitoring' => $input['date_end_monitoring'],
                'users' => $input['users'],
                'details' => $input['details'],
                'monitoring' => $input['monitoring'],
                'projects' => $input['projects'],
                'service_includes' => $input['service_includes'],
                'accommodation' => $input['accommodation'],
                'travel_expenses' => $input['travel_expenses'],
                'days_service' => $input['days_service'],
                'personal_transport' => $input['personal_transport'],
                'send_sampling' => $input['send_sampling'],
                'surveillance' => $input['surveillance'],
                'electric_generator' => $input['electric_generator'],
                'company_emission_id' => $input['company_emission_id'],
                'type_document_required' => $input['type_document_required'],
                'number_copy' => $input['number_copy'],
                'observations' => $input['observations'],
            ]);

            $orderService->items()->delete();

            if (!empty($input['items']) && is_array($input['items'])) {
                foreach ($input['items'] as $item) {
                    ItemsOrderService::create([
                        'order_service_id' => $orderService->id,
                        'filable_type' => Item::class,
                        'filable_id' => $item['id'],
                        'item' => $item,
                        'type' => 'matrix',
                        'amount' => $item['number_samples'],
                        'price_unit' => $item['unit_price'] ?? 0,
                        'total' => $item['price'] ?? 0,
                    ]);
                }
            }

            DB::commit();
            return $this->sendSuccess('Orden de servicio actualizada con éxito');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $orderService = OrderService::findOrFail($id);
            $orderService->items()->delete();
            $orderService->delete();

            DB::commit();
            return $this->sendSuccess('Orden de servicio eliminada con éxito');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    public function exportOrderService($id)
    {
        $orderService = $this->getOrderServiceForExport($id);

        if (!$orderService) {
            return $this->sendError('No se encontró la orden de servicio');
        }

        return Excel::download(
            new OrderServiceExport($orderService),
            'orden-servicio-' . ($orderService->code ?? $orderService->id) . '.xlsx'
        );
    }

    public function exportOrderServicePdf($id)
    {
        $orderService = $this->getOrderServiceForExport($id);

        if (!$orderService) {
            return $this->sendError('No se encontró la orden de servicio');
        }

        $exportData = $this->buildOrderServiceExportData($orderService);

        $pdf = Pdf::loadView('pdf.order-services.main', $exportData)
            ->setPaper('a4', 'portrait')
            ->setOption('isRemoteEnabled', true);

        return $pdf->stream('orden-servicio-' . ($orderService->code ?? $orderService->id) . '.pdf');
    }

    // public function teams($matrizId): JsonResponse
    // {
    //     try {
    //         $matriz = Matriz::query()
    //             ->with(['itemsMatriz'])
    //             ->find($matrizId);

    //         if (! $matriz) {
    //             return $this->sendError('Matriz no encontrada');
    //         }

    //         $essaysIds = $matriz->itemsMatriz
    //             ->pluck('essays_id')
    //             ->filter()
    //             ->unique()
    //             ->values()
    //             ->toArray();

    //         if (empty($essaysIds)) {
    //             return $this->sendResponse([], 'La matriz no tiene ensayos relacionados');
    //         }

    //         $relations = RelationEssayTeam::query()
    //             ->whereIn('essay_id', $essaysIds)
    //             ->get(['essay_id', 'team_id']);

    //         if ($relations->isEmpty()) {
    //             return $this->sendResponse([], 'No hay equipos relacionados a los ensayos');
    //         }

    //         $teamIds = $relations
    //             ->pluck('team_id')
    //             ->filter()
    //             ->unique()
    //             ->values()
    //             ->toArray();

    //         $url = rtrim(env('SYSTEM_B_URL'), '/') . '/api-tems/team-in';

    //         $response = Http::withHeaders([
    //             'X-API-KEY' => env('INTERNAL_API_KEY'),
    //             'Accept' => 'application/json',
    //         ])->timeout(30)->post($url, [
    //             'teams_ids' => $teamIds
    //         ]);

    //         if (! $response->successful()) {
    //             return $this->sendError([
    //                 'message' => 'La API externa devolvió un error',
    //                 'error' => $response->json() ?? $response->body(),
    //             ]);
    //         }

    //         $teams = collect($response->json()['data'] ?? []);
    //         $teamsById = $teams->keyBy('id');

    //         $essays = Essays::query()
    //             ->with([
    //                 'unitsMeasurement',
    //                 'condition',
    //             ])
    //             ->whereIn('id', $essaysIds)
    //             ->get()
    //             ->keyBy('id');

    //         $essayWithTeams = collect($essaysIds)
    //             ->map(function ($essayId) use ($relations, $teamsById, $essays) {
    //                 $teams = $relations
    //                     ->where('essay_id', $essayId)
    //                     ->map(function ($relation) use ($teamsById) {
    //                         return $teamsById->get($relation->team_id);
    //                     })
    //                     ->filter()
    //                     ->values()
    //                     ->toArray();

    //                 return [
    //                     'essay' => $essays->get($essayId),
    //                     'teams' => $teams,
    //                 ];
    //             })
    //             ->values()
    //             ->toArray();

    //         return $this->sendResponse($essayWithTeams, 'Enviando equipos');
    //     } catch (Exception $e) {
    //         return $this->sendError($e->getMessage());
    //     }
    // }

    private function getOrderServiceForExport($id): ?OrderService
    {
        return OrderService::query()
            ->with([
                'company:id,ruc,business_name,direction,activity',
                'user',
                'validator',
                'quote',
                'contactCompany.user',
                'items',
                'application',
                'contactApplication.user',
                'companyEmission'
            ])
            ->find($id);
    }

    private function buildOrderServiceExportData(OrderService $orderService): array
    {
        $company = $orderService->company;
        $contact = $orderService->contactCompany;

        $matrices = $orderService->items->where('type', 'matrix');
        $services = $orderService->items->where('type', 'service');

        $groupedMatrices = $matrices
            ->groupBy(function ($matrix) {
                $matrixDescription = data_get($matrix, 'item.matrix.description');

                $matrixKey = $matrixDescription
                    ?: 'matrix_filter_' . data_get($matrix, 'item.matrix_filter', 'sin_matriz');

                $frequencyLabel = data_get($matrix, 'item.frequency_label')
                    ?? data_get($matrix, 'item.item.frequency_label')
                    ?? 'SIN_FRECUENCIA';

                return $matrixKey . '||' . $frequencyLabel;
            })
            ->map(function ($items) {
                $first = $items->first();

                $matrixDescription = data_get($first, 'item.matrix.description');

                if (!$matrixDescription) {
                    $parameterId = data_get($first, 'item.parameter_id');
                    $typeOfSampleId = data_get($first, 'item.type_of_sample_filter');
                    $matrixId = data_get($first, 'item.matrix_filter');

                    $connectionParameter = ConnectionParameter::query()
                        ->with('matrix')
                        ->where('parameter_id', $parameterId)
                        ->when($typeOfSampleId, fn($q) => $q->where('type_of_samples_id', $typeOfSampleId))
                        ->when($matrixId, fn($q) => $q->where('matrix_id', $matrixId))
                        ->first();

                    $matrixDescription = $connectionParameter?->matrix?->description;
                }

                $preparedItems = $items
                    ->map(function ($item) use ($matrixDescription) {
                        $methodologyCode = data_get($item, 'item.reference.code', '');
                        $methodologyTitle = data_get($item, 'item.reference.title', '');

                        $methodology = trim($methodologyCode . ' - ' . $methodologyTitle, ' -');

                        $item->matrix_description_resolved = data_get($item, 'item.matrix.description')
                            ?? $matrixDescription
                            ?? 'Sin matriz';

                        $item->essay_description_resolved = data_get($item, 'item.parameter.description')
                            ?? data_get($item, 'item.description')
                            ?? '-';

                        $item->methodology_resolved = $methodology !== ''
                            ? $methodology
                            : data_get($item, 'item.methodology')
                            ?? data_get($item, 'item.methodologie.description')
                            ?? '-';

                        $item->lcm_resolved = data_get($item, 'item.lcm', '-');

                        $item->unit_resolved = data_get($item, 'item.unit_measurement.description')
                            ?? data_get($item, 'item.units_measurement.description')
                            ?? '-';

                        $item->samples_resolved = data_get($item, 'item.number_samples')
                            ?? $item->amount
                            ?? '-';

                        $item->subcontract_resolved = data_get($item, 'item.subcontract')
                            ?? data_get($item, 'item.subcontract_name')
                            ?? data_get($item, 'item.condition.description')
                            ?? 'NO APLICA';

                        $item->equipment_resolved = data_get($item, 'item.equipment')
                            ?? data_get($item, 'item.equipments')
                            ?? data_get($item, 'item.team')
                            ?? '-';

                        $item->observation_resolved = data_get($item, 'item.observation')
                            ?? data_get($item, 'item.observations')
                            ?? data_get($item, 'item.accreditation')
                            ?? '-';

                        return $item;
                    })
                    ->values();

                return [
                    'description' => $matrixDescription ?? 'Sin matriz',
                    'frequency_label' => data_get($first, 'item.frequency_label')
                        ?? data_get($first, 'item.item.frequency_label'),
                    'items' => $preparedItems,
                ];
            })
            ->values();

        return [
            'orderService' => $orderService,
            'company' => $company,
            'contact' => $contact,
            'groupedMatrices' => $groupedMatrices,
            'services' => $services->values(),
        ];
    }
}
