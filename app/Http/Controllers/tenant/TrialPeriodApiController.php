<?php

namespace App\Http\Controllers\tenant;

use App\Http\Controllers\Controller;
use App\Models\tenant\TrialPeriod;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrialPeriodApiController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        try {
            $orderId = $request->input('order_id');
            $type_of_sample_id = $request->input('type_of_sample_id');
            $condition_id = $request->input('condition_id');

            $data = TrialPeriod::query()
                ->where('order_id', $orderId)
                ->where('type_of_sample_id', $type_of_sample_id)
                ->where('condition_id', $condition_id)
                ->first();

            return $this->sendResponse([
                'id' => $data?->id,
                'date_init' => $data?->date_init ? Carbon::make($data?->date_init)->format('Y-m-d') : null,
                'date_end' => $data?->date_end ? Carbon::make($data?->date_end)->format('Y-m-d') : null,
                'order_id' => $data?->order_id,
                'type_of_sample_id' => $data?->type_of_sample_id,
                'condition_id' => $data?->condition_id,
            ], 'Enviando datos');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $input = $request->all();

            TrialPeriod::updateOrCreate(
                [
                    'order_id' => $input['order_id'] ?? null,
                    'type_of_sample_id' => $input['type_of_sample_id'] ?? null,
                    'condition_id' => $input['condition_id'] ?? null,
                ],
                [
                    'date_init' => $input['date_init'] ?? null,
                    'date_end' => $input['date_end'] ?? null,
                ]
            );

            DB::commit();
            return $this->sendSuccess('Periodo de ensayo guardado con exito');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }
}
