<?php

namespace App\Models\tenant;

use App\Casts\LocalTimezone;
use App\Models\TenantModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quotes extends TenantModel
{
    use SoftDeletes;

    protected $table = 'quotes';

    protected $fillable = [
        'company_id',
        'contact_id',
        'user_id',
        'user_validated_id',
        'validated',
        'direction',
        'date_attention',
        'version',
        'code',
        'items_total',
        'services_total',
        'other_expenses_total',
        'igv',
        'subtotal',
        'total',
        'reference',
        'observations',
        'applicant_id',
    ];

    protected $casts = [
        'created_at' => LocalTimezone::class,
        'date_attention' => LocalTimezone::class,
        'validated' => 'boolean'
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(ContactCompanies::class, 'contact_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function validator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_validated_id');
    }

    public function itemsQuotes(): HasMany
    {
        return $this->hasMany(ItemsQuotes::class, 'quote_id');
    }

    public function orderService(): HasOne
    {
        return $this->hasOne(OrderService::class, 'quote_id', 'id');
    }

    public function applicant(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'applicant_id');
    }
}
