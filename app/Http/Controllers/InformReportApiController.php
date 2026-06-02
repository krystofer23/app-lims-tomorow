<?php

namespace App\Http\Controllers;

use App\Exports\InformDesignExport;
use App\Models\tenant\ChainCustody;
use App\Models\tenant\OrderService;
use App\Models\tenant\OtsGenerate;
use App\Models\tenant\TypeOfAnalysis;
use App\Models\tenant\LaboratoryResults;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class InformReportApiController extends Controller
{
    public function downloadDesign(int $orderId, Request $request)
    {
        try {
            $type = $request->input('type');

            $order = OrderService::with([
                'items',
                'company:id,ruc,business_name,direction',
                'application:id,ruc,business_name,direction'
            ])->findOrFail($orderId);

            $parameters = collect($order->items)
                ->filter(fn($row) => data_get($row, 'item.type') === $type)
                ->values();

            $matrixIds = $parameters
                ->map(function ($parameter) {
                    $item = data_get($parameter, 'item', []);

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
                ->whereIn('matrix_id', $matrixIds)
                ->get();

            $sampleQuantity = $chainCustody
                ->sum(fn($item) => (int) ($item->number_sample ?? 0));

            $samplingPerformedBy = $chainCustody
                ->pluck('company_sampling_id')
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
                        'code_lab' => $item->code_lab ?? '-',
                        'code_sample' => $item->code_sample ?? '-',
                        'date_sample' => $date ?? '-',
                        'hour_sample' => $hour ?? '-',

                        // OJO AQUÍ
                        'coordinate' => $item->coordinate ?? '-',
                    ];
                })
                ->toArray();

            // Ordenear por tipo de analisis => parametros
            // Tipo de ensayo - unidad - lcm - resultados (Lab)

            $typeAnalysisIds = $parameters
                ->map(function ($row) {
                    $item = data_get($row, 'item', []);

                    return data_get($item, 'parameter.type_of_analysis_id')
                        ?? data_get($item, 'type_of_analysis_id');
                })
                ->filter()
                ->unique()
                ->values()
                ->toArray();

            $typeAnalysisMap = TypeOfAnalysis::whereIn('id', $typeAnalysisIds)
                ->pluck('description', 'id')
                ->toArray();

            $laboratoryResults = LaboratoryResults::query()
                ->where('order_id', $orderId)
                ->get()
                ->keyBy(function ($row) {
                    return $row->item_id . '_' . $row->chain_custody_id;
                });

            $sampleIndexByChainCustodyId = $chainCustody
                ->values()
                ->pluck('id')
                ->flip();

            $analysisGroups = $parameters
                ->map(function ($row) use (
                    $typeAnalysisMap,
                    $laboratoryResults,
                    $chainCustody,
                    $sampleIndexByChainCustodyId
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
                            return (int) data_get($parameter, 'id') === (int) $realItemId;
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

            $mapData = [
                'company' => $order?->company?->business_name,
                'direction' => $order?->direction,
                'application' => $order?->application?->business_name,
                'reference' => $order->code . ' / COT N°' . $order->quote_id,
                'project' => $order?->project,
                'origin' => $order?->origin,
                'sampling_performed_by' => $samplingPerformedBy[0] ?? '-',
                'sample_quantity' => $sampleQuantity,
                'product' => $type,
                'sampling_plan' => 'PM N°',
                'date_of_receipt' => $dateOfReceipt,
                'time_of_receipt' => $timeOfReceipt,
                'test_period' => '-',
                'date_of_issue' => '-',

                'samples' => $samples,
                'category' => $type,

                'sampling_point_description' => '-',
                'analysis_groups' => $analysisGroups,

                'analysis_groups_methodology' => $analysisGroupsMethodology
            ];

            return Excel::download(
                new InformDesignExport($mapData),
                'formato-emisiones-diseno.xlsx'
            );
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    // public function storeDesign()
    // {
    //     Excel::store(
    //         new InformDesignExport(),
    //         'reportes/formato-emisiones-diseno.xlsx',
    //         'public'
    //     );

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Excel generado correctamente.',
    //         'path' => 'storage/reportes/formato-emisiones-diseno.xlsx',
    //     ]);
    // }
}
