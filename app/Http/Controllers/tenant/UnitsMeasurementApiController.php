<?php

namespace App\Http\Controllers\tenant;

use App\Http\Controllers\Controller;
use App\Models\tenant\UnitsMeasurement;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UnitsMeasurementApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $data = UnitsMeasurement::get();

            return $this->sendResponse($data, 'Enviando unidades de medida');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $input = $request->all();

            UnitsMeasurement::create([
                'description' => $input['description']
            ]);

            return $this->sendSuccess('Unidad de medida agregado con exito');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function update($id, Request $request): JsonResponse
    {
        try {
            $input = $request->all();

            $unitMeasurement = UnitsMeasurement::findOrFail($id);

            $unitMeasurement->update([
                'description' => $input['description']
            ]);

            return $this->sendSuccess('Unidad de medida editado con exito');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function destroy($id, Request $request): JsonResponse
    {
        try {
            $unitMeasurement = UnitsMeasurement::findOrFail($id);
            $unitMeasurement->delete();

            return $this->sendSuccess('Unidad de medida eliminado con exito');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
