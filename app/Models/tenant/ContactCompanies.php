<?php

namespace App\Models\tenant;

use App\Casts\LocalTimezone;
use App\Models\Tenant\User;
use App\Models\TenantModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactCompanies extends TenantModel
{
    use SoftDeletes;

    protected $table = 'contact_companies';

    protected $fillable = [
        'phone',
        'email',
        'active',
        'company_id',
        'user_id',
        'is_collection',
        'type',
    ];

    protected $casts = [
        'active' => 'boolean',
        'is_collection' => 'boolean',
        'created_at' => LocalTimezone::class
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
