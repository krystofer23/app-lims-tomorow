<?php

namespace App\Models\tenant;

use App\Casts\LocalTimezone;
use App\Models\TenantModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Services extends TenantModel
{
    use SoftDeletes;

    protected $table = 'services';

    protected $fillable = [
        'description',
        'days',
        'amount',
        'unit_price',
        'price',
    ];

    protected $casts = [
        'created_at' => LocalTimezone::class,
    ];
}
