<?php

namespace App\Models\tenant;

use App\Casts\LocalTimezone;
use App\Models\TenantModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Methodologies extends TenantModel
{
    use SoftDeletes;

    protected $table = 'methodologies';

    protected $fillable = [
        'description',
        'info',
    ];

    protected $casts = [
        'created_at' => LocalTimezone::class
    ];
}
