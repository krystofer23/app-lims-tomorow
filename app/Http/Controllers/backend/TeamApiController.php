<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\backend\Team;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TeamApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $rate = $request->input('rate', []);
            $code = $request->input('code');
            $description = $request->input('description');

            $serie = $request->input('serie', null);
            $status = $request->input('status', null);

            $query = Team::query()->with('area');

            $data = $query
                ->when(is_array($rate) && count($rate) === 2, function ($q) use ($rate) {
                    $startDate = Carbon::parse($rate[0]);
                    $endDate = Carbon::parse($rate[1]);
                    $q->whereBetween('created_at', [$startDate, $endDate]);
                })
                ->when($code, function ($q) use ($code) {
                    $q->where('code', 'like', "%{$code}%");
                })
                ->when($description, function ($q) use ($description) {
                    $q->where('description', 'like', "%{$description}%")
                        ->orWhere('denomination', 'like', "%{$description}%");
                })
                ->when($request->filled('serie'), function ($q) use ($serie) {
                    $q->where('serie', $serie);
                })
                ->when($request->filled('status'), function ($q) use ($status) {
                    $q->where('status', $status);
                })
                ->paginate(20);

            return $this->sendResponse($data, 'Lista de equipos');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    // public function show($id)
    // {
    //     $team = Team::find($id);
    //     return $this->sendResponse($team, 'Enviando equipo');
    // }

    // public function store(TeamRequest $request): JsonResponse
    // {
    //     try {
    //         DB::beginTransaction();

    //         $input = $request->all();

    //         $team = Team::where('code', $input['code'])
    //             ->orWhere('serie', $input['serie'])
    //             ->first();

    //         if ($team) {
    //             return $this->sendError('Error el equipo ya esta registrado');
    //         }

    //         Team::create([
    //             'code' => $input['code'] ?? null,
    //             'description' => $input['description'] ?? null,
    //             'denomination' => $input['denomination'] ?? null,
    //             'brand_manufacturer' => $input['brand_manufacturer'] ?? null,
    //             'model' => $input['model'] ?? null,
    //             'serie' => $input['serie'] ?? null,
    //             'observations_technique' => $input['observations_technique'] ?? null,
    //             'observations_certificate' => $input['observations_certificate'] ?? null,
    //             'range_capacity' => $input['range_capacity'] ?? null,
    //             'scope_resolution' => $input['scope_resolution'] ?? null,
    //             'date_entry' => $input['date_entry'] ?? null,
    //             'operational_status' => $input['operational_status'] ?? null,
    //             'last_calibration' => $input['last_calibration'] ?? null,
    //             'next_calibration' => $input['next_calibration'] ?? null,
    //             'executed_calibration' => $input['executed_calibration'] ?? null,
    //             'conformity' => $input['conformity'] ?? null,
    //             'frequency' => $input['frequency'] ?? null,
    //             'last_verification' => $input['last_verification'] ?? null,
    //             'next_verification' => $input['next_verification'] ?? null,
    //             'executed_verification' => $input['executed_verification'] ?? null,
    //             'accordance' => $input['accordance'] ?? null,
    //             'calibration_points_verification' => $input['calibration_points_verification'] ?? null,
    //             'acceptance_criteria' => $input['acceptance_criteria'] ?? null,
    //             'active' => $input['active'] ?? null,
    //             'area_id' => $input['area_id'] ?? null,
    //             'status' => 'IN'
    //         ]);

    //         DB::commit();
    //         return $this->sendSuccess('Equipo registrado con exito.');
    //     } catch (Exception $e) {
    //         DB::rollBack();
    //         return $this->sendError($e->getMessage());
    //     }
    // }

    // public function update($id, TeamRequest $request): JsonResponse
    // {
    //     try {
    //         DB::beginTransaction();

    //         $team = Team::find($id);
    //         $input = $request->all();

    //         if (!$team) {
    //             return $this->sendError('No se encontro el equipo.');
    //         }

    //         $team->update([
    //             'code' => $input['code'] ?? null,
    //             'description' => $input['description'] ?? null,
    //             'denomination' => $input['denomination'] ?? null,
    //             'brand_manufacturer' => $input['brand_manufacturer'] ?? null,
    //             'model' => $input['model'] ?? null,
    //             'serie' => $input['serie'] ?? null,
    //             'observations_technique' => $input['observations_technique'] ?? null,
    //             'observations_certificate' => $input['observations_certificate'] ?? null,
    //             'range_capacity' => $input['range_capacity'] ?? null,
    //             'scope_resolution' => $input['scope_resolution'] ?? null,
    //             'date_entry' => $input['date_entry'] ?? null,
    //             'operational_status' => $input['operational_status'] ?? null,
    //             'last_calibration' => $input['last_calibration'] ?? null,
    //             'next_calibration' => $input['next_calibration'] ?? null,
    //             'executed_calibration' => $input['executed_calibration'] ?? null,
    //             'conformity' => $input['conformity'] ?? null,
    //             'frequency' => $input['frequency'] ?? null,
    //             'last_verification' => $input['last_verification'] ?? null,
    //             'next_verification' => $input['next_verification'] ?? null,
    //             'executed_verification' => $input['executed_verification'] ?? null,
    //             'accordance' => $input['accordance'] ?? null,
    //             'calibration_points_verification' => $input['calibration_points_verification'] ?? null,
    //             'acceptance_criteria' => $input['acceptance_criteria'] ?? null,
    //             'active' => $input['active'] ?? null,
    //             'area_id' => $input['area_id'] ?? null,
    //         ]);

    //         DB::commit();
    //         return $this->sendSuccess('Equipo actualizado con exito.');
    //     } catch (Exception $e) {
    //         DB::rollBack();
    //         return $this->sendError($e->getMessage());
    //     }
    // }

    // public function destroy($id): JsonResponse
    // {
    //     try {
    //         DB::beginTransaction();

    //         $team = Team::find($id);

    //         if (!$team) {
    //             return $this->sendError('No se encontro el equipo.');
    //         }

    //         $team->delete();

    //         DB::commit();
    //         return $this->sendSuccess('Equipo eliminado con exito.');
    //     } catch (Exception $e) {
    //         DB::rollBack();
    //         return $this->sendError($e->getMessage());
    //     }
    // }

    // public function getMovement($code): JsonResponse
    // {
    //     try {
    //         $team = Team::where('code', $code)->first();

    //         if (!$team) {
    //             return $this->sendError('No se encontro un registro');
    //         }

    //         return $this->sendResponse([
    //             'team' => $team,
    //         ], 'Movimeintos del equipo');
    //     } catch (Exception $e) {
    //         return $this->sendError($e->getMessage());
    //     }
    // }

    // public function received($teamId): JsonResponse
    // {
    //     try {
    //         DB::beginTransaction();

    //         $team = Team::find($teamId);
    //         $team->update([
    //             'active' => true
    //         ]);

    //         $movements = MovementItem::where('team_id', $team->id)
    //             ->pluck('movement_id')
    //             ->unique()
    //             ->values();

    //         $uniqueMovements = Movement::whereNull('status')->whereIn('id', $movements)->first();

    //         $total = count($uniqueMovements->items);
    //         $is_total = [];

    //         foreach ($uniqueMovements->items as $item) {
    //             $team = Team::find($item->team_id);

    //             if ($team && $team->active) {
    //                 $is_total[] = $team;
    //             }
    //         }

    //         if ($total === count($is_total)) {
    //             $uniqueMovements->update([
    //                 'status' => 'Equipos devueltos'
    //             ]);
    //         }

    //         DB::commit();
    //         return $this->sendSuccess('Equipo recepcionado');
    //     } catch (Exception $e) {
    //         DB::rollBack();
    //         return $this->sendError($e->getMessage());
    //     }
    // }

    // public function kpis(): JsonResponse
    // {
    //     try {
    //         $total = Team::count();
    //         $in = Team::where('status', 'IN')->count();
    //         $out = Team::where('status', 'OUT')->count();

    //         return $this->sendResponse([
    //             'total' => $total,
    //             'in' => $in,
    //             'out' => $out,
    //         ], 'Enviando kpis');
    //     } catch (Exception $e) {
    //         return $this->sendError($e->getMessage());
    //     }
    // }

    // public function inventoryByDenomination(Request $request)
    // {
    //     $q = trim((string) $request->get('q', ''));
    //     $status = $request->get('status');
    //     $active = $request->has('active') ? $request->boolean('active') : null;
    //     $areaId = $request->get('area_id');

    //     $perPage = (int) $request->get('per_page', 10);
    //     $itemsPerDenom = (int) $request->get('items_per_denom', 10);
    //     $page = (int) $request->get('page', 1);

    //     $sort = (string) $request->get('sort', 'denomination');
    //     $dir = strtolower((string) $request->get('dir', 'asc')) === 'desc' ? 'desc' : 'asc';

    //     $applyFilters = function ($query) use ($q, $status, $active, $areaId) {
    //         $query->whereNull('teams.deleted_at');

    //         if ($active !== null) {
    //             $query->where('teams.active', $active);
    //         }

    //         if (!empty($areaId)) {
    //             $query->where('teams.area_id', $areaId);
    //         }

    //         if (!empty($status)) {
    //             $query->where('teams.status', $status);
    //         }

    //         if ($q !== '') {
    //             $query->where(function ($qq) use ($q) {
    //                 $qq->where('teams.denomination', 'like', "%{$q}%")
    //                     ->orWhere('teams.description', 'like', "%{$q}%")
    //                     ->orWhere('teams.code', 'like', "%{$q}%")
    //                     ->orWhere('teams.brand_manufacturer', 'like', "%{$q}%")
    //                     ->orWhere('teams.model', 'like', "%{$q}%")
    //                     ->orWhere('teams.serie', 'like', "%{$q}%");
    //             });
    //         }

    //         return $query;
    //     };

    //     $denomsQuery = Team::query();
    //     $applyFilters($denomsQuery);

    //     $denomsQuery
    //         ->selectRaw("COALESCE(NULLIF(TRIM(teams.denomination), ''), 'Sin denominación') as denomination_group")
    //         ->selectRaw("COUNT(*) as total")
    //         ->selectRaw("SUM(CASE WHEN teams.status = 'IN' THEN 1 ELSE 0 END) as total_in")
    //         ->selectRaw("SUM(CASE WHEN teams.status = 'OUT' THEN 1 ELSE 0 END) as total_out")
    //         ->groupBy('denomination_group');

    //     $sortMap = [
    //         'denomination' => 'denomination_group',
    //         'total' => 'total',
    //         'in' => 'total_in',
    //         'out' => 'total_out',
    //     ];
    //     $denomsQuery->orderBy($sortMap[$sort] ?? 'denomination_group', $dir);

    //     $denomsPaginator = $denomsQuery->paginate($perPage, ['*'], 'page', $page);

    //     $denoms = collect($denomsPaginator->items())
    //         ->pluck('denomination_group')
    //         ->values();

    //     $teamsGrouped = collect();

    //     if ($denoms->isNotEmpty()) {
    //         $sub = Team::query();
    //         $applyFilters($sub);

    //         $sub->selectRaw("
    //         teams.*,
    //         COALESCE(NULLIF(TRIM(teams.denomination), ''), 'Sin denominación') as denomination_group,
    //         ROW_NUMBER() OVER (
    //             PARTITION BY COALESCE(NULLIF(TRIM(teams.denomination), ''), 'Sin denominación')
    //             ORDER BY teams.id DESC
    //         ) as rn
    //     ")->with('area:id,description');

    //         $teamsGrouped = DB::query()
    //             ->fromSub($sub, 't')
    //             ->whereIn('t.denomination_group', $denoms->all())
    //             ->where('t.rn', '<=', $itemsPerDenom)
    //             ->orderBy('t.denomination_group')
    //             ->orderBy('t.id', 'desc')
    //             ->get()
    //             ->groupBy('denomination_group');
    //     }

    //     $data = collect($denomsPaginator->items())->map(function ($g) use ($teamsGrouped) {
    //         $denom = $g->denomination_group;

    //         return [
    //             'denomination' => $denom,
    //             'total' => (int) $g->total,
    //             'in' => (int) $g->total_in,
    //             'out' => (int) $g->total_out,
    //             'items' => ($teamsGrouped->get($denom, collect()))->values(),
    //         ];
    //     })->values();

    //     return response()->json([
    //         'data' => $data,
    //         'meta' => [
    //             'current_page' => $denomsPaginator->currentPage(),
    //             'per_page' => $denomsPaginator->perPage(),
    //             'last_page' => $denomsPaginator->lastPage(),
    //             'total_denominations' => $denomsPaginator->total(),
    //             'items_per_denom' => $itemsPerDenom,
    //             'filters' => [
    //                 'q' => $q,
    //                 'status' => $status,
    //                 'active' => $active,
    //                 'area_id' => $areaId,
    //                 'sort' => $sort,
    //                 'dir' => $dir,
    //             ],
    //         ],
    //     ]);
    // }

    // public function inventoryByDenominationItems(Request $request)
    // {
    //     $denomination = (string) $request->get('denomination', 'Sin denominación');

    //     $q = trim((string) $request->get('q', ''));
    //     $status = $request->get('status'); // IN | OUT | null
    //     $active = $request->has('active') ? $request->boolean('active') : null;
    //     $areaId = $request->get('area_id');

    //     $perPage = (int) $request->get('per_page', 10);
    //     $page = (int) $request->get('page', 1);

    //     $query = Team::query()
    //         ->whereNull('teams.deleted_at')
    //         ->with('area:id,description');

    //     // Filtros globales
    //     if ($active !== null) $query->where('teams.active', $active);
    //     if (!empty($areaId))  $query->where('teams.area_id', $areaId);
    //     if (!empty($status))  $query->where('teams.status', $status);

    //     // Filtro por denominación (incluye “Sin denominación”)
    //     if ($denomination === 'Sin denominación') {
    //         $query->where(function ($qq) {
    //             $qq->whereNull('teams.denomination')
    //                 ->orWhereRaw("TRIM(teams.denomination) = ''");
    //         });
    //     } else {
    //         $query->whereRaw(
    //             "COALESCE(NULLIF(TRIM(teams.denomination), ''), 'Sin denominación') = ?",
    //             [$denomination]
    //         );
    //     }

    //     // Búsqueda dentro de esa denominación
    //     if ($q !== '') {
    //         $query->where(function ($qq) use ($q) {
    //             $qq->where('teams.description', 'like', "%{$q}%")
    //                 ->orWhere('teams.code', 'like', "%{$q}%")
    //                 ->orWhere('teams.brand_manufacturer', 'like', "%{$q}%")
    //                 ->orWhere('teams.model', 'like', "%{$q}%")
    //                 ->orWhere('teams.serie', 'like', "%{$q}%");
    //         });
    //     }

    //     /**
    //      * ✅ ORDEN FORZADO SIEMPRE (MENOR A MAYOR) POR NÚMERO FINAL DEL CODE
    //      * - GL-OPE-023-08 => 8
    //      * - AIRE-12       => 12
    //      * - Si no termina en número => al final
    //      */
    //     $query->orderByRaw("
    //         CASE
    //             WHEN teams.code REGEXP '[0-9]+$'
    //             THEN CAST(SUBSTRING_INDEX(teams.code, '-', -1) AS UNSIGNED)
    //             ELSE 999999999
    //         END ASC
    //     ");

    //     // desempate estable
    //     $query->orderBy('teams.code', 'asc');

    //     $paginator = $query->paginate($perPage, ['*'], 'page', $page);

    //     // KPIs de esa denominación
    //     $kpisQuery = Team::query()->whereNull('teams.deleted_at');

    //     if ($active !== null) $kpisQuery->where('teams.active', $active);
    //     if (!empty($areaId))  $kpisQuery->where('teams.area_id', $areaId);

    //     if ($denomination === 'Sin denominación') {
    //         $kpisQuery->where(function ($qq) {
    //             $qq->whereNull('teams.denomination')
    //                 ->orWhereRaw("TRIM(teams.denomination) = ''");
    //         });
    //     } else {
    //         $kpisQuery->whereRaw(
    //             "COALESCE(NULLIF(TRIM(teams.denomination), ''), 'Sin denominación') = ?",
    //             [$denomination]
    //         );
    //     }

    //     $kpis = $kpisQuery
    //         ->selectRaw("COUNT(*) as total")
    //         ->selectRaw("SUM(CASE WHEN teams.status='IN' THEN 1 ELSE 0 END) as total_in")
    //         ->selectRaw("SUM(CASE WHEN teams.status='OUT' THEN 1 ELSE 0 END) as total_out")
    //         ->first();

    //     return response()->json([
    //         'data' => $paginator->items(),
    //         'meta' => [
    //             'denomination' => $denomination,
    //             'current_page' => $paginator->currentPage(),
    //             'per_page' => $paginator->perPage(),
    //             'last_page' => $paginator->lastPage(),
    //             'total_items' => $paginator->total(),
    //             'kpis' => [
    //                 'total' => (int) ($kpis->total ?? 0),
    //                 'in' => (int) ($kpis->total_in ?? 0),
    //                 'out' => (int) ($kpis->total_out ?? 0),
    //             ],
    //         ],
    //     ]);
    // }

    // public function teamsNotDelivered(): HttpFoundationJsonResponse
    // {
    //     try {
    //         $today = Carbon::now('America/Lima')->toDateString();

    //         $movements = Movement::query()
    //             ->with([
    //                 'team:id,code,description,denomination,model,serie',
    //                 'company'
    //             ])
    //             ->where('status', 'OUT')
    //             ->whereDate('return_date', '<', $today)
    //             ->whereNull('return_day_now')
    //             ->paginate(15);

    //         return $this->sendResponse($movements, 'Datos enviados correctamente');
    //     } catch (Exception $e) {
    //         return $this->sendError($e->getMessage());
    //     }
    // }

    // public function teamsToBeDelivered(): HttpFoundationJsonResponse
    // {
    //     try {
    //         $today = Carbon::now('America/Lima')->toDateString();
    //         $limitDate = Carbon::now('America/Lima')->addDays(3)->toDateString();

    //         $movements = Movement::query()
    //             ->with([
    //                 'team:id,code,description,denomination,model,serie',
    //                 'company'
    //             ])
    //             ->where('status', 'OUT')
    //             ->whereBetween(DB::raw('DATE(return_date)'), [$today, $limitDate])
    //             ->whereNull('return_day_now')
    //             ->paginate(15);

    //         return $this->sendResponse($movements, 'Datos enviados correctamente');
    //     } catch (Exception $e) {
    //         return $this->sendError($e->getMessage());
    //     }
    // }

    // public function exportTeamsNotDelivered()
    // {
    //     try {
    //         return Excel::download(
    //             new TeamsNotDeliveredExport(),
    //             'equipos_sin_entregar.xlsx'
    //         );
    //     } catch (Exception $e) {
    //         return $this->sendError($e->getMessage());
    //     }
    // }
}
