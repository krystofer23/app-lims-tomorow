<?php

namespace App\Models\Tenant;

use App\Casts\LocalTimezone;
use App\Models\TenantModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use SoftDeletes, HasRoles;

    protected $connection = 'tenant';

    protected string $guard_name = 'api';

    protected $table = 'users';

    protected $fillable = [
        'name',
        'last_name_first',
        'last_name_second',
        'full_name',
        'type_document',
        'document_number',
        'sex',
        'age',
        'birthdate',
        'email',
        'password',
        'is_professional',
        'signature',
        'active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_professional' => 'boolean',
        'active' => 'boolean',
        'created_at' => LocalTimezone::class,
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getDefaultGuardName(): string
    {
        return $this->guard_name;
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }
}
