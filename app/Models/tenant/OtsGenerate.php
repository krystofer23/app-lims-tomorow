<?php

namespace App\Models\tenant;

use App\Casts\LocalTimezone;
use App\Models\Tenant\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OtsGenerate extends Model
{
    protected $table = 'ots_generate';

    protected $fillable = [
        'os',
        'order_id',
        'number_chain',
        'number_report',
        'matrix_id',
        'delivery_date',
        'hour',
        'parameters',
    ];

    protected $casts = [
        'created_at' => LocalTimezone::class,
        'parameters' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function matrix(): BelongsTo
    {
        return $this->belongsTo(Matrix::class, 'matrix_id');
    }
}
