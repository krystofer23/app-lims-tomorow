<?php

namespace App\Models\tenant;

use App\Casts\LocalTimezone;
use App\Models\TenantModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChainCustody extends TenantModel
{
    protected $table = 'chain_custody';

    protected $fillable = [
        'company_id',
        'application_id',
        'order_id',
        'os',
        'content',
    ];

    protected $casts = [
        'created_at' => LocalTimezone::class,
        'content' => 'json'
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'application_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(OrderService::class, 'order_id');
    }
}
