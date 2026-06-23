<?php

namespace App\Models\tenatn;

use App\Casts\LocalTimezone;
use App\Models\tenant\OrderService;
use App\Models\tenant\Parameters;
use App\Models\TenantModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SelectToMetals extends TenantModel
{
    protected $table = 'select_to_metals';

    protected $fillable = [
        'order_id',
        'to_metal_id',
        'parameter_id',
        'item',
    ];

    protected $casts = [
        'item' => 'array',
        'created_at' => LocalTimezone::class,
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(OrderService::class, 'order_id');
    }

    public function toMetal(): BelongsTo
    {
        return $this->belongsTo(Parameters::class, 'to_metal_id');
    }

    public function parameter(): BelongsTo
    {
        return $this->belongsTo(Parameters::class, 'parameter_id');
    }
}
