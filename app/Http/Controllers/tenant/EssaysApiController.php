<?php

namespace App\Http\Controllers\tenant;

use App\Http\Controllers\Controller;
use App\Models\tenant\Essays;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
            $essays->delete();

            return $this->sendSuccess('Ensayo eliminada con exito');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
