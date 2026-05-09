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
        'type',
        'category',
        'description',
        'unit_price',
        'condition_id',
        'other_company',
        'company_id',
    ];

    protected $casts = [
        'created_at' => LocalTimezone::class,
        'other_company' => 'boolean'
    ];

    public function itemsMatriz(): HasMany
    {
        return $this->hasMany(ItemsMatriz::class, 'matriz_id', 'id');
    }

    public function condition(): BelongsTo
    {
        return $this->belongsTo(Conditions::class, 'condition_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
