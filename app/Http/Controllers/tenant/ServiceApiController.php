<?php

namespace App\Http\Controllers\tenant;

use App\Http\Controllers\Controller;
use App\Models\tenant\Services;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceApiController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $data = Services::query()
                ->paginate(15);

            return $this->sendResponse($data, 'Enviando servicios');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $input = $request->all();

            Services::create([
                'description' => $input['description'] ?? null,
                'days' => $input['days'] ?? null,
                'amount' => $input['amount'] ?? null,
                'unit_price' => $input['unit_price'] ?? null,
                'price' => $input['price'] ?? null,
            ]);

            DB::commit();
            return $this->sendSuccess('Servicio creado con exito');
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

            $service = Services::find($id);
            $service->update([
                'description' => $input['description'] ?? null,
                'days' => $input['days'] ?? null,
                'amount' => $input['amount'] ?? null,
                'unit_price' => $input['unit_price'] ?? null,
                'price' => $input['price'] ?? null,
            ]);

            DB::commit();
            return $this->sendSuccess('Servicio actualizado con exito');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $service = Services::find($id);
            $service->delete();

            DB::commit();
            return $this->sendSuccess('Servicio eliminado con exito');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }
}
