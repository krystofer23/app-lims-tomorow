<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Area extends Model
{
    protected $connection = 'backend_greenlab';

    use HasFactory, SoftDeletes;

    protected $table = 'areas';

    protected $fillable = [
        'description',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean'
    ];
}
