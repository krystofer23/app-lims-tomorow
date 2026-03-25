<?php

namespace App\Models\tenant;

use App\Casts\LocalTimezone;
use App\Models\TenantModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemsMatriz extends TenantModel
{
    use SoftDeletes;

    protected $table = 'items_matriz';

    protected $fillable = [
        'matriz_id',
        'essays_id',
    ];

    protected $casts = [
        'created_at' => LocalTimezone::class
    ];

    public function matriz(): BelongsTo
    {
        return $this->belongsTo(Matriz::class, 'matriz_id');
    }

    public function essays(): BelongsTo
    {
        return $this->belongsTo(Essays::class, 'essays_id');
    }
}
