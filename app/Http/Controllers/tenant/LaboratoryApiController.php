<?php

namespace App\Http\Controllers\tenant;

use App\Http\Controllers\Controller;
use App\Models\tenant\ChainCustody;
use App\Models\tenant\OrderService;
use App\Models\tenant\TypeOfSamples;
use App\Models\tenant\UnitsMeasurement;
use App\Models\tenant\LaboratoryResults;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LaboratoryApiController extends Controller
{
    public function orders(Request $request): JsonResponse
    {
        try {
            $application_id = $request->input('application_id');
            $company_id = $request->input('company_id');
            $order_id = $request->input('order_id');

            $query = OrderService::query()
                ->with([
                    'company',
                    'application',
                    'items',
                ]);

            if ($request->has('is_attended')) {
                $isAttended = filter_var($request->is_attended, FILTER_VALIDATE_BOOLEAN);

                if ($isAttended) {
                    $query->whereHas('results');
                } else {
                    $query->doesntHave('results');
                }
            }

            $query
                ->when($application_id, fn($q) => $q->where('application_id', $application_id))
                ->when($company_id, fn($q) => $q->where('company_id', $company_id))
                ->when($order_id, fn($q) => $q->where('id', $order_id));

            $data = $query->paginate(15);

            $orderIds = $data->getCollection()
                ->pluck('id')
                ->filter()
                ->values();

            $typeSamplesCache = TypeOfSamples::query()
                ->pluck('description', 'id');

            $unitMeasurementsCache = UnitsMeasurement::query()
                ->pluck('description', 'id');

            $laboratoryResultsCache = LaboratoryResults::query()
                ->whereIn('order_id', $orderIds)
                ->get()
                ->groupBy('order_id');

            $chainCustodiesCache = ChainCustody::query()
                ->whereIn('order_id', $orderIds)
                ->get()
                ->groupBy('order_id');

            $data->getCollection()->transform(function ($order) use (
                $typeSamplesCache,
                $unitMeasurementsCache,
                $laboratoryResultsCache,
                $chainCustodiesCache
            ) {
                $orderResults = $laboratoryResultsCache->get($order->id, collect())
                    ->keyBy(function ($row) {
                        return $row->item_id . '_' . $row->chain_custody_id;
                    });

                $chainCustodies = $chainCustodiesCache->get($order->id, collect());

                $totalExpected = 0;
                $totalSaved = 0;

                $itemsGrouped = collect($order->items)
                    ->map(function ($row) use (
                        $typeSamplesCache,
                        $unitMeasurementsCache,
                        $orderResults,
                        $chainCustodies,
                        &$totalExpected,
                        &$totalSaved
                    ) {
                        $item = $row->item ?? [];

                        if (is_string($item)) {
                            $item = json_decode($item, true) ?: [];
                        }

                        $realItemId = data_get($row, 'item_id')
                            ?? data_get($item, 'id');

                        $typeOfSampleId = data_get($item, 'type_of_sample_id')
                            ?? data_get($item, 'parameter.connections_parameter.0.type_of_samples_id');

                        $typeOfSampleName = data_get($item, 'type_of_sample.description')
                            ?? $typeSamplesCache->get($typeOfSampleId)
                            ?? 'Sin tipo de muestra';

                        $unitMeasurementId = data_get($item, 'unit_measurement_id');

                        $unitMeasurementName = data_get($item, 'unit_measurement.description')
                            ?? $unitMeasurementsCache->get($unitMeasurementId);

                        $stations = $chainCustodies
                            ->filter(function ($chainCustody) use ($realItemId) {
                                if (!$realItemId) {
                                    return false;
                                }

                                $parameters = $chainCustody->parameters ?? [];

                                if (is_string($parameters)) {
                                    $parameters = json_decode($parameters, true) ?: [];
                                }

                                if (!is_array($parameters)) {
                                    return false;
                                }

                                return collect($parameters)->contains(function ($parameter) use ($realItemId) {
                                    return (int) data_get($parameter, 'id') === (int) $realItemId;
                                });
                            })
                            ->map(function ($chainCustody) use ($realItemId, $orderResults, &$totalExpected, &$totalSaved) {
                                $key = $realItemId . '_' . $chainCustody->id;

                                $savedResult = $orderResults->get($key);

                                $totalExpected++;

                                if (
                                    $savedResult &&
                                    $savedResult->result !== null &&
                                    trim((string) $savedResult->result) !== ''
                                ) {
                                    $totalSaved++;
                                }

                                return [
                                    'chain_custody_id' => $chainCustody->id,
                                    'number_chain' => $chainCustody->number_chain,
                                    'code_lab' => $chainCustody->code_lab,
                                    'code_sample' => $chainCustody->code_sample,
                                    'code_season' => $chainCustody->code_season,
                                    'coordinate' => $chainCustody->coordinate,
                                    'result' => $savedResult?->result,
                                    'laboratory_result_id' => $savedResult?->id,
                                ];
                            })
                            ->values();

                        return [
                            'id' => $row->id,
                            'item_id' => $realItemId,

                            'type_of_sample_id' => $typeOfSampleId,
                            'type_of_sample' => $typeOfSampleName,

                            'matrix_id' => data_get($item, 'matrix_id'),
                            'matrix' => data_get($item, 'matrix.description'),

                            'parameter_id' => data_get($item, 'parameter_id'),
                            'parameter' => data_get($item, 'parameter.description'),

                            'unit_measurement_id' => $unitMeasurementId,
                            'unit_measurement' => $unitMeasurementName,

                            'lcm' => data_get($item, 'lcm'),

                            'reference_id' => data_get($item, 'reference_id'),
                            'reference_code' => data_get($item, 'reference.code'),
                            'reference_title' => data_get($item, 'reference.title'),

                            'condition_id' => data_get($item, 'condition_id'),
                            'condition' => data_get($item, 'condition.description'),

                            'type' => data_get($item, 'type'),
                            'price' => data_get($item, 'price'),
                            'number_samples' => data_get($item, 'number_samples'),

                            'stations' => $stations,
                            'stations_count' => $stations->count(),
                            'has_chain_custody' => $stations->isNotEmpty(),
                        ];
                    })
                    ->sortBy(function ($item) {
                        return $item['type_of_sample_id'] ?? 999999;
                    })
                    ->groupBy(function ($item) {
                        return $item['type_of_sample_id'] ?? 'without_type_of_sample';
                    })
                    ->map(function ($group) {
                        $first = $group->first();

                        return [
                            'type_of_sample_id' => $first['type_of_sample_id'],
                            'type_of_sample' => $first['type_of_sample'],
                            'items' => $group->values(),
                        ];
                    })
                    ->values();

                $order->items_grouped = $itemsGrouped;

                $order->total_results_expected = $totalExpected;
                $order->total_results_saved = $totalSaved;
                $order->is_completed = $totalExpected > 0 && $totalExpected === $totalSaved;

                return $order;
            });

            return $this->sendResponse($data, 'Enviando ordenes de servicio');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function show($orderId): JsonResponse
    {
        try {
            $order = OrderService::query()
                ->with('items')
                ->findOrFail($orderId);

            $items = $order->items;

            $typeSamplesCache = TypeOfSamples::query()
                ->pluck('description', 'id');

            $unitMeasurementsCache = UnitsMeasurement::query()
                ->pluck('description', 'id');

            $chainCustodies = ChainCustody::query()
                ->where('order_id', $orderId)
                ->get();

            $laboratoryResultsCache = LaboratoryResults::query()
                ->where('order_id', $orderId)
                ->get()
                ->keyBy(function ($row) {
                    return $row->item_id . '_' . $row->chain_custody_id;
                });

            $mapData = collect($items)
                ->map(function ($row) use (
                    $typeSamplesCache,
                    $unitMeasurementsCache,
                    $laboratoryResultsCache,
                    $chainCustodies
                ) {
                    $item = $row->item ?? [];

                    if (is_string($item)) {
                        $item = json_decode($item, true) ?: [];
                    }

                    /*
                 |--------------------------------------------------------------------------
                 | ID real del parámetro/item
                 |--------------------------------------------------------------------------
                 | En chain_custody.parameters[].id se guarda el ID del item real.
                 | En tu JSON ese ID es 226, 228, 227, etc.
                 */
                    $realItemId = data_get($row, 'item_id')
                        ?? data_get($item, 'id');

                    $typeOfSampleId = data_get($item, 'type_of_sample_id')
                        ?? data_get($item, 'parameter.connections_parameter.0.type_of_samples_id');

                    $typeOfSampleName = data_get($item, 'type_of_sample.description')
                        ?? $typeSamplesCache->get($typeOfSampleId)
                        ?? 'Sin tipo de muestra';

                    $unitMeasurementId = data_get($item, 'unit_measurement_id');

                    $unitMeasurementName = data_get($item, 'unit_measurement.description')
                        ?? $unitMeasurementsCache->get($unitMeasurementId);

                    $stations = $chainCustodies
                        ->filter(function ($chainCustody) use ($realItemId) {
                            if (!$realItemId) {
                                return false;
                            }

                            $parameters = $chainCustody->parameters ?? [];

                            if (is_string($parameters)) {
                                $parameters = json_decode($parameters, true) ?: [];
                            }

                            if (!is_array($parameters)) {
                                return false;
                            }

                            return collect($parameters)->contains(function ($parameter) use ($realItemId) {
                                return (int) data_get($parameter, 'id') === (int) $realItemId;
                            });
                        })
                        ->map(function ($chainCustody) use ($realItemId, $laboratoryResultsCache) {
                            $key = $realItemId . '_' . $chainCustody->id;

                            $savedResult = $laboratoryResultsCache->get($key);

                            return [
                                'chain_custody_id' => $chainCustody->id,
                                'number_chain' => $chainCustody->number_chain,
                                'code_lab' => $chainCustody->code_lab,
                                'code_sample' => $chainCustody->code_sample,
                                'code_season' => $chainCustody->code_season,
                                'coordinate' => $chainCustody->coordinate,
                                'result' => $savedResult?->result,
                                'laboratory_result_id' => $savedResult?->id,
                            ];
                        })
                        ->values();

                    return [
                        'id' => $row->id,
                        'item_id' => $realItemId,

                        'type_of_sample_id' => $typeOfSampleId,
                        'type_of_sample' => $typeOfSampleName,

                        'matrix_id' => data_get($item, 'matrix_id'),
                        'matrix' => data_get($item, 'matrix.description'),

                        'parameter_id' => data_get($item, 'parameter_id'),
                        'parameter' => data_get($item, 'parameter.description'),

                        'unit_measurement_id' => $unitMeasurementId,
                        'unit_measurement' => $unitMeasurementName,

                        'lcm' => data_get($item, 'lcm'),

                        'reference_id' => data_get($item, 'reference_id'),
                        'reference_code' => data_get($item, 'reference.code'),
                        'reference_title' => data_get($item, 'reference.title'),

                        'condition_id' => data_get($item, 'condition_id'),
                        'condition' => data_get($item, 'condition.description'),

                        'type' => data_get($item, 'type'),
                        'price' => data_get($item, 'price'),
                        'number_samples' => data_get($item, 'number_samples'),

                        'stations' => $stations,
                        'stations_count' => $stations->count(),
                        'has_chain_custody' => $stations->isNotEmpty(),
                    ];
                })
                ->sortBy(function ($item) {
                    return $item['type_of_sample_id'] ?? 999999;
                })
                ->groupBy(function ($item) {
                    return $item['type_of_sample_id'] ?? 'without_type_of_sample';
                })
                ->map(function ($group) {
                    $first = $group->first();

                    return [
                        'type_of_sample_id' => $first['type_of_sample_id'],
                        'type_of_sample' => $first['type_of_sample'],
                        'items' => $group->values(),
                    ];
                })
                ->values();

            return $this->sendResponse($mapData, 'Enviando datos de la orden');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $input = $request->validate([
                'order_id' => ['required', 'integer'],
                'results' => ['required', 'array'],
                'results.*.item_id' => ['required', 'integer'],
                'results.*.order_item_id' => ['nullable', 'integer'],
                'results.*.chain_custody_id' => ['required', 'integer'],
                'results.*.result' => ['nullable', 'string'],
            ]);

            if (empty($input['results'])) {
                return $this->sendError('No se enviaron resultados para guardar.');
            }

            DB::beginTransaction();

            foreach ($input['results'] as $row) {
                $chainCustody = ChainCustody::query()
                    ->where('id', $row['chain_custody_id'])
                    ->where('order_id', $input['order_id'])
                    ->first();

                if (!$chainCustody) {
                    throw new Exception('La cadena de custodia no pertenece a esta orden.');
                }

                $parameters = $chainCustody->parameters ?? [];

                if (is_string($parameters)) {
                    $parameters = json_decode($parameters, true) ?: [];
                }

                if (!is_array($parameters)) {
                    $parameters = [];
                }

                $parameterData = collect($parameters)
                    ->first(function ($parameter) use ($row) {
                        return (int) data_get($parameter, 'id') === (int) $row['item_id'];
                    });

                if (!$parameterData) {
                    throw new Exception('El parámetro no pertenece a la cadena de custodia seleccionada.');
                }

                LaboratoryResults::query()->updateOrCreate(
                    [
                        'order_id' => $input['order_id'],
                        'item_id' => $row['item_id'],
                        'chain_custody_id' => $row['chain_custody_id'],
                    ],
                    [
                        'order_item_id' => $row['order_item_id'] ?? null,
                        'parameter_id' => data_get($parameterData, 'parameter_id'),
                        'matrix_id' => $chainCustody->matrix_id,
                        'type_of_sample_id' => $chainCustody->type_of_sample_id,
                        'code_season' => $chainCustody->code_season,
                        'code_lab' => $chainCustody->code_lab,
                        'code_sample' => $chainCustody->code_sample,
                        'result' => $row['result'] ?? null,
                    ]
                );
            }

            DB::commit();

            return $this->sendSuccess('Resultados guardados con éxito');
        } catch (Exception $e) {
            DB::rollBack();

            return $this->sendError($e->getMessage());
        }
    }
}
