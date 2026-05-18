<?php

namespace App\Models\tenant;

use App\Models\TenantModel;
use Illuminate\Database\Eloquent\Model;

class Departament extends TenantModel
{
    protected $table = 'ubigeo_departamentos';

    protected $fillable = [
        'departamento',
        'ubigeo',
    ];
}
