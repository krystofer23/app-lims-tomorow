<?php

namespace App\Http\Controllers\tenant;

use App\Http\Controllers\Controller;
use App\Models\tenatn\SelectToMetals;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SelectToMetalsApiController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $input = $request->all();

        try {
            DB::beginTransaction();

            SelectToMetals::create([
                'order_id' => $input['order_id'],
                'to_metal_id' => $input['to_metal_id'],
                'parameter_id' => $input['parameter_id'],
                'item' => $input['item'],
            ]);

            DB::commit();
            return $this->sendSuccess('Parametro seleccionado');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $toMetal = SelectToMetals::findOrFail($id);
            $toMetal->delete();

            DB::commit();
            return $this->sendSuccess('Parametro removido');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }
}
