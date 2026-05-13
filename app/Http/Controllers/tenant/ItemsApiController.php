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
            $condition = $request->input('condition');
            $matrix = $request->input('matrix');

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
                ->when($type, fn($q) => $q->where('type', $type))
                ->when($condition, fn($q) => $q->where('condition_id', $condition))
                ->when($matrix, fn($q) => $q->where('matrix_id', $matrix))
                ->paginate(15);

            return $this->sendResponse($data, 'Enviando items');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
