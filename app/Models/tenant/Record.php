<?php

namespace App\Models\tenant;

use App\Casts\LocalTimezone;
use App\Models\Tenant\User;
use App\Models\TenantModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Record extends TenantModel
{
    protected $table = 'records';

    protected $fillable = [
        'user_id',
        'type',
        'fileable_type',
        'fileable_id',
    ];

    protected $casts = [
        'created_at' => LocalTimezone::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
