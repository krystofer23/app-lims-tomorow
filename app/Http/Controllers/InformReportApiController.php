<?php

namespace App\Http\Controllers;

use App\Exports\InformDesignExport;
use App\Models\tenant\ChainCustody;
use App\Models\tenant\Conditions;
use App\Models\tenant\OrderService;
use App\Models\tenant\OtsGenerate;
use App\Models\tenant\TypeOfAnalysis;
use App\Models\tenant\LaboratoryResults;
use App\Models\tenant\ProceduresToParameter;
use App\Models\tenant\TrialPeriod;
use App\Models\tenatn\SelectToMetals;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class InformReportApiController extends Controller
{
    protected $legends;

    public function __construct()
    {
        $this->legends = [
            'AGUA' => [
                'legend' => "
                    Leyenda:
                    (z): Resolución cuantificable del equipo
                    L.C.M: Límite de cuantificación del Método
                    \"<\": Menor que el L.C.M. indicado
                    ¤: Información proporcionada en la cadena por el cliente
                    Las muestras recibidas cumplen con las condiciones necesarias para la realización de los análisis solicitados.

                ",
            ],
            'AIRE' => [
                'legend' => "
                    Leyenda:
                    (z): Resolución cuantificable del equipo
                    L.C.M: Límite de cuantificación del Método
                    \"<\": Menor que el L.C.M. indicado
                    ¤: Información proporcionada en la cadena por el cliente
                    ⁽ᵃ⁾: Tiempo de muestro 1 hora
                    ⁽ᵇ⁾: Tiempo de muestro 8 horas
                    ⁽ᶜ⁾: Tiempo de muestro 24 horas
                    Las muestras recibidas cumplen con las condiciones necesarias para la realización de los análisis solicitados.
                ",
            ],
            'SUELO' => [
                'legend' => "
                    Leyenda:
                    L.C.M: Límite de cuantificación del Método
                    \"<\": Menor que el L.C.M. indicado
                    Las muestras recibidas cumplen con las condiciones necesarias para la realización de los análisis solicitados.
                    ¤: Información proporcionada en la cadena por el cliente
                    Los parametros indicados estan expresados en  mg/Kg PS
                    PS: Peso seco
                ",
            ],
            'VIBRACION' => [
                'legend' => "",
            ],
            'SALUD OCUPACIONAL' => [
                'legend' => "
                    Leyenda:
                    L.C.M: Límite de cuantificación del Método
                    \"<\": Menor que el L.C.M. indicado
                    ¤: Información proporcionada en la cadena por el cliente
                    Las muestras recibidas cumplen con las condiciones necesarias para la realización de los análisis solicitados.
                ",
            ],
            'RUIDO' => [
                'legend' => "
                    Leyenda:
                    (z) Resolución cuantificable; dB(A): Decibelio A.
                    \"LAeqT\" = Nivel de Presión Acústica Continuo Equivalente Ponderado A, \"LAmín.\" = Nivel de Presión Sonora Mínimo, \"LAmáx.\" Nivel de Presión Sonora Máximo.
                    * Corresponde a la incertidumbre asociada al LAeqt
                    L.C.M Límite de cuantificación del Método
                ",
            ],
            'RNI' => [
                'legend' => "
                    (*): Información obtenida directamente del equipo de medición.
                    Leyenda:
                    µT = microTesla, A/m = amperio/metro, V/m = voltios/metro
                    L.C.M = Límite de cuantificación del método,  L.D.M: Límite de detección del método
                    \"<\" = Menor que el L.C.M o L.D.M indicado, \">\" = Mayor al rango lineal permitido por la técnica analítica
                ",
            ],
            'EMISIONES' => [
                'legend' => "
                    Leyenda:
                    L.C.M: Límite de cuantificación del Método
                    V/I: Valor indeterminado
                    (z): Resolución cuantificable del equipo
                    \"<\": Menor que el L.C.M. indicado
                    Condiciones Normales: Los resultados están expresados a 0 °C, 1013.25 mBar
                    Condiciones Estándar: Los resultados están expresados a 20 °C, 1013.25 mBar
                    Condiciones Estándar: Los resultados están expresados a 25 °C, 1013.25 mBar
                    ⁽ᵠ⁾: Corresponde al ángulo de desviación promedio del flujo de la chimenea evaluada.
                    ppm: Los resultados están expresados en 10-6 mol/mol
                ",
            ]
        ];
    }

    public function show($orderId): JsonResponse
    {
        try {
            $order = OrderService::with([
                'items',
                'company:id,business_name,ruc',
                'application:id,business_name,ruc',
            ])->findOrFail($orderId);

            $types = $order->items
                ->pluck('item.type')
                ->filter()
                ->unique()
                ->values();

            $items = $types->map(function ($type) use ($order) {
                $itemsByType = $order->items->where('item.type', $type);

                return [
                    'type' => $type,
                    'inacal' => $itemsByType->where('item.condition_id', 1)->isNotEmpty(),
                    'ias' => $itemsByType->where('item.condition_id', 2)->isNotEmpty(),
                ];
            })->values();

            $mapData = [
                'id' => $order->id,
                'company_id' => $order->company_id,
                'application_id' => $order->application_id,
                'department' => $order->department,
                'district' => $order->district,
                'province' => $order->province,
                'code' => $order->code,
                'company' => $order->company,
                'application' => $order->application,
                'types' => $types,
                'items' => $items,
            ];

            return $this->sendResponse($mapData, 'Enviando datos de la orden');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function downloadDesignPdf(int $orderId, Request $request)
    {
        try {
            $mapData = $this->buildInformDesignData($orderId, $request);

            $pdf = Pdf::loadView('pdf.inform-design', [
                'data' => $mapData,
            ])
                ->setPaper('a4', 'portrait')
                ->setOption('isRemoteEnabled', true);

            return $pdf->stream('formato-emisiones-diseno.pdf');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function downloadDesignExcel(int $orderId, Request $request)
    {
        try {
            $mapData = $this->buildInformDesignData($orderId, $request);

            return Excel::download(
                new InformDesignExport($mapData),
                'formato-emisiones-diseno.xlsx'
            );
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    private function buildInformDesignData(int $orderId, Request $request)
    {
        $type = $request->input('type');
        $condition = $request->input('condition');

        $order = OrderService::with([
            'items',
            'company:id,ruc,business_name,direction',
            'application:id,ruc,business_name,direction'
        ])->findOrFail($orderId);

        $normalizeItem = function ($item): array {
            if (is_string($item)) {
                $item = json_decode($item, true) ?: [];
            }

            return is_array($item) ? $item : [];
        };

        /*
     * Primero filtramos los items originales de la orden.
     */
        $baseParameters = collect($order->items)
            ->filter(function ($row) use ($type, $normalizeItem) {
                $item = $normalizeItem(data_get($row, 'item', []));

                return data_get($item, 'type') === $type;
            })
            ->filter(function ($row) use ($condition, $normalizeItem) {
                $item = $normalizeItem(data_get($row, 'item', []));

                return data_get($item, 'condition.description') === $condition;
            })
            ->values();

        /*
     * Metales seleccionados por orden.
     *
     * to_metal_id  = parámetro padre, ejemplo: Metales
     * parameter_id = parámetro hijo, ejemplo: Boro, Zinc, Vanadio
     */
        $selectedMetalsByToMetalId = SelectToMetals::query()
            ->where('order_id', $orderId)
            ->get()
            ->groupBy(function ($row) {
                return (int) $row->to_metal_id;
            });

        /*
     * Guardamos los items padres de metal.
     *
     * Esto sirve para que en II. MÉTODOS Y REFERENCIAS,
     * cuando el parámetro sea un metal hijo, se use el padre to_metal.
     */
        $metalParentItemsByToMetalId = $baseParameters
            ->mapWithKeys(function ($row) use ($normalizeItem, $selectedMetalsByToMetalId) {
                $item = $normalizeItem(data_get($row, 'item', []));

                $parameterId = data_get($item, 'parameter_id')
                    ?? data_get($item, 'parameter.id');

                $parameterId = $parameterId !== null ? (int) $parameterId : null;

                if (!$parameterId || !$selectedMetalsByToMetalId->has($parameterId)) {
                    return [];
                }

                return [
                    $parameterId => $item,
                ];
            });

        /*
     * Para resultados:
     *
     * Si el parámetro NO tiene hijos metálicos, se mantiene normal.
     * Si el parámetro SÍ tiene hijos metálicos, NO se muestra el padre;
     * se muestran solo sus hijos.
     */
        $parameters = $baseParameters
            ->flatMap(function ($row) use ($selectedMetalsByToMetalId, $normalizeItem) {
                $item = $normalizeItem(data_get($row, 'item', []));

                $realItemId = data_get($row, 'item_id')
                    ?? data_get($item, 'id');

                $realItemId = $realItemId !== null ? (int) $realItemId : null;

                $parameterId = data_get($item, 'parameter_id')
                    ?? data_get($item, 'parameter.id');

                $parameterId = $parameterId !== null ? (int) $parameterId : null;

                /*
             * Caso normal: no tiene metales hijos.
             */
                if (!$parameterId || !$selectedMetalsByToMetalId->has($parameterId)) {
                    return collect([
                        [
                            'id' => data_get($row, 'id'),
                            'item_id' => $realItemId,
                            'order_item_id' => $realItemId,
                            'item' => $item,

                            'is_metal_child' => false,
                            'select_to_metal_id' => null,
                            'to_metal_id' => null,
                            'parent_item_id' => null,
                            'parent_parameter_id' => null,
                        ]
                    ]);
                }

                /*
             * Caso metal:
             * No devolvemos el padre para resultados.
             * Devolvemos solo los hijos de select_to_metals.
             */
                return $selectedMetalsByToMetalId
                    ->get($parameterId)
                    ->map(function ($metal) use ($realItemId, $parameterId, $normalizeItem) {
                        $metalItem = $normalizeItem($metal->item ?? []);

                        return [
                            'id' => null,

                            /*
                         * Para resultados de laboratorio:
                         * item_id será el parameter_id del metal hijo.
                         *
                         * Ejemplo:
                         * Boro    => 45
                         * Vanadio => 118
                         */
                            'item_id' => (int) $metal->parameter_id,

                            /*
                         * Item padre original de la orden.
                         */
                            'order_item_id' => $realItemId,

                            /*
                         * JSON completo del hijo.
                         */
                            'item' => $metalItem,

                            'is_metal_child' => true,
                            'select_to_metal_id' => (int) $metal->id,
                            'to_metal_id' => (int) $metal->to_metal_id,
                            'parent_item_id' => $realItemId,
                            'parent_parameter_id' => $parameterId,
                        ];
                    })
                    ->values();
            })
            ->values();

        $typeOfSampleId = $parameters
            ->map(function ($row) use ($normalizeItem) {
                $item = $normalizeItem(data_get($row, 'item', []));

                return data_get($item, 'type_of_sample_id')
                    ?? data_get($item, 'type_of_sample_filter')
                    ?? data_get($item, 'parameter.connections_parameter.0.type_of_samples_id')
                    ?? data_get($item, 'parameter.connections_parameter.0.type_of_sample_id')
                    ?? data_get($item, 'parameter.connections_parameter.0.type_of_sample.id');
            })
            ->filter()
            ->unique()
            ->first();

        $matrixIds = $parameters
            ->map(function ($parameter) use ($normalizeItem) {
                $item = $normalizeItem(data_get($parameter, 'item', []));

                return data_get($item, 'matrix_id')
                    ?? data_get($item, 'matrix_filter')
                    ?? data_get($item, 'parameter.connections_parameter.0.matrix_id')
                    ?? data_get($item, 'parameter.connections_parameter.0.matrix.id');
            })
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        $chainCustody = ChainCustody::query()
            ->where('order_id', $orderId)
            ->when(!empty($matrixIds), function ($query) use ($matrixIds) {
                $query->whereIn('matrix_id', $matrixIds);
            })
            ->get();

        $firstChainCustody = $chainCustody->first();

        $sampleQuantity = $chainCustody
            ->sum(fn($item) => (int) ($item->number_sample ?? 0));

        $samplingPerformedBy = $chainCustody
            ->pluck('company_sampling_id')
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        /*
     * IDs reales de parámetros para resultados/procedimientos.
     * En metales serán los hijos: Boro, Zinc, Vanadio, etc.
     */
        $parameterIds = $parameters
            ->map(function ($row) use ($normalizeItem) {
                $item = $normalizeItem(data_get($row, 'item', []));

                return data_get($item, 'parameter_id')
                    ?? data_get($item, 'parameter.id')
                    ?? data_get($row, 'item_id');
            })
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        $dateAgreed = $chainCustody
            ->filter(function ($chain) use ($parameterIds) {
                $parametersJson = $chain->parameters ?? [];

                if (is_string($parametersJson)) {
                    $parametersJson = json_decode($parametersJson, true) ?: [];
                }

                if (!is_array($parametersJson)) {
                    $parametersJson = [];
                }

                $chainParameterIds = collect($parametersJson)
                    ->map(function ($parameter) {
                        return data_get($parameter, 'parameter_id')
                            ?? data_get($parameter, 'parameter.id')
                            ?? data_get($parameter, 'id')
                            ?? data_get($parameter, 'item_id');
                    })
                    ->filter()
                    ->toArray();

                return !empty(array_intersect($chainParameterIds, $parameterIds));
            })
            ->pluck('date_agreed')
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        $dateReception = $chainCustody
            ->pluck('date_reception')
            ->filter()
            ->unique()
            ->map(function ($date) {
                $carbon = Carbon::parse($date);

                return [
                    'datetime' => $date,
                    'date' => $carbon->format('Y-m-d'),
                    'hour' => $carbon->format('H:i'),
                ];
            })
            ->values()
            ->toArray();

        $dateOfReceipt = $dateReception[0]['date'] ?? '-';
        $timeOfReceipt = $dateReception[0]['hour'] ?? '-';

        $samples = $chainCustody
            ->values()
            ->map(function ($item) {
                $date = null;
                $hour = null;

                if (!empty($item->date_reception)) {
                    $carbon = Carbon::parse($item->date_reception);
                    $date = $carbon->format('Y-m-d');
                    $hour = $carbon->format('H:i');
                }

                $dateSamplingInit = null;
                $hourSamplingInit = null;

                if (!empty($item->date_sampling_init)) {
                    $carbonInit = Carbon::parse($item->date_sampling_init);
                    $dateSamplingInit = $carbonInit->format('Y-m-d');
                    $hourSamplingInit = $carbonInit->format('H:i');
                }

                $dateSamplingEnd = null;
                $hourSamplingEnd = null;

                if (!empty($item->date_sampling_end)) {
                    $carbonEnd = Carbon::parse($item->date_sampling_end);
                    $dateSamplingEnd = $carbonEnd->format('Y-m-d');
                    $hourSamplingEnd = $carbonEnd->format('H:i');
                }

                return [
                    'id' => $item->id,
                    'code_lab' => $item->code_lab ?? '-',
                    'code_sample' => $item->code_sample ?? '-',

                    'date_sample' => $date ?? '-',
                    'hour_sample' => $hour ?? '-',

                    'date_sampling_init' => $dateSamplingInit ?? '-',
                    'hour_sampling_init' => $hourSamplingInit ?? '-',
                    'date_sampling_end' => $dateSamplingEnd ?? '-',
                    'hour_sampling_end' => $hourSamplingEnd ?? '-',

                    'coordinate' => $item->coordinate ?? '-',
                ];
            })
            ->toArray();

        /*
     * TypeAnalysis para hijos normales/metálicos.
     */
        $typeAnalysisIdsFromParameters = $parameters
            ->map(function ($row) use ($normalizeItem) {
                $item = $normalizeItem(data_get($row, 'item', []));

                return data_get($item, 'parameter.type_of_analysis_id')
                    ?? data_get($item, 'type_of_analysis_id');
            });

        /*
     * TypeAnalysis de padres metálicos.
     * Necesario para II. MÉTODOS Y REFERENCIAS.
     */
        $typeAnalysisIdsFromMetalParents = $metalParentItemsByToMetalId
            ->map(function ($item) {
                return data_get($item, 'parameter.type_of_analysis_id')
                    ?? data_get($item, 'type_of_analysis_id');
            });

        $typeAnalysisIds = $typeAnalysisIdsFromParameters
            ->merge($typeAnalysisIdsFromMetalParents)
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        $typeAnalysisMap = !empty($typeAnalysisIds)
            ? TypeOfAnalysis::query()
            ->whereIn('id', $typeAnalysisIds)
            ->pluck('description', 'id')
            ->toArray()
            : [];

        $isVibration = strtoupper((string) $type) === 'VIBRACION';
        $isRni = strtoupper((string) $type) === 'RNI';

        $rniResults = LaboratoryRniResult::query()
            ->where('order_id', $orderId)
            ->whereNull('deleted_at')
            ->orderBy('chain_custody_id')
            ->orderByRaw("FIELD(measurement_period, 'PUNTA', 'NO_PUNTA')")
            ->get()
            ->map(function ($row) {
                return [
                    'chain_custody_id' => $row->chain_custody_id,
                    'measurement_period' => $row->measurement_period,

                    'date_monitoring' => optional($row->date_monitoring)->format('Y-m-d'),
                    'hour_sampling' => $row->hour_sampling,
                    'humidity_relative' => $row->humidity_relative,
                    'ambient_temperature' => $row->ambient_temperature,
                    'electric_system_type' => $row->electric_system_type,

                    'instrument' => $row->instrument,
                    'brand' => $row->brand,
                    'model' => $row->model,
                    'serial_number' => $row->serial_number,
                    'probe_range' => $row->probe_range,
                    'calibration_date' => optional($row->calibration_date)->format('Y-m-d'),
                    'certificate_number' => $row->certificate_number,

                    'station_description' => $row->station_description,
                    'soil_coverage' => $row->soil_coverage,
                    'climate_conditions' => $row->climate_conditions,

                    'measurements' => $row->measurements ?? [],
                    'summary' => $row->summary ?? [],
                    'chain_custody' => [
                        'code_lab' => $row->chainCustody?->code_lab,
                        'code_season' => $row->chainCustody?->code_season,
                        'coordinate' => $row->chainCustody?->coordinate,
                    ],
                ];
            })
            ->toArray();

        $laboratoryResults = LaboratoryResults::query()
            ->where('order_id', $orderId)
            ->whereNull('deleted_at')
            ->get()
            ->keyBy(function ($row) {
                $baseKey = $row->item_id . '_' . $row->chain_custody_id;

                if ($row->result_axis && $row->result_type) {
                    return $baseKey . '_' . strtoupper($row->result_axis) . '_' . strtoupper($row->result_type);
                }

                return $baseKey;
            });

        /*
     * I. RESULTADOS:
     * Aquí sí se muestran los hijos metálicos.
     */
        if ($isVibration) {
            $firstParameterRow = $parameters->first();
            $firstItem = $normalizeItem(data_get($firstParameterRow, 'item', []));

            $realItemId = data_get($firstParameterRow, 'item_id')
                ?? data_get($firstItem, 'id');

            $realItemId = $realItemId !== null ? (int) $realItemId : null;

            $unitRaw = data_get($firstItem, 'parameter.unit_measurement.description')
                ?? data_get($firstItem, 'unit_measurement.description')
                ?? data_get($firstItem, 'unit')
                ?? '';

            $unitParts = collect(preg_split('/\r\n|\r|\n/', (string) $unitRaw))
                ->map(fn($value) => trim($value))
                ->filter()
                ->values();

            $ppvUnit = $unitParts->get(0, 'mm/s');
            $frecUnit = $unitParts->get(1, 'Hz');

            $lcmRaw = data_get($firstItem, 'parameter.lcm')
                ?? data_get($firstItem, 'lcm')
                ?? '';

            $lcmParts = collect(preg_split('/\r\n|\r|\n/', (string) $lcmRaw))
                ->map(fn($value) => trim($value))
                ->filter()
                ->values();

            $ppvLcm = $lcmParts->get(0, '0,027(z)');
            $frecLcm = $lcmParts->get(1, '-');

            $buildVibrationResults = function (string $axis, string $resultType) use (
                $chainCustody,
                $laboratoryResults,
                $realItemId
            ) {
                return $chainCustody
                    ->values()
                    ->map(function ($custody) use (
                        $laboratoryResults,
                        $realItemId,
                        $axis,
                        $resultType
                    ) {
                        $key = $realItemId . '_' . $custody->id . '_' . strtoupper($axis) . '_' . strtoupper($resultType);

                        $savedResult = $laboratoryResults->get($key);

                        return [
                            'chain_custody_id' => $custody->id,
                            'code_lab' => $custody->code_lab,
                            'code_sample' => $custody->code_sample,
                            'code_season' => $custody->code_season,
                            'coordinate' => $custody->coordinate,
                            'result' => $savedResult?->result ?? '',
                        ];
                    })
                    ->toArray();
            };

            $analysisGroups = [
                [
                    'type_of_analysis' => 'Análisis de Campo',
                    'parameters' => [
                        [
                            'parameter' => '- PPV max',
                            'unit' => '',
                            'lcm' => '',
                            'results' => [],
                            'is_vibration_section' => true,
                        ],
                        [
                            'parameter' => '- Eje X',
                            'unit' => $ppvUnit,
                            'lcm' => $ppvLcm,
                            'results' => $buildVibrationResults('X', 'PPV'),
                            'is_vibration_axis' => true,
                        ],
                        [
                            'parameter' => '- Eje Y',
                            'unit' => $ppvUnit,
                            'lcm' => $ppvLcm,
                            'results' => $buildVibrationResults('Y', 'PPV'),
                            'is_vibration_axis' => true,
                        ],
                        [
                            'parameter' => '- Eje Z',
                            'unit' => $ppvUnit,
                            'lcm' => $ppvLcm,
                            'results' => $buildVibrationResults('Z', 'PPV'),
                            'is_vibration_axis' => true,
                        ],

                        [
                            'parameter' => '- Nivel de Frecuencia',
                            'unit' => '',
                            'lcm' => '',
                            'results' => [],
                            'is_vibration_section' => true,
                        ],
                        [
                            'parameter' => '- Eje X',
                            'unit' => $frecUnit,
                            'lcm' => $frecLcm,
                            'results' => $buildVibrationResults('X', 'FREC'),
                            'is_vibration_axis' => true,
                        ],
                        [
                            'parameter' => '- Eje Y',
                            'unit' => $frecUnit,
                            'lcm' => $frecLcm,
                            'results' => $buildVibrationResults('Y', 'FREC'),
                            'is_vibration_axis' => true,
                        ],
                        [
                            'parameter' => '- Eje Z',
                            'unit' => $frecUnit,
                            'lcm' => $frecLcm,
                            'results' => $buildVibrationResults('Z', 'FREC'),
                            'is_vibration_axis' => true,
                        ],
                    ],
                ],
            ];
        } else {
            $analysisGroups = $parameters
                ->map(function ($row) use (
                    $typeAnalysisMap,
                    $laboratoryResults,
                    $chainCustody,
                    $normalizeItem
                ) {
                    $item = $normalizeItem(data_get($row, 'item', []));

                    $realItemId = data_get($row, 'item_id')
                        ?? data_get($item, 'id');

                    $realItemId = $realItemId !== null ? (int) $realItemId : null;

                    $parameterId = data_get($item, 'parameter_id')
                        ?? data_get($item, 'parameter.id')
                        ?? $realItemId;

                    $parameterId = $parameterId !== null ? (int) $parameterId : null;

                    $typeAnalysisId = data_get($item, 'parameter.type_of_analysis_id')
                        ?? data_get($item, 'type_of_analysis_id');

                    $results = [];

                    foreach ($chainCustody as $custody) {
                        $parametersJson = $custody->parameters ?? [];

                        if (is_string($parametersJson)) {
                            $parametersJson = json_decode($parametersJson, true) ?: [];
                        }

                        if (!is_array($parametersJson)) {
                            $parametersJson = [];
                        }

                        $existsInCustody = collect($parametersJson)->contains(function ($parameter) use ($realItemId, $parameterId) {
                            $chainItemId = data_get($parameter, 'id')
                                ?? data_get($parameter, 'item_id');

                            $chainParameterId = data_get($parameter, 'parameter_id')
                                ?? data_get($parameter, 'parameter.id');

                            if ($parameterId && $chainParameterId && (int) $chainParameterId === (int) $parameterId) {
                                return true;
                            }

                            if ($realItemId && $chainItemId && (int) $chainItemId === (int) $realItemId) {
                                return true;
                            }

                            if ($realItemId && $chainParameterId && (int) $chainParameterId === (int) $realItemId) {
                                return true;
                            }

                            return false;
                        });

                        if (!$existsInCustody) {
                            continue;
                        }

                        $key = $realItemId . '_' . $custody->id;

                        $savedResult = $laboratoryResults->get($key);

                        $results[] = [
                            'chain_custody_id' => $custody->id,
                            'code_lab' => $custody->code_lab,
                            'code_sample' => $custody->code_sample,
                            'code_season' => $custody->code_season,
                            'coordinate' => $custody->coordinate,
                            'result' => $savedResult?->result ?? '',
                        ];
                    }

                    return [
                        'type_of_analysis' =>
                        data_get($item, 'parameter.type_of_analysis.description')
                            ?? data_get($item, 'type_of_analysis.description')
                            ?? ($typeAnalysisMap[$typeAnalysisId] ?? 'SIN TIPO DE ENSAYO'),

                        'parameter' =>
                        data_get($item, 'parameter.description')
                            ?? data_get($item, 'description')
                            ?? data_get($item, 'name')
                            ?? '-',

                        'unit' =>
                        data_get($item, 'parameter.unit_measurement.description')
                            ?? data_get($item, 'unit_measurement.description')
                            ?? data_get($item, 'unit')
                            ?? '-',

                        'lcm' =>
                        data_get($item, 'parameter.lcm')
                            ?? data_get($item, 'lcm')
                            ?? '-',

                        'results' => $results,
                    ];
                })
                ->groupBy('type_of_analysis')
                ->map(function ($items, $typeOfAnalysis) {
                    return [
                        'type_of_analysis' => $typeOfAnalysis,
                        'parameters' => $items->map(function ($item) {
                            return [
                                'parameter' => $item['parameter'],
                                'unit' => $item['unit'],
                                'lcm' => $item['lcm'],
                                'results' => $item['results'] ?? [],
                            ];
                        })->values()->toArray(),
                    ];
                })
                ->values()
                ->toArray();
        }

        /*
     * II. MÉTODOS Y REFERENCIAS:
     *
     * Aquí está el cambio importante:
     * - Si es parámetro normal, usa su propio item.
     * - Si es metal hijo, usa el padre to_metal.
     */
        $analysisGroupsMethodology = $parameters
            ->map(function ($row) use (
                $typeAnalysisMap,
                $normalizeItem,
                $metalParentItemsByToMetalId
            ) {
                $item = $normalizeItem(data_get($row, 'item', []));

                $isMetalChild = (bool) data_get($row, 'is_metal_child', false);

                if ($isMetalChild) {
                    $toMetalId = data_get($row, 'to_metal_id')
                        ?? data_get($row, 'parent_parameter_id');

                    $toMetalId = $toMetalId !== null ? (int) $toMetalId : null;

                    /*
                 * Para metal hijo, usamos el item padre.
                 */
                    $methodItem = $toMetalId
                        ? ($metalParentItemsByToMetalId->get($toMetalId) ?? $item)
                        : $item;

                    /*
                 * Esta key permite que todos los hijos metálicos
                 * se agrupen como una sola fila del padre.
                 */
                    $methodParameterId = $toMetalId;
                } else {
                    $methodItem = $item;

                    $methodParameterId = data_get($methodItem, 'parameter_id')
                        ?? data_get($methodItem, 'parameter.id');

                    $methodParameterId = $methodParameterId !== null ? (int) $methodParameterId : null;
                }

                $typeAnalysisId = data_get($methodItem, 'parameter.type_of_analysis_id')
                    ?? data_get($methodItem, 'type_of_analysis_id');

                $parameterName = data_get($methodItem, 'parameter.description')
                    ?? data_get($methodItem, 'description')
                    ?? data_get($methodItem, 'name')
                    ?? '-';

                $code = data_get($methodItem, 'parameter.reference.code')
                    ?? data_get($methodItem, 'reference.code')
                    ?? '-';

                $title = data_get($methodItem, 'parameter.reference.title')
                    ?? data_get($methodItem, 'reference.title')
                    ?? '-';

                return [
                    '_method_key' => ($methodParameterId ?? $parameterName) . '|' . $code . '|' . $title,

                    'type_of_analysis' =>
                    data_get($methodItem, 'parameter.type_of_analysis.description')
                        ?? data_get($methodItem, 'type_of_analysis.description')
                        ?? ($typeAnalysisMap[$typeAnalysisId] ?? 'SIN TIPO DE ENSAYO'),

                    /*
                 * Si era metal hijo, aquí saldrá el padre to_metal.
                 */
                    'parameter' => $parameterName,

                    /*
                 * Código y título también salen del padre.
                 */
                    'code' => $code,
                    'title' => $title,
                ];
            })
            ->unique('_method_key')
            ->groupBy('type_of_analysis')
            ->map(function ($items, $typeOfAnalysis) {
                return [
                    'type_of_analysis' => $typeOfAnalysis,
                    'parameters' => $items->map(function ($item) {
                        return [
                            'parameter' => $item['parameter'],
                            'code' => $item['code'],
                            'title' => $item['title'],
                        ];
                    })->values()->toArray(),
                ];
            })
            ->values()
            ->toArray();

        $analysisGroupsProcedures = !empty($parameterIds)
            ? ProceduresToParameter::with('procedure')
            ->whereIn('parameter_id', $parameterIds)
            ->get()
            ->map(function ($procedure) {
                return [
                    'procedure' => $procedure?->procedure?->description,
                ];
            })
            ->filter(fn($row) => !empty($row['procedure']))
            ->unique('procedure')
            ->values()
            ->toArray()
            : [];

        $legend = $this->getLegend($type, $samplingPerformedBy);

        $code = $order?->code ?? '';

        $cleanCode = str_starts_with($code, 'OS-')
            ? substr($code, 3)
            : $code;

        $conditionId = Conditions::query()
            ->where('description', 'like', "%{$condition}%")
            ->value('id');

        $trialPeriod = null;

        if ($orderId && $conditionId && $typeOfSampleId) {
            $trialPeriod = TrialPeriod::query()
                ->where('order_id', $orderId)
                ->where('condition_id', $conditionId)
                ->where('type_of_sample_id', $typeOfSampleId)
                ->first();
        }

        $mapData = [
            'is_vibration' => $isVibration,
            'report_number' => $firstChainCustody?->number_report ?? 'XXX-XX-I',

            'company' => $order?->company?->business_name,
            'direction' => $order?->direction ?? $order?->company?->direction,
            'application' => $order?->application?->business_name,
            'reference' => ($order?->code ?? '-') . ' / Cotización N° ' . ($order?->quote_id ?? '-'),
            'project' => $order?->project,
            'origin' => $order?->origin,

            'sampling_performed_by' => $samplingPerformedBy[0] ?? '-',
            'sample_quantity' => $sampleQuantity,
            'product' => $type,

            'sampling_plan' => ($samplingPerformedBy[0] ?? null) === 'GREENLAB PERÚ S.A.C.'
                ? 'PM N° ' . $cleanCode
                : 'NO APLICA',

            'date_of_receipt' => $dateOfReceipt,
            'time_of_receipt' => $timeOfReceipt,
            'test_period' => '-',
            'date_of_issue' => '-',

            'samples' => $samples,
            'category' => $type,
            'sub_category' => $type,

            'sampling_point_description' => '-',
            'analysis_groups' => $analysisGroups,
            'analysis_groups_methodology' => $analysisGroupsMethodology,

            'legend' => $legend,

            'procedures' => $analysisGroupsProcedures,
            'condition' => $condition,

            'date_agreed' => $dateAgreed[0] ?? '-',

            'trial_period' => ($trialPeriod?->date_init && $trialPeriod?->date_end)
                ? sprintf(
                    'DEL %s AL %s',
                    Carbon::parse($trialPeriod->date_init)->format('Y-m-d'),
                    Carbon::parse($trialPeriod->date_end)->format('Y-m-d')
                )
                : null,

            'is_rni' => $isRni,
            'rni_results' => $rniResults,
        ];

        return $mapData;
    }

    private function getLegend(string $type, array $samplingPerformedBy): string
    {
        $legend = $this->legends[$type]['legend'] ?? '';

        $isGreenlab = ($samplingPerformedBy[0] ?? null) === 'GREENLAB PERÚ S.A.C.';

        if ($isGreenlab) {
            $legend = preg_replace('/^\s*¤:.*\R?/m', '', $legend);
        }

        return $legend;
    }
};
