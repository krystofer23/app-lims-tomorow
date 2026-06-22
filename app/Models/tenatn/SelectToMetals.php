<?php

namespace App\Models\tenatn;

use App\Casts\LocalTimezone;
use App\Models\TenantModel;
use Illuminate\Database\Eloquent\Model;

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
}
