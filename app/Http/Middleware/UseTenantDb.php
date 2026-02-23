<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UseTenantDb
{
    public function handle(Request $request, Closure $next)
    {
        if (tenant() && method_exists(tenant(), 'getDatabaseName')) {
            $dbName = tenant()->getDatabaseName();

            if ($dbName) {
                config(['database.connections.tenant.database' => $dbName]);
                DB::purge('tenant');
                DB::reconnect('tenant');
            }
        }

        return $next($request);
    }
}