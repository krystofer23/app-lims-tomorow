<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UseTenantDbConnection
{
    public function handle(Request $request, Closure $next)
    {
        if (tenant()) {
            // AJUSTA este campo según donde guardas tu DB del tenant:
            // Ejemplos comunes:
            // $dbName = tenant('database');
            // $dbName = tenant('tenancy_db_name');
            // $dbName = tenant('data')['tenancy_db_name'] ?? null;

            $dbName = data_get(tenant('data'), 'tenancy_db_name'); // <-- cambia si tu key es otra

            if ($dbName) {
                config(['database.connections.tenant.database' => $dbName]);
                DB::purge('tenant');
                DB::reconnect('tenant');
            }
        }

        return $next($request);
    }
}
