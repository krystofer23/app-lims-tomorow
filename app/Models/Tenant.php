<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant
{
    use HasDatabase, HasDomains;

    public function getDatabaseName(): string
    {
        return (string) ($this->tenancy_db_name ?? '');
    }
}