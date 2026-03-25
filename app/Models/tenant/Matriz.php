<?php

namespace App\Models\tenant;

use App\Casts\LocalTimezone;
use App\Models\TenantModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Matriz extends TenantModel
{
    use SoftDeletes;

    protected $table = 'matriz';

    protected $fillable = [
        'description',
        'methodologie_id',
        'number_samples',
        'unit_price',
        'price',
        'category',
        'information', 
        'type_matriz',
    ];

    protected $casts = [
        'created_at' => LocalTimezone::class,
    ];

    public function methodologie(): BelongsTo
    {
        return $this->belongsTo(Methodologies::class, 'methodologie_id');
    }

    public function itemsMatriz(): HasMany
    {
        return $this->hasMany(ItemsMatriz::class, 'matriz_id', 'id');
    }
}
