<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class TenantsMigrateForceAll extends Command
{
    protected $signature = 'tenants:migrate-force-all
        {--tenants= : Lista de tenant IDs separados por coma (opcional)}
        {--pretend : Muestra el SQL sin ejecutar}
        {--step : Ejecuta migraciones con --step}
        {--seed : Ejecuta también los seeders del tenant}
        {--seeder= : Seeder específico a ejecutar (opcional)}
        {--debug : Muestra data cruda cuando no encuentra DB}';

    protected $description = 'Migra tenants forzando la DB en la conexión tenant (lee DB desde tenants.data JSON).';

    public function handle(): int
    {
        $filter = trim((string) $this->option('tenants'));
        $ids = $filter !== '' ? array_values(array_filter(array_map('trim', explode(',', $filter)))) : [];

        $q = DB::table('tenants')->select(['id', 'data'])->orderBy('id');

        if (!empty($ids)) {
            $q->whereIn('id', $ids);
        }

        $tenants = $q->get();

        if ($tenants->isEmpty()) {
            $this->warn('No se encontraron tenants para migrar.');
            return self::SUCCESS;
        }

        $baseArgs = [
            '--database' => 'tenant',
            '--path' => 'database/migrations/tenant',
            '--force' => true,
        ];

        if ($this->option('pretend')) {
            $baseArgs['--pretend'] = true;
        }

        if ($this->option('step')) {
            $baseArgs['--step'] = true;
        }

        $shouldSeed = (bool) $this->option('seed');
        $specificSeeder = trim((string) $this->option('seeder'));

        foreach ($tenants as $t) {
            $tenantId = (string) $t->id;

            $raw = $t->data;

            $data = [];
            if (is_string($raw) && $raw !== '') {
                $data = json_decode($raw, true) ?: [];
            } elseif (is_array($raw)) {
                $data = $raw;
            } elseif (is_object($raw)) {
                $data = (array) $raw;
            }

            $dbName =
                (string) ($data['tenancy_db_name'] ?? '') ?:
                (string) ($data['database'] ?? '') ?:
                (string) ($data['db_name'] ?? '') ?:
                (string) ($data['tenant_db_name'] ?? '') ?:
                '';

            if ($dbName === '') {
                $msg = "Tenant {$tenantId}: no tiene DB en data (busqué tenancy_db_name/database/db_name/tenant_db_name). Saltando.";
                $this->error($msg);

                if ($this->option('debug')) {
                    $this->line('  raw data: ' . (is_string($raw) ? $raw : json_encode($raw)));
                }

                $this->newLine();
                continue;
            }

            $this->info("Tenant: {$tenantId} | DB: {$dbName}");

            DB::purge('tenant');
            config(['database.connections.tenant.database' => $dbName]);

            try {
                DB::reconnect('tenant');
                DB::connection('tenant')->select('SELECT 1');
                $this->line('  ✅ Conexión OK');
            } catch (\Throwable $e) {
                $this->error("  ❌ No conecta a DB {$dbName}: {$e->getMessage()}");
                $this->newLine();
                continue;
            }

            try {
                Artisan::call('migrate', $baseArgs, $this->output);
                $this->line('  ✅ Migrado');
            } catch (\Throwable $e) {
                $this->error("  ❌ Falló migrate: {$e->getMessage()}");
                $this->newLine();
                continue;
            }

            if ($shouldSeed) {
                try {
                    $seedArgs = [
                        '--database' => 'tenant',
                        '--class' => $specificSeeder !== ''
                            ? $specificSeeder
                            : 'Database\\Seeders\\Tenant\\TenantSeeder',
                        '--force' => true,
                    ];

                    Artisan::call('db:seed', $seedArgs, $this->output);

                    $this->line(
                        "  ✅ Seeder ejecutado: {$seedArgs['--class']}"
                    );
                } catch (\Throwable $e) {
                    $this->error("  ❌ Falló seed: {$e->getMessage()}");
                }
            }

            $this->newLine();
        }

        return self::SUCCESS;
    }
}
