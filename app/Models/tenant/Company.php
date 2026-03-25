<?php

namespace App\Models\tenant;

use App\Casts\LocalTimezone;
use App\Models\TenantModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends TenantModel
{
    use SoftDeletes;

    protected $table = 'companies';

    protected $fillable = [
        'ruc',
        'business_name',
        'direction',
        'activity',
        'state',
        'is_supplier',
        'is_partner',
    ];

    protected $casts = [
        'is_supplier' => 'boolean',
        'is_partner' => 'boolean',
        'state' => 'boolean',
        'created_at' => LocalTimezone::class
    ];

    public function contacts(): HasMany
    {
        return $this->hasMany(ContactCompanies::class, 'company_id', 'id');
    }
}
