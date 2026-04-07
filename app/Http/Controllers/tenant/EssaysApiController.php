<?php

namespace App\Http\Controllers\tenant;

use App\Http\Controllers\Controller;
use App\Models\tenant\Essays;
use App\Models\tenant\RelationEssayTeam;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EssaysApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $data = Essays::query()
                ->with([
                    'unitsMeasurement:id,description',
                    'condition:id,description',
                ])
                ->paginate(15);

            return $this->sendResponse($data, 'Enviando ensayos');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $input = $request->all();

            Essays::create([
                'description' => $input['description'],
                'lcm' => $input['lcm'],
                'units_measurement_id' => $input['units_measurement_id'],
                'condition_id' => $input['condition_id'],
            ]);

            return $this->sendSuccess('Ensayo creada con exito');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function update($id, Request $request): JsonResponse
    {
        try {
            $input = $request->all();

            $essays = Essays::findOrFail($id);
            $essays->update([
                'description' => $input['description'],
                'lcm' => $input['lcm'],
                'units_measurement_id' => $input['units_measurement_id'],
                'condition_id' => $input['condition_id'],
            ]);

            return $this->sendSuccess('Ensayo editada con exito');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $essays = Essays::findOrFail($id);
            $essays->relationEssayTeam()->delete();
            $essays->delete();

            return $this->sendSuccess('Ensayo eliminada con exito');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function relationsTeam(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $essayId = $request->essay_id;
            $teamsIds = $request->teams_ids ?? [];

            $teamsIds = array_values(array_unique($teamsIds));

            $currentRelations = RelationEssayTeam::where('essay_id', $essayId)->get();

            $currentTeamIds = $currentRelations->pluck('team_id')->toArray();

            $teamsToCreate = array_diff($teamsIds, $currentTeamIds);
            $teamsToDelete = array_diff($currentTeamIds, $teamsIds);

            foreach ($teamsToCreate as $teamId) {
                RelationEssayTeam::create([
                    'essay_id' => $essayId,
                    'team_id' => $teamId,
                ]);
            }

            if (!empty($teamsToDelete)) {
                RelationEssayTeam::where('essay_id', $essayId)
                    ->whereIn('team_id', $teamsToDelete)
                    ->delete();
            }

            DB::commit();
            return $this->sendSuccess('Equipos relacionados con éxito');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    public function getRelationsTeam($essayId): JsonResponse
    {
        try {
            $relationsIds = RelationEssayTeam::query()
                ->where('essay_id', $essayId)
                ->get()
                ->pluck('team_id');

            return $this->sendResponse($relationsIds, 'Enviando equipos');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
