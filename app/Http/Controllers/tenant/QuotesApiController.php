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
                    'itemsQuotes'
                ])
                ->paginate(15);

            return $this->sendResponse($data, 'Enviandos cotizaciones');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $quote = Quotes::with('itemsQuotes')->find($id);

            if (!$quote) {
                return $this->sendError('Cotización no encontrada');
            }

            return $this->sendResponse($quote, 'Enviando cotización');
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
                    $unitPrice = $value['unit_price'] ?? 0;
                    $lineTotal = $value['price'] ?? ($amount * $unitPrice);

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
            ])
            ->find($id);

        if (!$quote) {
            return $this->sendError('No se encontro la cotización');
        }

        return Excel::download(new QuoteExport($quote), 'cotizacion.xlsx');
    }
}
