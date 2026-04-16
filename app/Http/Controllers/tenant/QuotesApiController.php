<?php

namespace App\Http\Controllers\tenant;

use App\Exports\QuoteExport;
use App\Http\Controllers\Controller;
use App\Models\tenant\ItemsQuotes;
use App\Models\tenant\LogisticCats;
use App\Models\tenant\Matriz;
use App\Models\tenant\Quotes;
use App\Models\tenant\Services;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class QuotesApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $data = Quotes::query()
                ->with([
                    'company',
                    'user',
                    'validator',
                    'itemsQuotes',
                    'contact.user',
                    'orderService'
                ])
                ->paginate(15);

            return $this->sendResponse($data, 'Enviandos cotizaciones');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function show(int $id, Request $request): JsonResponse
    {
        try {
            $quote = Quotes::with('itemsQuotes', 'orderService')->find($id);

            if (! $quote) {
                return $this->sendError('Cotización no encontrada');
            }

            $insets = $request?->is_order_service ? ['matriz'] : ['matriz', 'service'];

            $items = $quote->itemsQuotes
                ->whereIn('type', $insets)
                ->map(function ($item) {
                    return [
                        'id' => $item->item['id'],
                        'type' => $item->type,
                        'item' => $item->item,
                    ];
                })
                ->values();

            $otherExpenses = $quote->itemsQuotes
                ->where('type', 'other_expense')
                ->map(function ($item) {
                    return $item->item;
                })
                ->values();

            $mapData = [
                'id' => $quote->id,
                'company_id' => $quote->company_id,
                'direction' => $quote->direction,
                'date_attention' => $quote->date_attention,
                'version' => $quote->version,
                'code' => $quote->code,
                'items_total' => $quote->items_total,
                'other_expenses_total' => $quote->other_expenses_total,
                'igv' => $quote->igv,
                'subtotal' => $quote->subtotal,
                'total' => $quote->total,
                'reference' => $quote->reference,
                'observations' => $quote->observations,
                'contact_id' => $quote->contact_id,
                'items' => $items,
                'other_expenses' => $otherExpenses,
                'is_os' => $quote?->orderService ? true : false,
                'order_service' => $quote?->orderService
            ];

            return $this->sendResponse($mapData, 'Enviando cotización');
        } catch (Exception $e) {
            return $this->sendError(sprintf(
                'El error está en %s línea %d. Detalles: %s',
                $e->getFile(),
                $e->getLine(),
                $e->getMessage()
            ));
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $userId = Auth::guard('api')->id();

            if (!$userId) {
                return $this->sendError('No hay un usuario autenticado');
            }

            $input = $request->all();

            DB::beginTransaction();

            $quote = Quotes::create([
                'company_id' => $input['company_id'] ?? null,
                'user_id' => $userId,
                'direction' => $input['direction'] ?? null,
                'date_attention' => $input['date_attention'] ?? null,
                'reference' => $input['reference'] ?? null,
                'observations' => $input['observations'] ?? null,
                'items_total' => 0,
                'other_expenses_total' => 0,
                'igv' => 0,
                'subtotal' => 0,
                'total' => 0,
                'contact_id' => $input['contact_id'] ?? null
            ]);

            if (!empty($input['items']) && is_array($input['items'])) {
                foreach ($input['items'] as $item) {
                    $type = $item['type'];
                    $value = $item['item'];

                    $model = match ($type) {
                        'service' => Services::class,
                        'matriz' => Matriz::class,
                        default => null,
                    };

                    $amount = $value['amount'] ?? $value['number_samples'] ?? 0;

                    ItemsQuotes::create([
                        'quote_id' => $quote->id,
                        'type' => $type,
                        'filable_type' => $model,
                        'filable_id' => $value['id'],
                        'item' => $value,
                        'amount' => $amount,
                        'price_unit' => $value['unit_price'],
                        'total' => $value['price'],
                    ]);
                }
            }

            if (!empty($input['other_expenses']) && is_array($input['other_expenses'])) {
                foreach ($input['other_expenses'] as $item) {
                    $amount = $item['amount'] ?? 0;
                    $unitPrice = $item['unit_price'] ?? 0;
                    $lineTotal = $item['price'] ?? ($amount * $unitPrice);

                    ItemsQuotes::create([
                        'quote_id' => $quote->id,
                        'type' => $item['type'] ?? 'other_expense',
                        'filable_type' => LogisticCats::class,
                        'filable_id' => $item['id'],
                        'item' => $item,
                        'amount' => $amount,
                        'price_unit' => $item['unit_price'],
                        'total' => $item['price'],
                    ]);
                }
            }

            $quote->update([
                'items_total' => $input['subtotal'],
                'other_expenses_total' => $input['other_expenses_total'],
                'subtotal' => $input['subtotal'],
                'igv' => $input['igv'],
                'total' => $input['total'],
            ]);

            DB::commit();

            return $this->sendSuccess('Cotización creada con éxito');
        } catch (Exception $e) {
            DB::rollBack();

            return $this->sendError(sprintf(
                'El error está en %s línea %d. Detalles: %s',
                $e->getFile(),
                $e->getLine(),
                $e->getMessage()
            ));
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $userId = Auth::guard('api')->id();

            if (!$userId) {
                return $this->sendError('No hay un usuario autenticado');
            }

            $quote = Quotes::find($id);

            if (!$quote) {
                DB::rollBack();
                return $this->sendError('Cotización no encontrada');
            }

            $input = $request->all();

            $quote->update([
                'company_id' => $input['company_id'] ?? null,
                'contact_id' => $input['contact_id'] ?? null,
                'user_id' => $userId,
                'direction' => $input['direction'] ?? null,
                'date_attention' => $input['date_attention'] ?? null,
                'reference' => $input['reference'] ?? null,
                'observations' => $input['observations'] ?? null,
            ]);

            ItemsQuotes::where('quote_id', $quote->id)->delete();

            if (!empty($input['items']) && is_array($input['items'])) {
                foreach ($input['items'] as $item) {
                    $type = $item['type'] ?? null;
                    $value = $item['item'] ?? [];

                    if (!$type || empty($value)) {
                        continue;
                    }

                    $model = match ($type) {
                        'service' => Services::class,
                        'matriz' => Matriz::class,
                        default => null,
                    };

                    $amount = $value['amount'] ?? $value['number_samples'] ?? 0;
                    $unitPrice = $value['unit_price'] ?? 0;
                    $lineTotal = $value['price'] ?? ($amount * $unitPrice);

                    ItemsQuotes::create([
                        'quote_id' => $quote->id,
                        'type' => $type,
                        'filable_type' => $model,
                        'filable_id' => $value['id'] ?? null,
                        'item' => $value,
                        'amount' => $amount,
                        'price_unit' => $value['unit_price'],
                        'total' => $value['price'],
                    ]);
                }
            }

            if (!empty($input['other_expenses']) && is_array($input['other_expenses'])) {
                foreach ($input['other_expenses'] as $item) {
                    $amount = $item['amount'] ?? 0;

                    ItemsQuotes::create([
                        'quote_id' => $quote->id,
                        'type' => $item['type'] ?? 'other_expense',
                        'item' => $item,
                        'amount' => $amount,
                        'price_unit' => $item['unit_price'],
                        'total' => $item['price'],
                    ]);
                }
            }

            $quote->update([
                'items_total' => $input['subtotal'],
                'other_expenses_total' => $input['other_expenses_total'],
                'subtotal' => $input['subtotal'],
                'igv' => $input['igv'],
                'total' => $input['total'],
            ]);

            DB::commit();

            return $this->sendSuccess('Cotización actualizada con éxito');
        } catch (Exception $e) {
            DB::rollBack();

            return $this->sendError(sprintf(
                'El error está en %s línea %d. Detalles: %s',
                $e->getFile(),
                $e->getLine(),
                $e->getMessage()
            ));
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $quote = Quotes::find($id);

            if (!$quote) {
                DB::rollBack();
                return $this->sendError('Cotización no encontrada');
            }

            ItemsQuotes::where('quote_id', $quote->id)->delete();
            $quote->delete();

            DB::commit();

            return $this->sendSuccess('Cotización eliminada con éxito');
        } catch (Exception $e) {
            DB::rollBack();

            return $this->sendError(sprintf(
                'El error está en %s línea %d. Detalles: %s',
                $e->getFile(),
                $e->getLine(),
                $e->getMessage()
            ));
        }
    }

    public function exportQuote($id, Request $request)
    {
        $quote = Quotes::query()
            ->with([
                'company:id,ruc,business_name,direction,activity',
                'user',
                'itemsQuotes',
                'contact.user'
            ])
            ->find($id);

        if (!$quote) {
            return $this->sendError('No se encontro la cotización');
        }

        return Excel::download(new QuoteExport($quote), 'cotizacion.xlsx');
    }

    public function exportQuotePdf($id, Request $request)
    {
        $quote = Quotes::query()
            ->with([
                'company:id,ruc,business_name,direction,activity',
                'user',
                'itemsQuotes',
                'contact.user'
            ])
            ->find($id);

        if (!$quote) {
            return $this->sendError('No se encontró la cotización');
        }

        $company = $quote->company;

        $matrices = $quote->itemsQuotes->where('type', 'matriz');
        $services = $quote->itemsQuotes->where('type', 'service');
        $other_expense = $quote->itemsQuotes->where('type', 'other_expense');

        $ruc = strval($company->ruc);

        $groupedMatrices = $matrices
            ->groupBy(function ($matriz) {
                $description = data_get($matriz, 'item.description', 'Sin matriz');
                $frequencyLabel = data_get($matriz, 'item.frequency_label');

                return $description . '||' . ($frequencyLabel ?: 'SIN_FRECUENCIA');
            })
            ->map(function ($items) {
                $first = $items->first();

                return [
                    'description' => data_get($first, 'item.description', 'Sin matriz'),
                    'frequency_label' => data_get($first, 'item.frequency_label'),
                    'items' => $items,
                    'total' => $items->sum(fn($item) => (float) ($item->total ?? 0)),
                ];
            })
            ->values();

        $servicesTotal = $services->sum(function ($service) {
            return (float) data_get($service, 'item.price', $service->total ?? 0);
        });

        $otherExpenseTotal = $other_expense->sum(function ($otherexpense) {
            return (float) data_get($otherexpense, 'item.price', $otherexpense->total ?? 0);
        });

        $matricesTotal = $groupedMatrices->sum('total');
        $grandTotal = $matricesTotal + $servicesTotal;

        $pdf = Pdf::loadView('pdf.quotes.main', [
            'quote' => $quote,
            'company' => $company,
            'ruc' => $ruc,
            'groupedMatrices' => $groupedMatrices,
            'services' => $services,
            'servicesTotal' => $servicesTotal,
            'matricesTotal' => $matricesTotal,
            'grandTotal' => $grandTotal,
            'other_expense' => $other_expense,
            'otherExpenseTotal' => $otherExpenseTotal,
            'contact' => $quote?->contact
        ])->setPaper('a4', 'portrait');

        return $pdf->download('cotizacion-' . $quote->id . '.pdf');
    }
}
