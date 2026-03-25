<?php

namespace App\Http\Controllers\tenant;

use App\Http\Controllers\Controller;
use App\Models\tenant\LogisticCats;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogisticCatsApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $data = LogisticCats::query()
                ->paginate(15);

            return $this->sendResponse($data, 'Enviando datos');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $input = $request->all();

            LogisticCats::create([
                'description' => $input['description'],
                'unit_price' => $input['unit_price'],
            ]);

            DB::commit();
            return $this->sendSuccess('Registro guardado con exito');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    public function update($id, Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $input = $request->all();

            $logisticCats = LogisticCats::find($id);

            $logisticCats->update([
                'description' => $input['description'],
                'unit_price' => $input['unit_price'],
            ]);

            DB::commit();
            return $this->sendSuccess('Registro actualizado con exito');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $logisticCats = LogisticCats::find($id);
            $logisticCats->destroy();

            DB::commit();
            return $this->sendSuccess('Registro eliminado con exito');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }
}
