<?php

namespace App\Http\Controllers\tenant;

use App\Http\Controllers\Controller;
use App\Models\tenant\Item;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ItemsApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $type = $request->input('type');
            $conditionId = $request->input('condition_id');
            $matrixId = $request->input('matrix_id');

            $data = Item::query()
                ->with([
                    'condition',
                    'matrix',
                    'reference',
                    'category',
                    'parameter',
                    'unitMeasurement',
                    'company',
                    'subCategory',
                ])
                ->when($conditionId, fn($q) => $q->where('condition_id', $conditionId))
                ->when($type, fn($q) => $q->where('type', $type))
                ->when($matrixId, fn($q) => $q->where('matrix_id', $matrixId))
                ->paginate(15);

            return $this->sendResponse($data, 'Enviando items');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
