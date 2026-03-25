<?php

namespace App\Http\Controllers\tenant;

use App\Http\Controllers\Controller;
use App\Models\tenant\Conditions;
use App\Models\tenant\Essays;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConditionsApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $data = Conditions::query()
                ->paginate(15);

            return $this->sendResponse($data, 'Enviando condiciones');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $input = $request->all();

            Conditions::create([
                'description' => $input['description'],
                'info' => $input['info'],
            ]);

            return $this->sendSuccess('Condicion creada con exito');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function update($id, Request $request): JsonResponse
    {
        try {
            $input = $request->all();

            $conditions = Conditions::findOrFail($id);
            $conditions->update([
                'description' => $input['description'],
                'info' => $input['info'],
            ]);

            return $this->sendSuccess('Condicion editada con exito');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $conditions = Conditions::findOrFail($id);
            $conditions->delete();

            return $this->sendSuccess('Condicion eliminada con exito');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
