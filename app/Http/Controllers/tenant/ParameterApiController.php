<?php

namespace App\Http\Controllers\tenant;

use App\Http\Controllers\Controller;
use App\Models\tenant\Parameters;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            $condition_id = $request->input('condition_id');
            $type = $request->input('type');

            if (!is_array($listArray)) {
                $listArray = [];
            }

            $data = Parameters::query()
                ->with([
                    'item' => fn($q) => $q
                        ->when($condition_id, fn($q) => $q->where('condition_id', $condition_id))
                        ->when($type, fn($q) => $q->where('type', $type))
                ])
                ->when(count($listArray) !== 0, function ($query) use ($listArray) {
                    $query->whereIn('id', $listArray);
                })
                ->when($search, function ($query) use ($search) {
                    $query->where('description', 'like', "%{$search}%");
                })
                ->orderBy('id', 'desc')
                ->get();

            return $this->sendResponse($data, 'Enviando parametros');
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
