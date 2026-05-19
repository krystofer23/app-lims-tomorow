<?php

namespace App\Http\Controllers\tenant;

use App\Exports\ChainCustodyExport;
use App\Http\Controllers\Controller;
use App\Models\tenant\ChainCustody;
use App\Models\tenant\OrderService;
use App\Models\tenant\OtsGenerate;
use App\Models\tenant\Record;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrdenTrabajoExport;
use App\Models\tenant\ConnectionParameter;
use App\Models\tenant\Matrix;
use App\Models\tenant\Parameters;
use App\Models\tenant\TypeOfSamples;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ReceptionApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $company_id = $request->input('company_id');
            $application_id = $request->input('application_id');
            $order_id = $request->input('order_id');

            $numberChain = $request->input('number_chain');

            $data = ChainCustody::query()
                ->with([
                    'company',
                    'application',
                    'order',
                ])
                ->when($request->filled('company_id'), fn($q) => $q->where('company_id', $company_id))
                ->when($request->filled('application_id'), fn($q) => $q->where('application_id', $application_id))
                ->when($request->filled('order_id'), fn($q) => $q->where('order_id', $order_id))
                ->when($request->filled('number_chain'), function ($q) use ($numberChain) {
                    $q->where('content->number_chain', 'like', "%{$numberChain}%");
                })
                ->paginate(15);

            return $this->sendResponse($data, 'Enviando registros');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $input = $request->all();
            $userId = Auth::guard('api')->id();

            $os = $input['order_id'] ? OrderService::select('id', 'code')->find($input['order_id'])?->code : null;

            $chainCustody = ChainCustody::create([
                'os' => $os,
                'company_id' => $input['company_id'],
                'application_id' => $input['application_id'],
                'order_id' => $input['order_id'],
                'os' => $input['os'],
                'number_chain' => $input['number_chain'],
                'number_report' => $input['number_report'],
                'type_of_sample_id' => $input['type_of_sample_id'],
                'matrix_id' => $input['matrix_id'],
                'number_sample' => $input['number_sample'],
                'number_essays' => $input['number_essays'],
                'date_reception' => $input['date_reception'],
                'date_sampling_init_date' => $input['date_sampling_init_date'],
                'date_sampling_init_time' => $input['date_sampling_init_time'],
                'date_sampling_end_date' => $input['date_sampling_end_date'],
                'date_sampling_end_time' => $input['date_sampling_end_time'],
                'date_agreed' => $input['date_agreed'],
                'company_sampling_id' => $input['company_sampling_id'],
                'code_lab' => $input['code_lab'],
                'code_season' => $input['code_season'],
                'condition_report' => $input['condition_report'],
                'other_company_id' => $input['other_company_id'],
                'observations' => $input['observations'],
                'parameters' => $input['parameters'],
            ]);

            Record::create([
                'user_id' => $userId,
                'type' => 'created',
                'fileable_type' => ChainCustody::class,
                'fileable_id' => $chainCustody?->id,
            ]);

            DB::commit();
            return $this->sendSuccess('Registro creado con exito');
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
            $userId = Auth::guard('api')->id();

            $chainCustody = ChainCustody::findOrFail($id);

            if (!$chainCustody) {
                return $this->sendError('No se encontro el registro');
            }

            $os = $input['order_id'] ? OrderService::select('id', 'code')->find($input['order_id'])?->code : null;

            $chainCustody->update([
                'os' => $os,
                'company_id' => $input['company_id'],
                'application_id' => $input['application_id'],
                'order_id' => $input['order_id'],
                'os' => $input['os'],
                'number_chain' => $input['number_chain'],
                'number_report' => $input['number_report'],
                'type_of_sample_id' => $input['type_of_sample_id'],
                'matrix_id' => $input['matrix_id'],
                'number_sample' => $input['number_sample'],
                'number_essays' => $input['number_essays'],
                'date_reception' => $input['date_reception'],
                'date_sampling_init_date' => $input['date_sampling_init_date'],
                'date_sampling_init_time' => $input['date_sampling_init_time'],
                'date_sampling_end_date' => $input['date_sampling_end_date'],
                'date_sampling_end_time' => $input['date_sampling_end_time'],
                'date_agreed' => $input['date_agreed'],
                'company_sampling_id' => $input['company_sampling_id'],
                'code_lab' => $input['code_lab'],
                'code_season' => $input['code_season'],
                'condition_report' => $input['condition_report'],
                'other_company_id' => $input['other_company_id'],
                'observations' => $input['observations'],
                'parameters' => $input['parameters'],
            ]);

            Record::create([
                'user_id' => $userId,
                'type' => 'updated',
                'fileable_type' => ChainCustody::class,
                'fileable_id' => $chainCustody?->id,
            ]);

            DB::commit();
            return $this->sendSuccess('Registro actualizado con exito');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $userId = Auth::guard('api')->id();

            $chainCustody = ChainCustody::findOrFail($id);

            if (!$chainCustody) {
                return $this->sendError('No se encontro el registro');
            }

            Record::create([
                'user_id' => $userId,
                'type' => 'deleted',
                'fileable_type' => ChainCustody::class,
                'fileable_id' => $chainCustody?->id,
            ]);

            $chainCustody->delete();

            DB::commit();
            return $this->sendSuccess('Registro eliminado con exito');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    public function generateOT(Request $request): JsonResponse
    {
        try {
            $userId = Auth::guard('api')->id();
            $numberChain = $request->input('number_chain');

            $data = ChainCustody::query()
                ->where('content->number_chain', $numberChain)
                ->get();

            if ($data->isEmpty()) {
                return $this->sendError('No se encontraron cadenas de custodia.');
            }

            $orderIds = $data->pluck('order_id')->unique()->values()->toArray();
            $os = $data->pluck('os')->unique()->values()->toArray();

            $content = $data->map(function ($item) {
                return [
                    'code_lab' => $item->content['code_lab'] ?? null,
                    'chain_custody_id' => $item->id,
                    'content' => $item->content,
                ];
            })->values()->toArray();

            $otsGenerate = OtsGenerate::updateOrCreate(
                [
                    'os' => $os[0] ?? null,
                    'order_id' => $orderIds[0] ?? null,
                    'number_chain' => $numberChain
                ],
                [
                    'user_id' => $userId,
                    'content' => $content,
                ]
            );

            return $this->sendResponse($otsGenerate, 'Datos generados correctamente');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function downloadExcelOT(int $id)
    {
        try {
            $ot = OtsGenerate::findOrFail($id);

            $payload = $this->buildPayload($ot);

            return Excel::download(
                new ChainCustodyExport($payload),
                'orden_trabajo_' . $ot->id . '.xlsx'
            );
        } catch (\Throwable $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function viewPdfOT(int $id)
    {
        try {
            $ot = OtsGenerate::findOrFail($id);
            $payload = $this->buildPayload($ot);

            $pdf = Pdf::loadView('pdf.chain-custody.main', $payload)
                ->setPaper('a4', 'portrait')
                ->setOption('isRemoteEnabled', true);

            return $pdf->stream('orden_trabajo_' . $ot->id . '.pdf');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    private function buildPayload(OtsGenerate $ot): array
    {
        $firstOt = $ot->content[0]['content'] ?? [];

        $datetime = $firstOt['date_sampling_init'];

        $carbon = Carbon::parse($datetime);

        $date = $carbon->toDateString();
        $hour = $carbon->toTimeString();

        $rows = [];

        foreach ($ot->content as $value) {
            $content = $value['content'] ?? [];

            $codeLab = $content['code_lab'] ?? null;
            $parameters = $content['parameters'] ?? [];

            $rows[] = array_values(array_filter([
                ...(is_array($codeLab) ? $codeLab : [$codeLab]),
                ...$parameters,
            ]));
        }

        $maxColumns = collect($rows)->map(fn($row) => count($row))->max() ?? 1;

        return [
            'os' => $ot?->os ?? '-',
            'number_report' => $firstOt['number_report'] ?? '-',
            'number_chain' => $firstOt['number_chain'] ?? '-',
            'matriz' => $firstOt['matriz'] ?? '-',
            'date_agreed' => $date ?? '-',
            'hour' => $hour ?? '-',
            'created_at' => optional($ot?->created_at)->format('Y-m-d'),
            'rows' => $rows,
            'maxColumns' => $maxColumns,
        ];
    }

    public function getTypeOfSamples(Request $request): JsonResponse
    {
        try {
            $orderId = $request->input('order_id');

            if (!$orderId) {
                $data = TypeOfSamples::query()
                    ->get();

                return $this->sendResponse($data, 'Enviando tipos de muestras');
            }

            $order = OrderService::query()
                ->with('items')
                ->findOrFail($orderId);

            $samplesIds = [];

            foreach ($order->items as $itemOrder) {
                $typeOfSampleId = data_get($itemOrder->item, 'type_of_sample_id');

                if ($typeOfSampleId) {
                    $samplesIds[] = $typeOfSampleId;
                    continue;
                }

                $parameterId = data_get($itemOrder->item, 'parameter_id');

                if (!$parameterId) {
                    continue;
                }

                $connectionParameter = ConnectionParameter::query()
                    ->where('parameter_id', $parameterId)
                    ->first();

                if ($connectionParameter?->type_of_samples_id) {
                    $samplesIds[] = $connectionParameter->type_of_samples_id;
                }
            }

            $samplesIds = array_values(array_unique($samplesIds));

            $data = TypeOfSamples::query()
                ->whereIn('id', $samplesIds)
                ->get();

            return $this->sendResponse($data, 'Enviando tipos de muestras');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function getMatrix(Request $request): JsonResponse
    {
        try {
            $orderId = $request->input('order_id');
            $type = $request->input('type');

            if (!$orderId) {
                $data = Matrix::query()
                    ->when($request->filled('type'), fn($q) => $q->where('type_of_sample_id', $type))
                    ->get();

                return $this->sendResponse($data, 'Enviando tipos de muestras');
            }

            $order = OrderService::query()
                ->with('items')
                ->findOrFail($orderId);

            $matrixIds = [];

            foreach ($order->items as $itemOrder) {
                $matrixId = data_get($itemOrder->item, 'matrix_id');

                if ($matrixId) {
                    $matrixIds[] = $matrixId;
                    continue;
                }

                $parameterId = data_get($itemOrder->item, 'parameter_id');

                if (!$parameterId) {
                    continue;
                }

                $connectionParameter = ConnectionParameter::query()
                    ->where('parameter_id', $parameterId)
                    ->first();

                if ($connectionParameter?->matrix_id) {
                    $matrixIds[] = $connectionParameter->matrix_id;
                }
            }

            $matrixIds = array_values(array_unique($matrixIds));

            $data = Matrix::query()
                ->whereIn('id', $matrixIds)
                ->when($request->filled('type'), fn($q) => $q->where('type_of_sample_id', $type))
                ->get();

            return $this->sendResponse($data, 'Enviando tipos de muestras');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
