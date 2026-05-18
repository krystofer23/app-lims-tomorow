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
        'reviwed',
        'company_id',
        'contact_company',
        'direction',
        'date_attention',
        'application_id',
        'contact_application',
        'department',
        'district',
        'province',
        'reference',
        'origin',
        'project',
        'date_init_service',
        'date_end_monitoring',
        'users',
        'details',
        'monitoring',
        'projects',
        'service_includes',
        'accommodation',
        'travel_expenses',
        'days_service',
        'personal_transport',
        'send_sampling',
        'surveillance',
        'electric_generator',
        'company_emission_id',
        'type_document_required',
        'number_copy',
        'code',
        'observations',
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
        'users' => 'json',
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
}
