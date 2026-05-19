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
            $matrix = $request->input('matrix');
            $product = $request->input('product');
            $condition = $request->input('condition');
            $type_of_analysis = $request->input('type_of_analysis');

            $data = Item::query()
                ->with([
                    'typeOfSample',
                    'condition',
                    'matrix',
                    'reference',
                    'parameter',
                    'unitMeasurement',
                    'company',
                ])
                ->when($condition, function ($q) use ($condition) {
                    $q->where('condition_id', $condition);
                })
                ->when($product, function ($query) use ($product) {
                    $query->where(function ($q) use ($product) {
                        $q->where('type_of_sample_id', $product)
                            ->orWhereHas('parameter.connectionsParameter', function ($subQuery) use ($product) {
                                $subQuery->where('type_of_samples_id', $product);
                            });
                    });
                })
                ->when($matrix, function ($query) use ($matrix) {
                    $query->where(function ($q) use ($matrix) {
                        $q->where('matrix_id', $matrix)
                            ->orWhereHas('parameter.connectionsParameter', function ($subQuery) use ($matrix) {
                                $subQuery->where('matrix_id', $matrix);
                            });
                    });
                })
                ->when($type_of_analysis, function ($query) use ($type_of_analysis) {
                    $query->whereHas('parameter', fn($q) => $q->where('type_of_analysis_id', $type_of_analysis));
                })
                ->paginate(15);

            return $this->sendResponse($data, 'Enviando items');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
