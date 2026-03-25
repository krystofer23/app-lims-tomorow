<?php

namespace App\Models\tenant;

use App\Casts\LocalTimezone;
use App\Models\TenantModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemsQuotes extends TenantModel
{
    use SoftDeletes;

    protected $table = 'items_quotes';

    protected $fillable = [
        'quote_id',
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

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quotes::class, 'quote_id');
    }

    public function filable(): MorphTo
    {
        return $this->morphTo();
    }
}
