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
            $data = Parameters::query()
                ->with([
                    'typeOfAnalysis',
                    'connectionsParameter',
                ])
                ->paginate(15);

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
                'type_of_analysis_id',
            ]);

            return $this->sendSuccess('Parametro actualizado con exito');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
