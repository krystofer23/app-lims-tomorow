<?php

namespace App\Http\Controllers\tenant;

use App\Http\Controllers\Controller;
use App\Models\tenant\Item;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemsApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $type = $request->input('type');
            $matrix = $request->input('matrix');
            $product = $request->input('product');
            $condition = $request->input('condition');
            $typeOfAnalysis = $request->input('type_of_analysis');

            $parameter = $request->input('parameter');
            $condition_id = $request->input('condition_id');
            $type_of_sample_id = $request->input('type_of_sample_id');

            $param = $request->input('param');

            $data = Item::query()
                ->with([
                    'typeOfSample',
                    'condition',
                    'matrix',
                    'reference',
                    'parameter',
                    'parameter.connectionsParameter' => fn($q) => $q
                        ->when($matrix, fn($query) => $query->where('matrix_id', $matrix))
                        ->when($product, fn($query) => $query->where('type_of_samples_id', $product)),
                    'parameter.connectionsParameter.matrix',
                    'parameter.connectionsParameter.typeOfSample',
                    'unitMeasurement',
                    'company',
                ])
                ->when($condition, function ($query) use ($condition) {
                    $query->where('condition_id', $condition);
                })
                ->when($condition_id, function ($query) use ($condition_id) {
                    $query->where('condition_id', $condition_id);
                })
                ->when($type, function ($query) use ($type) {
                    $query->where('type', $type);
                })
                ->when($product, function ($query) use ($product) {
                    $query->where(function ($query) use ($product) {
                        $query->where('type_of_sample_id', $product)
                            ->orWhereHas('parameter.connectionsParameter', function ($query) use ($product) {
                                $query->where('type_of_samples_id', $product);
                            });
                    });
                })
                ->when($type_of_sample_id, function ($query) use ($type_of_sample_id) {
                    $query->where(function ($query) use ($type_of_sample_id) {
                        $query->where('type_of_sample_id', $type_of_sample_id)
                            ->orWhereHas('parameter.connectionsParameter', function ($query) use ($type_of_sample_id) {
                                $query->where('type_of_samples_id', $type_of_sample_id);
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
                ->when($parameter, function ($query) use ($parameter) {
                    $query->whereHas('parameter', function ($query) use ($parameter) {
                        $query->where('description', 'like', "%$parameter%");
                    });
                })
                ->when($param, function ($query) use ($param) {
                    $query->whereHas('parameter', function ($query) use ($param) {
                        $query->where('description', 'like', "%$param%");
                    });
                })
                ->paginate(15);

            return $this->sendResponse($data, 'Enviando items');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function updateUnitPrice($id, Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $item = Item::findOrFail($id);
            $item->update([
                'unit_price' => $request->unit_price ?? 0.0
            ]);

            DB::commit();
            return $this->sendSuccess('Precio unitario actualizado con exito');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }
}
