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

            $chainCustody = ChainCustody::find($id);

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

            $chainCustody = ChainCustody::find($id);

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
                ->where('content->number_chain', 'like', "%{$numberChain}%")
                ->get();

            $orderIds = $data->pluck('order_id')->unique()->values()->toArray();
            $os = $data->pluck('os')->unique()->values()->toArray();

            $content = $data->map(function ($item) {
                return [
                    'code_lab' => $item->content['code_lab'] ?? null,
                    'chain_custody' => $item,
                ];
            })->values()->toArray();

            OtsGenerate::create([
                'user_id' => $userId,
                'os' => $os,
                'order_id' => $orderIds,
                'content' => $content,
            ]);

            return $this->sendResponse([], 'Datos generados correctamente');
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
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    // 🔹 2) GENERAR PDF
    public function downloadPdfOT(int $id)
    {
        try {
            $ot = OtsGenerate::findOrFail($id);

            $payload = $this->buildPayload($ot);

            $pdf = Pdf::loadView('pdf.orden-trabajo', $payload)
                ->setPaper('a4', 'portrait');

            return $pdf->download('orden_trabajo_' . $ot->id . '.pdf');
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    // 🔹 3) HELPER: transforma tu content a filas (code_lab + parámetro)
    private function buildPayload(OtsGenerate $ot): array
    {
        $rows = [];

        foreach ($ot->content as $item) {
            // $item viene de tu map: ['code_lab', 'chain_custody']
            $chain = $item['chain_custody']['content'] ?? [];

            $codeLab = $item['code_lab']
                ?? ($chain['code_lab'] ?? '');

            // parameters viene como string con comas y saltos de línea
            $parameters = $chain['parameters'] ?? '';

            $parametersArray = collect(preg_split('/[\n,]+/', $parameters))
                ->map(fn($p) => trim($p))
                ->filter()
                ->values();

            foreach ($parametersArray as $param) {
                $rows[] = [
                    'code_lab' => $codeLab,
                    'parameter' => $param,
                ];
            }
        }

        // Puedes tomar estos datos del primer item o de donde prefieras
        $first = $ot->content[0]['chain_custody']['content'] ?? [];

        return [
            // Cabecera
            'os' => is_array($ot->os) ? implode(', ', $ot->os) : $ot->os,
            'number_report' => $first['number_report'] ?? '',
            'number_chain'  => $first['number_chain'] ?? '',
            'matriz'        => $first['matriz'] ?? '',
            'date_agreed'   => isset($first['date_agreed']) ? date('d/m/Y', strtotime($first['date_agreed'])) : '',
            'hour'          => isset($first['date_reception']) ? date('H:i:s', strtotime($first['date_reception'])) : '',

            // Detalle para tabla
            'rows'    => $rows,   // para PDF
            'details' => array_map(fn($r) => [$r['code_lab'], $r['parameter']], $rows), // para Excel
        ];
    }
}
