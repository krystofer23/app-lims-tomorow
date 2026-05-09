<?php

namespace App\Models\tenant;

use App\Casts\LocalTimezone;
use App\Models\TenantModel;
use Illuminate\Database\Eloquent\Model;

class ReferencesStandard extends TenantModel
{
    protected $table = 'references_standard';

    protected $fillable = [
        'code',
        'title',
    ];

    protected $casts = [
        'created_at' => LocalTimezone::class
    ];
}
