<?php

namespace App\Models\tenant;

use App\Models\TenantModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Disctric extends TenantModel
{
    protected $table = 'ubigeo_distritos';

    protected $fillable = [
        'distrito',
        'ubigeo',
        'provincia_id',
        'departamento_id'
    ];

    public function province(): BelongsTo
    {
        return $this->belongsTo(Pronvince::class, 'provincia_id');
    }
}
