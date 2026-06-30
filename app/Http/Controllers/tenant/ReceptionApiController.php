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
                    'typeOfSample',
                    'matrix',
                ])
                ->when($request->filled('company_id'), fn($q) => $q->where('company_id', $company_id))
                ->when($request->filled('application_id'), fn($q) => $q->where('application_id', $application_id))
                ->when($request->filled('order_id'), fn($q) => $q->where('order_id', $order_id))
                ->when($request->filled('number_chain'), function ($q) use ($numberChain) {
                    $q->where('number_chain', 'like', "%{$numberChain}%");
                })
                ->orderBy('id', 'desc')
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

            $orderId = $input['order_id'] ?? null;

            $os = $orderId
                ? OrderService::query()->select('id', 'code')->find($orderId)?->code
                : null;

            $chainCustody = ChainCustody::create([
                'os' => $os,
                'order_id' => $input['order_id'] ?? null,
                'company_id' => $input['company_id'] ?? null,
                'application_id' => $input['application_id'] ?? null,
                'number_chain' => $input['number_chain'] ?? null,
                'number_report' => $input['number_report'] ?? null,
                'type_of_sample_id' => $input['type_of_sample_id'] ?? null,
                'matrix_id' => $input['matrix_id'] ?? null,
                'number_sample' => $input['number_sample'] ?? null,
                'number_essays' => $input['number_essays'] ?? null,
                'date_reception' => $input['date_reception'] ?? null,
                'date_sampling_init_date' => $input['date_sampling_init_date'] ?? null,
                'date_sampling_init_time' => $input['date_sampling_init_time'] ?? null,
                'date_sampling_end_date' => $input['date_sampling_end_date'] ?? null,
                'date_sampling_end_time' => $input['date_sampling_end_time'] ?? null,
                'date_agreed' => $input['date_agreed'] ?? null,
                'company_sampling_id' => $input['company_sampling_id'] ?? null,
                'code_lab' => $input['code_lab'] ?? null,
                'code_season' => $input['code_season'] ?? null,
                'condition_report' => $input['condition_report'] ?? null,
                'other_company_id' => $input['other_company_id'] ?? null,
                'observations' => $input['observations'] ?? null,
                'parameters' => $input['parameters'] ?? null,
                'code_sample' => $input['code_sample'] ?? null,
                'coordinate' => $input['coordinate'] ?? null,
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
                'order_id' => $input['order_id'] ?? null,
                'company_id' => $input['company_id'] ?? null,
                'application_id' => $input['application_id'] ?? null,
                'number_chain' => $input['number_chain'] ?? null,
                'number_report' => $input['number_report'] ?? null,
                'type_of_sample_id' => $input['type_of_sample_id'] ?? null,
                'matrix_id' => $input['matrix_id'] ?? null,
                'number_sample' => $input['number_sample'] ?? null,
                'number_essays' => $input['number_essays'] ?? null,
                'date_reception' => $input['date_reception'] ?? null,
                'date_sampling_init_date' => $input['date_sampling_init_date'] ?? null,
                'date_sampling_init_time' => $input['date_sampling_init_time'] ?? null,
                'date_sampling_end_date' => $input['date_sampling_end_date'] ?? null,
                'date_sampling_end_time' => $input['date_sampling_end_time'] ?? null,
                'date_agreed' => $input['date_agreed'] ?? null,
                'company_sampling_id' => $input['company_sampling_id'] ?? null,
                'code_lab' => $input['code_lab'] ?? null,
                'code_season' => $input['code_season'] ?? null,
                'condition_report' => $input['condition_report'] ?? null,
                'other_company_id' => $input['other_company_id'] ?? null,
                'observations' => $input['observations'] ?? null,
                'parameters' => $input['parameters'] ?? null,
                'code_sample' => $input['code_sample'] ?? null,
                'coordinate' => $input['coordinate'] ?? null,
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
                ->where('number_chain', $numberChain)
                ->get();

            if ($data->isEmpty()) {
                return $this->sendError('No se encontraron cadenas de custodia.');
            }

            DB::beginTransaction();

            $numberReports = $data->pluck('number_report')->unique()->values()->toArray();
            $orderIds = $data->pluck('order_id')->unique()->values()->toArray();
            $os = $data->pluck('os')->unique()->values()->toArray();
            $matrixIds = $data->pluck('matrix_id')->unique()->values()->toArray();

            $dateReception = $data->pluck('date_reception')->unique()->values()->first();

            $date = '-';
            $hour = '-';

            if ($dateReception) {
                $dateReceptionCarbon = Carbon::parse($dateReception);

                $date = $dateReceptionCarbon->format('Y-m-d');
                $hour = $dateReceptionCarbon->format('H:i:s');
            }

            $parameters = [];

            foreach ($data as $reception) {
                $parameters[] = [
                    'cod_lab' => $reception->code_lab,
                    'parameters' => $reception->parameters,
                ];
            }

            $otsGenerate = OtsGenerate::updateOrCreate(
                [
                    'os' => $os[0] ?? null,
                    'order_id' => $orderIds[0] ?? null,
                    'number_chain' => $numberChain,
                ],
                [
                    'number_report' => $numberReports[0] ?? null,
                    'matrix_id' => $matrixIds[0] ?? null,
                    'delivery_date' => $date,
                    'hour' => $hour,
                    'parameters' => $parameters,
                ]
            );

            Record::create([
                'user_id' => $userId,
                'type' => $otsGenerate->wasRecentlyCreated ? 'created' : 'updated',
                'fileable_type' => OtsGenerate::class,
                'fileable_id' => $otsGenerate->id,
            ]);

            DB::commit();

            return $this->sendResponse(
                $otsGenerate,
                $otsGenerate->wasRecentlyCreated
                    ? 'OT generada correctamente'
                    : 'OT generada correctamente'
            );
        } catch (Exception $e) {
            DB::rollBack();
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
        $arrMax = [];

        foreach ($ot->parameters as $parameter) {
            $arrMax[] = count($parameter['parameters'] ?? []);
        }

        $maxColumns = max($arrMax);

        return [
            'os' => $ot?->os,
            'number_chain' => $ot?->number_chain,
            'number_report' => $ot?->number_report,
            'matrix' => $ot?->matrix?->description,
            'delivery_date' => $ot?->delivery_date,
            'hour' => $ot?->hour,

            'created_at' => optional($ot->created_at)->format('Y-m-d'),

            'parameters' => $ot?->parameters,
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
