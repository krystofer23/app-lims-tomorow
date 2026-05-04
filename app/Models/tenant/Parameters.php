<?php

namespace App\Models\tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Parameters extends Model
{
    use SoftDeletes;

    protected $table = 'parameters';

    protected $fillable = [
        'type',
        'content',
    ];

    protected $casts = [
        'content' => 'array'
    ];
}
