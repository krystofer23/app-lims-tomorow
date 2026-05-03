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
                'company_id' => $input['company_id'],
                'application_id' => $input['application_id'],
                'order_id' => $input['order_id'],
                'os' => $os,
                'content' => $input,
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
                'company_id' => $input['company_id'],
                'application_id' => $input['application_id'],
                'order_id' => $input['order_id'],
                'os' => $os,
                'content' => $input,
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
                ->setPaper('a4', 'portrait');

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

        return [
            'os' => $ot?->os ?? '-',
            'number_report' => $firstOt['number_report'] ?? '-',
            'number_chain' => $firstOt['number_chain'] ?? '-',
            'matriz' => $firstOt['matriz'] ?? '-',
            'date_agreed' => $date ?? '-',
            'hour' => $hour ?? '-',
            'created_at' => optional($ot?->created_at)->format('Y-m-d')
        ];
    }
}
