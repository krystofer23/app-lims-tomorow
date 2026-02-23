<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Stancl\Tenancy\Facades\Tenancy;

class TenancyDbDebug extends Command
{
    protected $signature = 'tenancy:db-debug';
    protected $description = 'Muestra conexión y DB actual (para verificar tenancy).';

    public function handle(): int
    {
        DB::purge('tenant');

        $this->line('tenant_id: ' . (tenant('id') ?? 'null'));

        $this->line('default_connection: ' . DB::getDefaultConnection());
        $this->line('current_db: ' . (DB::connection()->getDatabaseName() ?? 'null'));

        config(['database.connections.tenant.database' => tenant()?->tenancy_db_name]); // tenant_greenlab

        DB::reconnect('tenant');

        $this->line('tenant_conn_db_config: ' . (config('database.connections.tenant.database') ?? 'null'));
        $this->line('tenant_db: ' . (DB::connection('tenant')->getDatabaseName() ?? 'null'));
        $this->line('tenant_model_db: ' . (tenant()?->tenancy_db_name ?? 'null'));

        return self::SUCCESS;
    }
}
