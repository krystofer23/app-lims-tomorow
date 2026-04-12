<?php

namespace App\Http\Controllers\tenant;

use App\Http\Controllers\Controller;
use App\Models\tenant\ItemsMatriz;
use App\Models\tenant\Matriz;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MatrizApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $search = $request->input('query', null);
            $type = $request->input('type', null);

            $query = Matriz::query()
                ->with([
                    'itemsMatriz.essays',
                    'itemsMatriz.essays.unitsMeasurement',
                    'itemsMatriz.essays.condition',
                    'methodologie'
                ])
                ->when($request->filled('query'), function ($q) use ($search) {
                    $q->where('description', $search);
                })
                ->when($request->filled('type'), function ($q) use ($type) {
                    $q->where('description', $type);
                })
                ->orderByDesc('id')
                ->paginate(15);

            $data = collect($query->items())->map(function ($item) {
                return [
                    'id' => $item->id,
                    'description' => $item->description,
                    'methodologie_id' => $item->methodologie_id,
                    'number_samples' => $item->number_samples,
                    'methodologie' => $item->methodologie,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                    'unit_price' => $item?->unit_price,
                    'price' => $item?->price,

                    'essays' => collect($item->itemsMatriz)->map(function ($value) {
                        $essay = $value['essays'];

                        return [
                            'id' => $essay['id'],
                            'description' => $essay['description'],
                            'lcm' => $essay['lcm'],
                            'units_measurement_id' => $essay['units_measurement_id'],
                            'condition_id' => $essay['condition_id'],
                            'condition' => $essay['condition'],
                            'units_measurement' => $essay['unitsMeasurement']
                        ];
                    })
                ];
            });

            return $this->sendResponse([
                'data' => $data,
                'current_page' => $query->currentPage(),
                'last_page' => $query->lastPage(),
                'total' => $query->total(),
                'per_page' => $query->perPage(),
            ], 'Enviando ensayos');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $input = $request->all();

            $matriz = Matriz::create([
                'description' => $input['description'],
                'methodologie_id' => $input['methodologie_id'],
                'number_samples' => $input['number_samples'],
                'unit_price' => $input['unit_price'],
                'price' => $input['price'],
            ]);

            $essays = $input['essays'] ?? [];

            if (count($essays) !== 0) {
                foreach ($essays as $key => $value) {
                    ItemsMatriz::create([
                        'matriz_id' => $matriz?->id,
                        'essays_id' => $value['id'],
                    ]);
                }
            }

            DB::commit();
            return $this->sendSuccess('Matriz creada con exito');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    public function update($id, Request $request): JsonResponse
    {
        try {
            $input = $request->all();

            $matriz = Matriz::findOrFail($id);
            $matriz->update([
                'description' => $input['description'],
                'methodologie_id' => $input['methodologie_id'],
                'number_samples' => $input['number_samples'],
                'unit_price' => $input['unit_price'],
                'price' => $input['price'],
            ]);

            $essays = $input['essays'] ?? [];

            if (count($essays) !== 0) {
                foreach ($essays as $key => $value) {
                    ItemsMatriz::updateOrCreate([
                        'matriz_id' => $matriz?->id,
                        'essays_id' => $value['id'],
                    ]);
                }
            }

            return $this->sendSuccess('Matriz editada con exito');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $matriz = Matriz::findOrFail($id);
            $matriz->delete();

            return $this->sendSuccess('Matriz eliminada con exito');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
