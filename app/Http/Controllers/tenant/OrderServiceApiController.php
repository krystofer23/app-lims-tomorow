<?php

namespace App\Http\Controllers\tenant;

use App\Http\Controllers\Controller;
use App\Models\tenant\Essays;
use App\Models\tenant\Matriz;
use App\Models\tenant\OrderService;
use App\Models\tenant\RelationEssayTeam;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OrderServiceApiController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            OrderService::create([]);

            DB::commit();
            return $this->sendSuccess('Orden de servicio generado con exito');
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
