<?php

namespace App\Http\Controllers\tenant;

use App\Http\Controllers\Controller;
use App\Models\tenant\ChainCustody;
use App\Models\tenant\OrderService;
use App\Models\tenant\TypeOfSamples;
use App\Models\tenant\UnitsMeasurement;
use App\Models\tenant\LaboratoryResults;
use App\Models\tenatn\SelectToMetals;
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
                    $axis = strtoupper($row->result_axis ?: 'NORMAL');
                    $type = strtoupper($row->result_type ?: 'NORMAL');

                    return $row->item_id . '_' . $row->chain_custody_id . '_' . $axis . '_' . $type;
                });

            $selectedMetalsByToMetalId = SelectToMetals::query()
                ->where('order_id', $orderId)
                ->get()
                ->groupBy(function ($row) {
                    return (int) $row->to_metal_id;
                });

            $mapData = collect($items)
                ->flatMap(function ($row) use (
                    $typeSamplesCache,
                    $unitMeasurementsCache,
                    $laboratoryResultsCache,
                    $chainCustodies,
                    $selectedMetalsByToMetalId
                ) {
                    $item = $row->item ?? [];

                    if (is_string($item)) {
                        $item = json_decode($item, true) ?: [];
                    }

                    if (!is_array($item)) {
                        $item = [];
                    }

                    $realItemId = data_get($row, 'item_id')
                        ?? data_get($item, 'id');

                    $realItemId = $realItemId !== null ? (int) $realItemId : null;

                    $parameterId = data_get($item, 'parameter_id')
                        ?? data_get($item, 'parameter.id');

                    $parameterId = $parameterId !== null ? (int) $parameterId : null;

                    $buildItem = function (
                        array $currentItem,
                        ?int $resultItemId = null,
                        ?int $stationSearchItemId = null,
                        ?int $stationSearchParameterId = null,
                        array $extra = []
                    ) use (
                        $row,
                        $realItemId,
                        $typeSamplesCache,
                        $unitMeasurementsCache,
                        $laboratoryResultsCache,
                        $chainCustodies
                    ) {
                        $itemIdForResult = $resultItemId
                            ?? $realItemId
                            ?? data_get($currentItem, 'id');

                        $itemIdForResult = $itemIdForResult !== null ? (int) $itemIdForResult : null;

                        $itemIdForStations = $stationSearchItemId
                            ?? $realItemId
                            ?? data_get($currentItem, 'id');

                        $itemIdForStations = $itemIdForStations !== null ? (int) $itemIdForStations : null;

                        $typeOfSampleId = data_get($currentItem, 'type_of_sample_id')
                            ?? data_get($currentItem, 'type_of_sample_filter')
                            ?? data_get($currentItem, 'parameter.connections_parameter.0.type_of_samples_id');

                        $typeOfSampleId = $typeOfSampleId !== null ? (int) $typeOfSampleId : null;

                        $typeOfSampleName = data_get($currentItem, 'type_of_sample.description')
                            ?? data_get($currentItem, 'parameter.connections_parameter.0.type_of_sample.description')
                            ?? $typeSamplesCache->get($typeOfSampleId)
                            ?? 'Sin tipo de muestra';

                        $unitMeasurementId = data_get($currentItem, 'unit_measurement_id');
                        $unitMeasurementId = $unitMeasurementId !== null ? (int) $unitMeasurementId : null;

                        $unitMeasurementName = data_get($currentItem, 'unit_measurement.description')
                            ?? $unitMeasurementsCache->get($unitMeasurementId);

                        $conditionId = data_get($currentItem, 'condition_id');
                        $conditionId = $conditionId !== null ? (int) $conditionId : null;

                        $matrixId = data_get($currentItem, 'matrix_id')
                            ?? data_get($currentItem, 'matrix_filter')
                            ?? data_get($currentItem, 'parameter.connections_parameter.0.matrix_id');

                        $matrixId = $matrixId !== null ? (int) $matrixId : null;

                        $matrixName = data_get($currentItem, 'matrix.description')
                            ?? data_get($currentItem, 'parameter.connections_parameter.0.matrix.description');

                        $parameterId = data_get($currentItem, 'parameter_id')
                            ?? data_get($currentItem, 'parameter.id');

                        $parameterId = $parameterId !== null ? (int) $parameterId : null;

                        $stations = $chainCustodies
                            ->filter(function ($chainCustody) use (
                                $itemIdForStations,
                                $stationSearchParameterId,
                                $parameterId
                            ) {
                                if (!$itemIdForStations && !$stationSearchParameterId && !$parameterId) {
                                    return false;
                                }

                                $parameters = $chainCustody->parameters ?? [];

                                if (is_string($parameters)) {
                                    $parameters = json_decode($parameters, true) ?: [];
                                }

                                if (!is_array($parameters)) {
                                    return false;
                                }

                                return collect($parameters)->contains(function ($parameter) use (
                                    $itemIdForStations,
                                    $stationSearchParameterId,
                                    $parameterId
                                ) {
                                    $chainItemId = data_get($parameter, 'id')
                                        ?? data_get($parameter, 'item_id');

                                    $chainParameterId = data_get($parameter, 'parameter_id')
                                        ?? data_get($parameter, 'parameter.id');

                                    $matchByItemId = $itemIdForStations
                                        && $chainItemId
                                        && (int) $chainItemId === (int) $itemIdForStations;

                                    $matchByStationParameterId = $stationSearchParameterId
                                        && $chainParameterId
                                        && (int) $chainParameterId === (int) $stationSearchParameterId;

                                    $matchByCurrentParameterId = $parameterId
                                        && $chainParameterId
                                        && (int) $chainParameterId === (int) $parameterId;

                                    return $matchByItemId
                                        || $matchByStationParameterId
                                        || $matchByCurrentParameterId;
                                });
                            })
                            ->map(function ($chainCustody) use ($itemIdForResult, $laboratoryResultsCache, $row) {
                                $baseKey = $itemIdForResult . '_' . $chainCustody->id;

                                $getSavedResult = function (string $axis = 'NORMAL', string $type = 'NORMAL') use ($laboratoryResultsCache, $baseKey) {
                                    $axis = strtoupper($axis);
                                    $type = strtoupper($type);

                                    return $laboratoryResultsCache->get($baseKey . '_' . $axis . '_' . $type);
                                };

                                $baseData = [
                                    'chain_custody_id' => $chainCustody->id,
                                    'number_chain' => $chainCustody->number_chain,
                                    'code_lab' => $chainCustody->code_lab,
                                    'code_sample' => $chainCustody->code_sample,
                                    'code_season' => $chainCustody->code_season,
                                    'coordinate' => $chainCustody->coordinate,
                                ];

                                if (data_get($row, 'item.type') === 'VIBRACION') {
                                    $savedResultXPpv = $getSavedResult('X', 'PPV');
                                    $savedResultXFrec = $getSavedResult('X', 'FREC');

                                    $savedResultYPpv = $getSavedResult('Y', 'PPV');
                                    $savedResultYFrec = $getSavedResult('Y', 'FREC');

                                    $savedResultZPpv = $getSavedResult('Z', 'PPV');
                                    $savedResultZFrec = $getSavedResult('Z', 'FREC');

                                    return array_merge($baseData, [
                                        'result_x_ppv' => $savedResultXPpv?->result,
                                        'result_x_frec' => $savedResultXFrec?->result,

                                        'result_y_ppv' => $savedResultYPpv?->result,
                                        'result_y_frec' => $savedResultYFrec?->result,

                                        'result_z_ppv' => $savedResultZPpv?->result,
                                        'result_z_frec' => $savedResultZFrec?->result,

                                        'laboratory_result_x_ppv_id' => $savedResultXPpv?->id,
                                        'laboratory_result_x_frec_id' => $savedResultXFrec?->id,

                                        'laboratory_result_y_ppv_id' => $savedResultYPpv?->id,
                                        'laboratory_result_y_frec_id' => $savedResultYFrec?->id,

                                        'laboratory_result_z_ppv_id' => $savedResultZPpv?->id,
                                        'laboratory_result_z_frec_id' => $savedResultZFrec?->id,
                                    ]);
                                }

                                $savedResult = $getSavedResult();

                                return array_merge($baseData, [
                                    'result' => $savedResult?->result,
                                    'laboratory_result_id' => $savedResult?->id,
                                ]);
                            })
                            ->values();

                        return array_merge([
                            'id' => $row->id,

                            'item_id' => $itemIdForResult,

                            'order_item_id' => $realItemId,

                            'type_of_sample_id' => $typeOfSampleId,
                            'type_of_sample' => $typeOfSampleName,

                            'matrix_id' => $matrixId,
                            'matrix' => $matrixName,

                            'parameter_id' => $parameterId,
                            'parameter' => data_get($currentItem, 'parameter.description'),

                            'unit_measurement_id' => $unitMeasurementId,
                            'unit_measurement' => $unitMeasurementName,

                            'lcm' => data_get($currentItem, 'lcm'),

                            'reference_id' => data_get($currentItem, 'reference_id'),
                            'reference_code' => data_get($currentItem, 'reference.code'),
                            'reference_title' => data_get($currentItem, 'reference.title'),

                            'condition_id' => $conditionId,
                            'condition' => data_get($currentItem, 'condition.description'),

                            'type' => data_get($currentItem, 'type'),
                            'price' => data_get($currentItem, 'price'),
                            'unit_price' => data_get($currentItem, 'unit_price'),
                            'number_samples' => data_get($currentItem, 'number_samples'),

                            'stations' => $stations,
                            'stations_count' => $stations->count(),
                            'has_chain_custody' => $stations->isNotEmpty(),

                            'is_vibration' => $row->item['type'] === 'VIBRACION' ? true : false
                        ], $extra);
                    };

                    $normalItem = $buildItem(
                        $item,
                        $realItemId,
                        $realItemId,
                        $parameterId,
                        [
                            'is_metal_child' => false,
                            'select_to_metal_id' => null,
                            'to_metal_id' => null,
                            'parent_item_id' => null,
                            'parent_parameter_id' => null,
                        ]
                    );

                    $metalRows = collect();

                    if ($parameterId && $selectedMetalsByToMetalId->has($parameterId)) {
                        $metalRows = $selectedMetalsByToMetalId
                            ->get($parameterId)
                            ->map(function ($metal) use ($buildItem, $realItemId, $parameterId) {
                                $metalItem = $metal->item ?? [];

                                if (is_string($metalItem)) {
                                    $metalItem = json_decode($metalItem, true) ?: [];
                                }

                                if (!is_array($metalItem)) {
                                    $metalItem = [];
                                }

                                return $buildItem(
                                    $metalItem,
                                    (int) $metal->id,
                                    $realItemId,
                                    (int) $metal->parameter_id,
                                    [
                                        'is_metal_child' => true,
                                        'select_to_metal_id' => (int) $metal->id,
                                        'to_metal_id' => (int) $metal->to_metal_id,
                                        'parent_item_id' => $realItemId,
                                        'parent_parameter_id' => $parameterId,
                                    ]
                                );
                            })
                            ->values();
                    }

                    return collect([$normalItem])
                        ->merge($metalRows);
                })
                ->sortBy(function ($item) {
                    return $item['type_of_sample_id'] ?? 999999;
                })
                ->groupBy(function ($item) {
                    return $item['type_of_sample_id'] ?? 'without_type_of_sample';
                })
                ->map(function ($group) {
                    $first = $group->first();

                    $ias = collect($group)
                        ->where('condition_id', 2)
                        ->values();

                    $inacal = collect($group)
                        ->where('condition_id', 1)
                        ->values();

                    $noCondition = collect($group)
                        ->filter(function ($item) {
                            return in_array($item['condition_id'], [3, null], true);
                        })
                        ->values();

                    return [
                        'type_of_sample_id' => $first['type_of_sample_id'],
                        'type_of_sample' => $first['type_of_sample'],
                        'items_ias' => $ias,
                        'items_inacal' => $inacal,
                        'items' => $noCondition,
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

                'results.*.parameter_id' => ['nullable', 'integer'],
                'results.*.is_metal_child' => ['nullable', 'boolean'],
                'results.*.select_to_metal_id' => ['nullable', 'integer'],
                'results.*.to_metal_id' => ['nullable', 'integer'],
                'results.*.parent_item_id' => ['nullable', 'integer'],
                'results.*.parent_parameter_id' => ['nullable', 'integer'],

                'results.*.is_vibration' => ['nullable', 'boolean'],
                'results.*.result_x' => ['nullable', 'string'],
                'results.*.result_y' => ['nullable', 'string'],
                'results.*.result_z' => ['nullable', 'string'],
            ]);

            if (empty($input['results'])) {
                return $this->sendError('No se enviaron resultados para guardar.');
            }

            DB::beginTransaction();

            $chainCustodyIds = collect($input['results'])
                ->pluck('chain_custody_id')
                ->filter()
                ->unique()
                ->values();

            $chainCustodies = ChainCustody::query()
                ->where('order_id', $input['order_id'])
                ->whereIn('id', $chainCustodyIds)
                ->get()
                ->keyBy('id');

            foreach ($input['results'] as $row) {
                $rowItemId = (int) $row['item_id'];

                $rowParameterId = isset($row['parameter_id']) && $row['parameter_id'] !== null
                    ? (int) $row['parameter_id']
                    : null;

                $chainCustody = $chainCustodies->get((int) $row['chain_custody_id']);

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

                $parameterData = collect($parameters)->first(function ($parameter) use ($rowItemId, $rowParameterId) {
                    $chainItemId = data_get($parameter, 'id')
                        ?? data_get($parameter, 'item_id');

                    $chainParameterId = data_get($parameter, 'parameter_id')
                        ?? data_get($parameter, 'parameter.id');

                    if ($chainItemId && (int) $chainItemId === $rowItemId) {
                        return true;
                    }

                    if ($rowParameterId && $chainParameterId && (int) $chainParameterId === $rowParameterId) {
                        return true;
                    }

                    if ($chainParameterId && (int) $chainParameterId === $rowItemId) {
                        return true;
                    }

                    return false;
                });

                if (!$parameterData) {
                    throw new Exception('El parámetro no pertenece a la cadena de custodia seleccionada.');
                }

                $parameterId = data_get($parameterData, 'parameter_id')
                    ?? data_get($parameterData, 'parameter.id')
                    ?? $rowParameterId
                    ?? $rowItemId;

                $matrixId = data_get($parameterData, 'matrix_id')
                    ?? data_get($parameterData, 'matrix.id')
                    ?? data_get($parameterData, 'matrix_filter')
                    ?? data_get($parameterData, 'parameter.connections_parameter.0.matrix_id')
                    ?? $chainCustody->matrix_id;

                $typeOfSampleId = data_get($parameterData, 'type_of_sample_id')
                    ?? data_get($parameterData, 'type_of_sample.id')
                    ?? data_get($parameterData, 'type_of_sample_filter')
                    ?? data_get($parameterData, 'parameter.connections_parameter.0.type_of_samples_id')
                    ?? data_get($parameterData, 'parameter.connections_parameter.0.type_of_sample.id')
                    ?? $chainCustody->type_of_sample_id;

                $isVibration = (bool) data_get($row, 'is_vibration', false);

                if ($isVibration) {
                    $vibrationResults = [
                        ['axis' => 'X', 'type' => 'PPV',  'value' => $row['result_x_ppv'] ?? null],
                        ['axis' => 'X', 'type' => 'FREC', 'value' => $row['result_x_frec'] ?? null],

                        ['axis' => 'Y', 'type' => 'PPV',  'value' => $row['result_y_ppv'] ?? null],
                        ['axis' => 'Y', 'type' => 'FREC', 'value' => $row['result_y_frec'] ?? null],

                        ['axis' => 'Z', 'type' => 'PPV',  'value' => $row['result_z_ppv'] ?? null],
                        ['axis' => 'Z', 'type' => 'FREC', 'value' => $row['result_z_frec'] ?? null],
                    ];


                    foreach ($vibrationResults as $vibrationResult) {
                        LaboratoryResults::query()->updateOrCreate(
                            [
                                'order_id' => $input['order_id'],
                                'item_id' => $rowItemId,
                                'chain_custody_id' => (int) $row['chain_custody_id'],
                                'result_axis' => $vibrationResult['axis'],
                                'result_type' => $vibrationResult['type'],
                            ],
                            [
                                'order_item_id' => $row['order_item_id'] ?? null,

                                'parameter_id' => $parameterId,
                                'matrix_id' => $matrixId,
                                'type_of_sample_id' => $typeOfSampleId,

                                'code_season' => $chainCustody->code_season,
                                'code_lab' => $chainCustody->code_lab,
                                'code_sample' => $chainCustody->code_sample,

                                'result' => $vibrationResult['value'],
                            ]
                        );
                    }
                } else {
                    LaboratoryResults::query()->updateOrCreate(
                        [
                            'order_id' => $input['order_id'],
                            'item_id' => $rowItemId,
                            'chain_custody_id' => (int) $row['chain_custody_id'],
                            'result_axis' => null,
                        ],
                        [
                            'order_item_id' => $row['order_item_id'] ?? null,

                            'parameter_id' => $parameterId,
                            'matrix_id' => $matrixId,
                            'type_of_sample_id' => $typeOfSampleId,

                            'code_season' => $chainCustody->code_season,
                            'code_lab' => $chainCustody->code_lab,
                            'code_sample' => $chainCustody->code_sample,

                            'result' => $row['result'] ?? null,
                        ]
                    );
                }
            }

            DB::commit();

            return $this->sendSuccess('Resultados guardados con éxito');
        } catch (\Throwable $e) {
            DB::rollBack();

            return $this->sendError($e->getMessage());
        }
    }
}
