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

        $parameters = collect($order->items)
            ->filter(fn($row) => data_get($row, 'item.type') === $type)
            ->filter(fn($row) => data_get($row, 'item.condition.description') === $condition)
            ->values();

        $typeOfSampleId = $parameters
            ->map(
                fn($row) =>
                data_get($row, 'item.type_of_sample_id')
                    ?? data_get($row, 'item.parameter.connections_parameter.0.type_of_samples_id')
                    ?? data_get($row, 'item.parameter.connections_parameter.0.type_of_sample_id')
            )
            ->filter()
            ->unique()
            ->first();

        $matrixIds = $parameters
            ->map(function ($parameter) {
                $item = data_get($parameter, 'item', []);

                if (is_string($item)) {
                    $item = json_decode($item, true) ?: [];
                }

                if (!empty($item['matrix_id'])) {
                    return $item['matrix_id'];
                }

                return data_get($item, 'parameter.connections_parameter.0.matrix_id');
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

        $parameterIds = $parameters
            ->pluck('item.parameter.id')
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
                    ->map(
                        fn($parameter) =>
                        data_get($parameter, 'parameter.id')
                            ?? data_get($parameter, 'id')
                    )
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

                return [
                    'id' => $item->id,
                    'code_lab' => $item->code_lab ?? '-',
                    'code_sample' => $item->code_sample ?? '-',
                    'date_sample' => $date ?? '-',
                    'hour_sample' => $hour ?? '-',
                    'coordinate' => $item->coordinate ?? '-',
                ];
            })
            ->toArray();

        $typeAnalysisIds = $parameters
            ->map(function ($row) {
                $item = data_get($row, 'item', []);

                if (is_string($item)) {
                    $item = json_decode($item, true) ?: [];
                }

                return data_get($item, 'parameter.type_of_analysis_id')
                    ?? data_get($item, 'type_of_analysis_id');
            })
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        $typeAnalysisMap = TypeOfAnalysis::query()
            ->whereIn('id', $typeAnalysisIds)
            ->pluck('description', 'id')
            ->toArray();

        $laboratoryResults = LaboratoryResults::query()
            ->where('order_id', $orderId)
            ->get()
            ->keyBy(function ($row) {
                return $row->item_id . '_' . $row->chain_custody_id;
            });

        $analysisGroups = $parameters
            ->map(function ($row) use (
                $typeAnalysisMap,
                $laboratoryResults,
                $chainCustody,
            ) {
                $item = data_get($row, 'item', []);

                if (is_string($item)) {
                    $item = json_decode($item, true) ?: [];
                }

                $realItemId = data_get($row, 'item_id')
                    ?? data_get($item, 'id');

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

                    $existsInCustody = collect($parametersJson)->contains(function ($parameter) use ($realItemId) {
                        return (int) data_get($parameter, 'id') === (int) $realItemId
                            || (int) data_get($parameter, 'item_id') === (int) $realItemId
                            || (int) data_get($parameter, 'parameter.id') === (int) $realItemId;
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

        $analysisGroupsMethodology = $parameters
            ->map(function ($row) use ($typeAnalysisMap) {
                $item = data_get($row, 'item', []);

                if (is_string($item)) {
                    $item = json_decode($item, true) ?: [];
                }

                $typeAnalysisId = data_get($item, 'parameter.type_of_analysis_id')
                    ?? data_get($item, 'type_of_analysis_id');

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

                    'code' =>
                    data_get($item, 'parameter.reference.code')
                        ?? data_get($item, 'reference.code')
                        ?? '-',

                    'title' =>
                    data_get($item, 'parameter.reference.title')
                        ?? data_get($item, 'reference.title')
                        ?? '-',
                ];
            })
            ->unique(function ($item) {
                return $item['parameter'] . '|' . $item['code'] . '|' . $item['title'];
            })
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

        $analysisGroupsProcedures = ProceduresToParameter::with('procedure')
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
            ->toArray();

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
