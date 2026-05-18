<?php

namespace App\Models\tenant;

use App\Models\TenantModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pronvince extends TenantModel
{
    protected $table = 'ubigeo_provincias';

    protected $fillable = [
        'provincia',
        'ubigeo',
        'departamento_id'
    ];

    public function departament(): BelongsTo
    {
        return $this->belongsTo(Departament::class, 'departamento_id');
    }
}
