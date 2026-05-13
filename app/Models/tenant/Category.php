<?php

namespace App\Models\tenant;

use App\Models\TenantModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends TenantModel
{
    use SoftDeletes;

    protected $table = 'categories';

    protected $fillable = [
        'description'
    ];
}
