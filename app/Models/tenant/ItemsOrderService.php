<?php

namespace App\Models\tenant;

use App\Casts\LocalTimezone;
use App\Models\TenantModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ItemsOrderService extends TenantModel
{
    protected $table = 'items_order_service';

    protected $fillable = [
        'order_service_id',
        'filable_type',
        'filable_id',
        'item',
        'type',
        'amount',
        'price_unit',
        'total',
    ];

    protected $casts = [
        'created_at' => LocalTimezone::class,
        'item' => 'json'
    ];

    public function orderService(): BelongsTo
    {
        return $this->belongsTo(OrderService::class, 'order_service_id');
    }

    public function filable(): MorphTo
    {
        return $this->morphTo();
    }
}
