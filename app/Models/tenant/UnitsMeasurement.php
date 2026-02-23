<?php

namespace App\Models\tenant;

use App\Casts\LocalTimezone;
use App\Models\TenantModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UnitsMeasurement extends TenantModel
{
    use SoftDeletes;

    protected $table = 'units_measurement';

    protected $fillable = [
        'description'
    ];

    protected $casts = [
        'created_at' => LocalTimezone::class
    ];
}
