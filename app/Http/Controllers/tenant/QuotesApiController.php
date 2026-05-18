<?php

namespace App\Http\Controllers\tenant;

use App\Exports\QuoteExport;
use App\Http\Controllers\Controller;
use App\Models\tenant\Item;
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
            $search = $request->input('search');
            $comercialId = $request->input('comercial_id');
            $companyId = $request->input('company_id');
            $isOs = $request->boolean('is_os');

            $data = Quotes::query()
                ->with([
                    'company',
                    'user',
                    'validator',
                    'itemsQuotes',
                    'contact.user',
                    'orderService',
                    'applicant'
                ])
                ->when($search, function ($q) use ($search) {
                    $q->where(function ($query) use ($search) {
                        $query
                            ->whereHas('company', function ($company) use ($search) {
                                $company->where('business_name', 'LIKE', "%{$search}%")
                                    ->orWhere('ruc', 'LIKE', "%{$search}%");
                            });
                    });
                })
                ->when($comercialId, fn($q) => $q->where('user_id', $comercialId))
                ->when($companyId, fn($q) => $q->where('company_id', $companyId))
                ->when($isOs, fn($q) => $q->whereHas('orderService'))
                ->latest()
                ->paginate(15);

            return $this->sendResponse($data, 'Cotizaciones enviadas correctamente');
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

            $items = $quote->itemsQuotes
                ->where('type', 'matrix')
                ->map(function ($item) {
                    return $item->item;
                })
                ->values();

            $services = $quote->itemsQuotes
                ->where('type', 'service')
                ->map(function ($item) {
                    return $item->item;
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
                'services_total' => $quote?->services_total,
                'other_expenses_total' => $quote->other_expenses_total,
                'igv' => $quote->igv,
                'subtotal' => $quote->subtotal,
                'total' => $quote->total,
                'reference' => $quote->reference,
                'observations' => $quote->observations,
                'contact_id' => $quote->contact_id,
                'items' => $items,
                'services' => $services,
                'other_expenses' => $otherExpenses,
                'is_os' => $quote?->orderService ? true : false,
                'order_service' => $quote?->orderService,
                'applicant_id' => $quote?->applicant_id
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

                'items_total' => $input['items_total'] ?? 0,
                'services_total' => $input['services_total'] ?? 0,
                'other_expenses_total' => $input['other_expenses_total'] ?? 0,

                'subtotal' => $input['subtotal'] ?? 0,
                'igv' => $input['igv'] ?? 0,
                'total' => $input['total'] ?? 0,

                'contact_id' => $input['contact_id'] ?? null,
                'applicant_id' => $input['applicant_id'] ?? null,
            ]);

            if (!empty($input['items']) && is_array($input['items'])) {
                foreach ($input['items'] as $item) {
                    ItemsQuotes::create([
                        'quote_id' => $quote->id,
                        'type' => 'matrix',
                        'filable_type' => Item::class,
                        'filable_id' => $item['id'] ?? null,
                        'item' => $item,
                        'amount' => $item['number_samples'] ?? 0,
                        'price_unit' => $item['unit_price'] ?? 0,
                        'total' => $item['price'] ?? 0,
                    ]);
                }
            }

            if (!empty($input['services']) && is_array($input['services'])) {
                foreach ($input['services'] as $item) {
                    ItemsQuotes::create([
                        'quote_id' => $quote->id,
                        'type' => 'service',
                        'filable_type' => LogisticCats::class,
                        'filable_id' => $item['id'] ?? null,
                        'item' => $item,
                        'amount' => $item['item']['amount'] ?? 0,
                        'price_unit' => $item['item']['unit_price'] ?? 0,
                        'total' => $item['price'] ?? 0,
                    ]);
                }
            }

            if (!empty($input['other_expenses']) && is_array($input['other_expenses'])) {
                foreach ($input['other_expenses'] as $item) {
                    ItemsQuotes::create([
                        'quote_id' => $quote->id,
                        'type' => $item['type'] ?? 'other_expense',
                        'filable_type' => LogisticCats::class,
                        'filable_id' => $item['id'] ?? null,
                        'item' => $item,
                        'amount' => $item['amount'] ?? 0,
                        'price_unit' => $item['unit_price'] ?? 0,
                        'total' => $item['price'] ?? 0,
                    ]);
                }
            }

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
            $userId = Auth::guard('api')->id();

            if (!$userId) {
                return $this->sendError('No hay un usuario autenticado');
            }

            DB::beginTransaction();

            $quote = Quotes::findOrFail($id);

            $input = $request->all();

            $quote->update([
                'company_id' => $input['company_id'] ?? null,
                'user_id' => $userId,
                'direction' => $input['direction'] ?? null,
                'date_attention' => $input['date_attention'] ?? null,
                'reference' => $input['reference'] ?? null,
                'observations' => $input['observations'] ?? null,

                'items_total' => $input['items_total'] ?? 0,
                'services_total' => $input['services_total'] ?? 0,
                'other_expenses_total' => $input['other_expenses_total'] ?? 0,

                'subtotal' => $input['subtotal'] ?? 0,
                'igv' => $input['igv'] ?? 0,
                'total' => $input['total'] ?? 0,

                'contact_id' => $input['contact_id'] ?? null,
                'applicant_id' => $input['applicant_id'] ?? null,
            ]);

            ItemsQuotes::query()
                ->where('quote_id', $quote->id)
                ->delete();

            if (!empty($input['items']) && is_array($input['items'])) {
                foreach ($input['items'] as $item) {
                    ItemsQuotes::create([
                        'quote_id' => $quote->id,
                        'type' => 'matrix',
                        'filable_type' => Item::class,
                        'filable_id' => $item['id'] ?? null,
                        'item' => $item,
                        'amount' => $item['number_samples'] ?? 0,
                        'price_unit' => $item['unit_price'] ?? 0,
                        'total' => $item['price'] ?? 0,
                    ]);
                }
            }

            if (!empty($input['services']) && is_array($input['services'])) {
                foreach ($input['services'] as $item) {
                    ItemsQuotes::create([
                        'quote_id' => $quote->id,
                        'type' => 'service',
                        'filable_type' => LogisticCats::class,
                        'filable_id' => $item['id'] ?? null,
                        'item' => $item,
                        'amount' => $item['item']['amount'] ?? 0,
                        'price_unit' => $item['item']['unit_price'] ?? 0,
                        'total' => $item['price'] ?? 0,
                    ]);
                }
            }

            if (!empty($input['other_expenses']) && is_array($input['other_expenses'])) {
                foreach ($input['other_expenses'] as $item) {
                    ItemsQuotes::create([
                        'quote_id' => $quote->id,
                        'type' => $item['type'] ?? 'other_expense',
                        'filable_type' => LogisticCats::class,
                        'filable_id' => $item['id'] ?? null,
                        'item' => $item,
                        'amount' => $item['amount'] ?? 0,
                        'price_unit' => $item['unit_price'] ?? 0,
                        'total' => $item['price'] ?? 0,
                    ]);
                }
            }

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

    public function destroy($id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $quote = Quotes::findOrFail($id);

            ItemsQuotes::query()
                ->where('quote_id', $quote->id)
                ->delete();

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
                'contact.user',
            ])
            ->find($id);

        if (!$quote) {
            return $this->sendError('No se encontró la cotización');
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
                'contact.user',
            ])
            ->find($id);

        if (!$quote) {
            return $this->sendError('No se encontró la cotización');
        }

        $company = $quote->company;
        $ruc = strval($company?->ruc ?? '');

        $matrices = $quote->itemsQuotes->where('type', 'matrix');
        $services = $quote->itemsQuotes->where('type', 'service');
        $other_expense = $quote->itemsQuotes->where('type', 'other_expense');

        $groupedMatrices = $matrices
            ->groupBy(function ($matrix) {
                $description = data_get($matrix, 'item.matrix.description', 'Sin matriz');

                $frequencyLabel = data_get($matrix, 'item.frequency_label')
                    ?? data_get($matrix, 'item.item.frequency_label');

                return $description . '||' . ($frequencyLabel ?: 'SIN_FRECUENCIA');
            })
            ->map(function ($items) {
                $first = $items->first();

                return [
                    'description' => data_get($first, 'item.matrix.description', 'Sin matriz'),
                    'frequency_label' => data_get($first, 'item.frequency_label')
                        ?? data_get($first, 'item.item.frequency_label'),
                    'items' => $items,
                    'total' => $items->sum(fn($item) => (float) ($item->total ?? 0)),
                ];
            })
            ->values();

        $matricesTotal = $matrices->sum(fn($item) => (float) ($item->total ?? 0));

        $servicesTotal = $services->sum(function ($service) {
            return (float) (
                $service->total
                ?? data_get($service, 'item.price')
                ?? data_get($service, 'item.item.price')
                ?? 0
            );
        });

        $otherExpenseTotal = $other_expense->sum(function ($otherexpense) {
            return (float) (
                $otherexpense->total
                ?? data_get($otherexpense, 'item.total')
                ?? data_get($otherexpense, 'item.price')
                ?? 0
            );
        });

        $grandTotal = $matricesTotal + $servicesTotal + $otherExpenseTotal;

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
            'contact' => $quote?->contact,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('cotizacion-' . $quote->id . '.pdf');
    }
}
