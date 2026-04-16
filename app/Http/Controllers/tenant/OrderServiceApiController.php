<?php

namespace App\Http\Controllers\tenant;

use App\Http\Controllers\Controller;
use App\Models\tenant\Essays;
use App\Models\tenant\ItemsOrderService;
use App\Models\tenant\Matriz;
use App\Models\tenant\OrderService;
use App\Models\tenant\RelationEssayTeam;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OrderServiceApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $data = OrderService::query()
                ->with([
                    'user',
                    'reviwed',
                    'company',
                    'items',
                    'contact.user'
                ])
                ->paginate(15);

            return $this->sendResponse($data, 'Enviando ordenes de servicio');
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

            $lastOrder = OrderService::whereNotNull('code')
                ->lockForUpdate()
                ->orderByDesc('id')
                ->first();

            $nextNumber = 1;

            if ($lastOrder && !empty($lastOrder->code)) {
                $lastNumber = (int) str_replace('OS-', '', $lastOrder->code);
                $nextNumber = $lastNumber + 1;
            }

            $code = 'OS-' . str_pad($nextNumber, 7, '0', STR_PAD_LEFT);

            $orderService = OrderService::create([
                'quote_id' => $input['quote_id'] ?? null,
                'user_id' => $userId,
                'reviwed_id' => $input['reviwed_id'] ?? null,
                'company_id' => $input['company_id'] ?? null,
                'reviwed' => $input['reviwed'] ?? null,
                'reference' => $input['reference'] ?? null,
                'origin' => $input['origin'] ?? null,
                'project' => $input['project'] ?? null,
                'date_monitoring_init' => $input['date_monitoring_init'] ?? null,
                'date_monitoring_end' => $input['date_monitoring_end'] ?? null,
                'date_induction' => $input['date_induction'] ?? null,
                'date_output' => $input['date_output'] ?? null,
                'details' => $input['details'] ?? null,
                'stations_monitoring' => $input['stations_monitoring'] ?? null,
                'project_monitoring' => $input['project_monitoring'] ?? null,
                'conditions' => $input['conditions'] ?? null,
                'emision_data' => $input['emision_data'] ?? null,
                'observations' => $input['observations'] ?? null,
                'code' => $code,
                'contact_id' => $input['contact_id'] ?? null,
            ]);

            if (!empty($input['items']) && is_array($input['items'])) {
                foreach ($input['items'] as $value) {
                    $model = $value['type'] === 'matriz' ? Matriz::class : null;

                    ItemsOrderService::create([
                        'order_service_id' => $orderService->id,
                        'filable_type' => $model,
                        'filable_id' => $value['id'] ?? null,
                        'item' => isset($value['item']) ? json_encode($value['item']) : null,
                        'type' => $value['type'] ?? null,
                        'amount' => $value['item']['number_samples'] ?? null,
                        'price_unit' => $value['item']['unit_price'] ?? null,
                        'total' => $value['item']['price'] ?? null,
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
                'quote_id' => $input['quote_id'] ?? null,
                'reviwed_id' => $input['reviwed_id'] ?? null,
                'company_id' => $input['company_id'] ?? null,
                'reviwed' => $input['reviwed'] ?? null,
                'reference' => $input['reference'] ?? null,
                'origin' => $input['origin'] ?? null,
                'project' => $input['project'] ?? null,
                'date_monitoring_init' => $input['date_monitoring_init'] ?? null,
                'date_monitoring_end' => $input['date_monitoring_end'] ?? null,
                'date_induction' => $input['date_induction'] ?? null,
                'date_output' => $input['date_output'] ?? null,
                'details' => $input['details'] ?? null,
                'stations_monitoring' => $input['stations_monitoring'] ?? null,
                'project_monitoring' => $input['project_monitoring'] ?? null,
                'conditions' => $input['conditions'] ?? null,
                'emision_data' => $input['emision_data'] ?? null,
                'observations' => $input['observations'] ?? null,
            ]);

            ItemsOrderService::where('order_service_id', $orderService->id)->delete();

            if (!empty($input['items']) && is_array($input['items'])) {
                foreach ($input['items'] as $value) {
                    $model = $value['type'] === 'matriz' ? Matriz::class : null;

                    ItemsOrderService::create([
                        'order_service_id' => $orderService->id,
                        'filable_type' => $model,
                        'filable_id' => $value['id'] ?? null,
                        'type' => $value['type'] ?? null,
                        'amount' => $value['item']['number_samples'] ?? null,
                        'price_unit' => $value['item']['unit_price'] ?? null,
                        'total' => $value['item']['price'] ?? null,
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

            ItemsOrderService::where('order_service_id', $orderService->id)->delete();

            $orderService->delete();

            DB::commit();
            return $this->sendSuccess('Orden de servicio eliminada con éxito');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    public function teams($matrizId): JsonResponse
    {
        try {
            $matriz = Matriz::query()
                ->with(['itemsMatriz'])
                ->find($matrizId);

            if (! $matriz) {
                return $this->sendError('Matriz no encontrada');
            }

            $essaysIds = $matriz->itemsMatriz
                ->pluck('essays_id')
                ->filter()
                ->unique()
                ->values()
                ->toArray();

            if (empty($essaysIds)) {
                return $this->sendResponse([], 'La matriz no tiene ensayos relacionados');
            }

            $relations = RelationEssayTeam::query()
                ->whereIn('essay_id', $essaysIds)
                ->get(['essay_id', 'team_id']);

            if ($relations->isEmpty()) {
                return $this->sendResponse([], 'No hay equipos relacionados a los ensayos');
            }

            $teamIds = $relations
                ->pluck('team_id')
                ->filter()
                ->unique()
                ->values()
                ->toArray();

            $url = rtrim(env('SYSTEM_B_URL'), '/') . '/api-tems/team-in';

            $response = Http::withHeaders([
                'X-API-KEY' => env('INTERNAL_API_KEY'),
                'Accept' => 'application/json',
            ])->timeout(30)->post($url, [
                'teams_ids' => $teamIds
            ]);

            if (! $response->successful()) {
                return $this->sendError([
                    'message' => 'La API externa devolvió un error',
                    'error' => $response->json() ?? $response->body(),
                ]);
            }

            $teams = collect($response->json()['data'] ?? []);
            $teamsById = $teams->keyBy('id');

            $essays = Essays::query()
                ->with([
                    'unitsMeasurement',
                    'condition',
                ])
                ->whereIn('id', $essaysIds)
                ->get()
                ->keyBy('id');

            $essayWithTeams = collect($essaysIds)
                ->map(function ($essayId) use ($relations, $teamsById, $essays) {
                    $teams = $relations
                        ->where('essay_id', $essayId)
                        ->map(function ($relation) use ($teamsById) {
                            return $teamsById->get($relation->team_id);
                        })
                        ->filter()
                        ->values()
                        ->toArray();

                    return [
                        'essay' => $essays->get($essayId),
                        'teams' => $teams,
                    ];
                })
                ->values()
                ->toArray();

            return $this->sendResponse($essayWithTeams, 'Enviando equipos');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
