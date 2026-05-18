<?php

namespace App\Models\tenant;

use App\Models\TenantModel;
use Illuminate\Database\Eloquent\Model;

class TypeOfAnalysis extends TenantModel
{
    protected $table = 'type_of_analysis';

    protected $fillable = [
        'description'
    ];
}
