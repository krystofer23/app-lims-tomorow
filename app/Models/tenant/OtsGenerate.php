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
        'user_id',
        'os',
        'order_id',
        'content',
    ];

    protected $casts = [
        'created_at' => LocalTimezone::class
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
