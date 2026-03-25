<?php

namespace App\Models\tenant;

use App\Casts\LocalTimezone;
use App\Models\TenantModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LogisticCats extends TenantModel
{
    use SoftDeletes;

    protected $table = 'logistic_cats';

    protected $fillable = [
        'description',
        'unit_price',
    ];

    protected $casts = [
        'created_at' => LocalTimezone::class,
        'updated_at' => LocalTimezone::class,
    ];
}
