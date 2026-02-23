<?php

declare(strict_types=1);

use App\Http\Controllers\tenant\UnitsMeasurementApiController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\InitializeTenancyBySubdomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

Route::middleware([
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    \App\Http\Middleware\UseTenantDb::class,
])->group(function () {
    Route::middleware('api')->prefix('tenant')->group(function () {

        Route::get('/ping', fn() => response()->json([
            'ok' => true,
            'tenant_id' => tenant('id'),
            'db' => config('database.default'),
            'database' => config('database.connections.' . config('database.default') . '.database'),
        ]));

        Route::get('/debug-db', function () {
            return response()->json([
                'tenant_id' => tenant('id'),
                'tenant_class' => get_class(tenant()),
                'tenant_db_from_model' => method_exists(tenant(), 'getDatabaseName') ? tenant()->getDatabaseName() : 'no-method',
                'tenant_data' => tenant()?->data ?? null,
                'tenant_connection_database' => config('database.connections.tenant.database'),
                'tenant_db_real' => DB::connection('tenant')->getDatabaseName(),
            ]);
        });

        Route::controller(UnitsMeasurementApiController::class)->prefix('unit-measurement')->group(function () {

            Route::get('', 'index');
            Route::post('', 'store');
            Route::put('/{id}', 'update');
            Route::delete('/{id}', 'destroy');
        });
    });
});
