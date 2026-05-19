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
            $typeOfAnalysis = $request->input('type_of_analysis');

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
                ->when($condition, function ($query) use ($condition) {
                    $query->where('condition_id', $condition);
                })
                ->when($product, function ($query) use ($product) {
                    $query->where(function ($query) use ($product) {
                        $query->where('type_of_sample_id', $product)
                            ->orWhereHas('parameter.connectionsParameter', function ($query) use ($product) {
                                $query->where('type_of_samples_id', $product);
                            });
                    });
                })
                ->when($matrix, function ($query) use ($matrix) {
                    $query->where(function ($query) use ($matrix) {
                        $query->where('matrix_id', $matrix)
                            ->orWhereHas('parameter.connectionsParameter', function ($query) use ($matrix) {
                                $query->where('matrix_id', $matrix);
                            });
                    });
                })
                ->when($typeOfAnalysis, function ($query) use ($typeOfAnalysis) {
                    $query->whereHas('parameter', function ($query) use ($typeOfAnalysis) {
                        $query->where('type_of_analysis_id', $typeOfAnalysis);
                    });
                })
                ->paginate(15);

            return $this->sendResponse($data, 'Enviando items');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
