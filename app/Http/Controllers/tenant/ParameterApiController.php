<?php

namespace App\Http\Controllers\tenant;

use App\Http\Controllers\Controller;
use App\Models\tenant\Parameters;
use App\Models\tenatn\SelectToMetals;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ParameterApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $search = $request->input('search');

            $data = Parameters::query()
                ->with([
                    'typeOfAnalysis',
                    'connectionsParameter',
                ])
                ->when($search, fn($q) => $q->where('description', 'like', "%$search%"))
                ->orderBy('id', 'desc')
                ->paginate(15);

            return $this->sendResponse($data, 'Enviando parametros');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function list(Request $request): JsonResponse
    {
        try {
            $search = $request->input('search');
            $listArray = $request->input('list_array', []);
            $conditionId = $request->input('condition_id');
            $type = $request->input('type');
            $orderId = $request->input('order_id');

            if (!is_array($listArray)) {
                $listArray = [];
            }

            $selects = SelectToMetals::query()
                ->when($orderId, fn($q) => $q->where('order_id', $orderId))
                ->get();

            $data = Parameters::query()
                ->with([
                    'item' => fn($q) => $q
                        ->when($conditionId, fn($q) => $q->where('condition_id', $conditionId))
                        ->when($type, fn($q) => $q->where('type', $type)),
                ])
                ->when(count($listArray) > 0, function ($query) use ($listArray) {
                    $query->whereIn('id', $listArray);
                })
                ->when($search, function ($query) use ($search) {
                    $query->where('description', 'like', "%{$search}%");
                })
                ->orderBy('id', 'desc')
                ->get();

            $mapData = $data->map(function ($p) use ($selects) {
                $select = $selects->where('parameter_id', $p->id)->first();

                return [
                    'id' => $p?->id,
                    'description' => $p?->description,
                    'type_of_analysis_id' => $p?->type_of_analysis_id,
                    'is_metal' => $p?->is_metal,
                    'ids_connections_parameters' => $p?->ids_connections_parameters,
                    'item' => $p?->item,
                    'is_select' => $select !== null,
                    'select_to_metal' => $select,
                ];
            });

            return $this->sendResponse($mapData, 'Enviando parametros');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $input = $request->all();

            Parameters::create([
                'description' => $input['description'],
                'type_of_analysis_id' => $input['type_of_analysis_id'],
            ]);

            DB::commit();
            return $this->sendSuccess('Parametro registrado con exito');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    public function update($id, Request $request): JsonResponse
    {
        try {
            $input = $request->all();

            $parameter = Parameters::findOrFail($id);

            $parameter->update([
                'description' => $input['description'],
                'type_of_analysis_id' => $input['type_of_analysis_id'],
            ]);

            return $this->sendSuccess('Parametro actualizado con exito');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $parameter = Parameters::findOrFail($id);
            $parameter->delete();

            DB::commit();
            return $this->sendSuccess('Parametro eliminado con exito');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }
}
