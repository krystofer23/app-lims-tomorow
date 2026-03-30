<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UseTenantDb
{
    // public function handle(Request $request, Closure $next)
    // {
    //     if (tenant() && method_exists(tenant(), 'getDatabaseName')) {
    //         $dbName = tenant()->getDatabaseName();

    //         if ($dbName) {
    //             config(['database.connections.tenant.database' => $dbName]);
    //             DB::purge('tenant');
    //             DB::reconnect('tenant');
    //         }
    //     }

    //     return $next($request);
    // }

    public function handle(Request $request, Closure $next)
    {
        if (tenant()) {
            $dbName = tenant()->getDatabaseName();

            if (! $dbName) {
                abort(500, 'El tenant no tiene base de datos asignada.');
            }

            config([
                'database.default' => 'tenant',
                'database.connections.tenant.database' => $dbName,
            ]);

            DB::purge('tenant');
            DB::setDefaultConnection('tenant');
            DB::reconnect('tenant');
        }

        return $next($request);
    }
}
