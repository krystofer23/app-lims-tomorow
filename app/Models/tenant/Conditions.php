<?php

namespace App\Models\tenant;

use App\Casts\LocalTimezone;
use App\Models\TenantModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conditions extends TenantModel
{
    use SoftDeletes;

    protected $table = 'conditions';

    protected $fillable = [
        'description',
        'info',
    ];

    protected $casts = [
        'created_at' => LocalTimezone::class
    ];
}
