<?php

namespace App\Http\Controllers;

use App\Exports\InformDesignExport;
use App\Models\tenant\ChainCustody;
use App\Models\tenant\OrderService;
use App\Models\tenant\OtsGenerate;
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

                
            Log::info($parameters);
            Log::info($matrixIds);

            $chainCustody = ChainCustody::query()
                ->where('order_id', $orderId)
                ->whereIn('matrix_id', $matrixIds)
                ->get();

            Log::info($chainCustody);

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
