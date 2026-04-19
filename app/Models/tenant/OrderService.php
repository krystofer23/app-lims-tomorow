<?php

namespace App\Models\tenant;

use App\Casts\LocalTimezone;
use App\Models\Tenant\User;
use App\Models\TenantModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderService extends TenantModel
{
    use SoftDeletes;

    protected $table = 'order_service';

    protected $fillable = [
        'quote_id',
        'user_id',
        'reviwed_id',
        'company_id',
        'reviwed',
        'reference',
        'origin',
        'project',
        'date_monitoring_init',
        'date_monitoring_end',
        'date_induction',
        'date_output',
        'details',
        'stations_monitoring',
        'project_monitoring',
        'conditions',
        'emision_data',
        'observations',
        'code',
        'contact_id',
        'direction',
        'date_attention'
    ];

    protected $casts = [
        'date_monitoring_init' => LocalTimezone::class,
        'date_monitoring_end' => LocalTimezone::class,
        'date_induction' => LocalTimezone::class,
        'date_output' => LocalTimezone::class,
        'created_at' => LocalTimezone::class,
        'date_attention' => LocalTimezone::class,
        'conditions' => 'json',
        'emision_data' => 'json',
    ];

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quotes::class, 'quote_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reviwed(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviwed_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(ItemsOrderService::class, 'order_service_id', 'id');
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(ContactCompanies::class, 'contact_id');
    }
}
