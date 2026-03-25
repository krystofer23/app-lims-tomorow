<?php

namespace App\Http\Controllers\tenant;

use App\Http\Controllers\Controller;
use App\Models\tenant\Methodologies;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MethodologiesApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $data = Methodologies::query()
                ->paginate(15);

            return $this->sendResponse($data, 'Enviando metodologias');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $input = $request->all();

            Methodologies::create([
                'description' => $input['description'],
                'info' => $input['info'],
            ]);

            return $this->sendSuccess('Metodologia agregado con exito');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function update($id, Request $request): JsonResponse
    {
        try {
            $input = $request->all();

            $methodologies = Methodologies::findOrFail($id);
            $methodologies->update([
                'description' => $input['description'],
                'info' => $input['info'],
            ]);

            return $this->sendSuccess('Metodologia editado con exito');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function destroy($id, Request $request): JsonResponse
    {
        try {
            $methodologies = Methodologies::findOrFail($id);
            $methodologies->delete();

            return $this->sendSuccess('Metodologia eliminado con exito');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
