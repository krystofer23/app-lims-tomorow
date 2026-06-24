<?php

namespace App\Models\tenant;

use App\Casts\LocalTimezone;
use App\Models\TenantModel;
use Illuminate\Database\Eloquent\Model;

class TypeOfAnalysis extends TenantModel
{
    protected $table = 'type_of_analysis';

    protected $fillable = [
        'description'
    ];

    protected $casts = [
        'created_at' => LocalTimezone::class,
    ];
}
