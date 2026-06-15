<?php

declare(strict_types=1);

use App\Http\Controllers\InformReportApiController;
use App\Http\Controllers\tenant\AuthApiController;
use App\Http\Controllers\tenant\CompaniesApiController;
use App\Http\Controllers\tenant\ConditionsApiController;
use App\Http\Controllers\tenant\ConsultingRucApiController;
use App\Http\Controllers\tenant\EssaysApiController;
use App\Http\Controllers\tenant\ImportApiController;
use App\Http\Controllers\tenant\ItemsApiController;
use App\Http\Controllers\tenant\LaboratoryApiController;
use App\Http\Controllers\tenant\ListApiController;
use App\Http\Controllers\tenant\LogisticCatsApiController;
use App\Http\Controllers\tenant\MatrizApiController;
use App\Http\Controllers\tenant\MethodologiesApiController;
use App\Http\Controllers\tenant\OrderServiceApiController;
use App\Http\Controllers\tenant\ParameterApiController;
use App\Http\Controllers\tenant\QuotesApiController;
use App\Http\Controllers\tenant\ReceptionApiController;
use App\Http\Controllers\tenant\ReportOtsApiController;
use App\Http\Controllers\tenant\ServiceApiController;
use App\Http\Controllers\tenant\TestImportApiController;
use App\Http\Controllers\tenant\TrialPeriodApiController;
use App\Http\Controllers\tenant\UbigeoApiController;
use App\Http\Controllers\tenant\UnitsMeasurementApiController;
use App\Http\Controllers\tenant\UserApiController;
use App\Http\Middleware\AllowExpiredTokenOnly;
use App\Http\Middleware\JWTMiddleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

Route::middleware([
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    \App\Http\Middleware\UseTenantDb::class,
])->group(function () {
    Route::middleware(['api'])->prefix('tenant')->group(function () {

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

        Route::middleware([AllowExpiredTokenOnly::class])->group(function () {

            Route::post('auth/refresh', [AuthApiController::class, 'refresh']);
        });

        Route::post('/auth', [AuthApiController::class, 'login']);

        Route::get('/test-prod', function () {
            return response()->json([
                'Todo bien'
            ]);
        });

        Route::middleware([JWTMiddleware::class])->group(function () {

            Route::controller(ParameterApiController::class)->prefix('parameters')->group(function () {

                Route::get('', 'index');
                Route::post('', 'store');
                Route::put('/{id}', 'update');
            });

            Route::controller(TrialPeriodApiController::class)->prefix('trial-period')->group(function () {

                Route::get('', 'show');
                Route::post('', 'store');
            });

            Route::controller(InformReportApiController::class)->prefix('information')->group(function () {

                Route::get('/{orderId}', 'show');
                Route::get('view-inform-report-pdf/{orderId}', 'downloadDesignPdf');
                Route::post('download-inform-report-excel/{orderId}', 'downloadDesignExcel');
            });

            Route::get('lab-orders-show/{orderId}', [LaboratoryApiController::class, 'show']);
            Route::post('lab-orders-store', [LaboratoryApiController::class, 'store']);
            Route::get('lab-orders', [LaboratoryApiController::class, 'orders']);

            Route::controller(UbigeoApiController::class)->prefix('ubigeo')->group(function () {

                Route::get('departments', 'departments');
                Route::get('provinces', 'provinces');
                Route::get('districts', 'districts');
            });

            Route::controller(ItemsApiController::class)->prefix('items')->group(function ($q) {

                Route::get('', 'index');
                Route::put('update-unit-price/{id}', 'updateUnitPrice');
            });

            Route::controller(TestImportApiController::class)->prefix('import')->group(function () {

                Route::post('', 'import');
            });

            Route::get('consulting-ruc/{ruc}', [ConsultingRucApiController::class, 'consulting']);

            Route::controller(ReportOtsApiController::class)->prefix('report-ots')->group(function () {

                Route::get('', 'index');
            });

            Route::controller(ReceptionApiController::class)->prefix('reception')->group(function () {

                Route::get('', 'index');
                Route::post('', 'store');
                Route::put('/{id}', 'update');
                Route::delete('/{id}', 'destroy');

                Route::post('generate-ot', 'generateOT');

                Route::get('download-excel/{id}', 'downloadExcelOT');
                Route::get('view-pdf-ot/{id}', 'viewPdfOT');

                Route::get('get-type-of-samples', 'getTypeOfSamples');
                Route::get('get-matrix', 'getMatrix');
            });

            Route::controller(OrderServiceApiController::class)->prefix('order-service')->group(function () {

                Route::get('', 'index');
                Route::get('/{id}', 'show');
                Route::post('', 'store');
                Route::put('/{id}', 'update');
                Route::delete('/{id}', 'destroy');
                Route::post('/export/{id}', 'exportOrderService');
                Route::post('/pdf/{id}', 'exportOrderServicePdf');
                Route::get('teams/{matrizId}', 'teams');
            });

            Route::controller(AuthApiController::class)->prefix('auth')->group(function () {

                Route::get('me', 'me');
                Route::post('logout', 'logout');
            });

            Route::controller(QuotesApiController::class)->prefix('quote')->group(function () {

                Route::get('', 'index');
                Route::get('/{id}', 'show');
                Route::post('', 'store');
                Route::put('/{id}', 'update');
                Route::delete('/{id}', 'destroy');
                Route::post('/export/{id}', 'exportQuote');
                Route::post('/pdf/{id}', 'exportQuotePdf');
            });

            Route::controller(ServiceApiController::class)->prefix('service')->group(function () {

                Route::get('', 'index');
                Route::post('', 'store');
                Route::put('/{id}', 'update');
                Route::delete('/{id}', 'destroy');
            });

            Route::controller(CompaniesApiController::class)->prefix('company')->group(function () {

                Route::get('', 'index');
                Route::get('/{id}', 'show');
                Route::post('', 'store');
                Route::put('/{id}', 'update');
            });

            Route::controller(ListApiController::class)->prefix('list')->group(function () {

                Route::get('orders-optimizate', 'ordersOptimizate');

                Route::get('types', 'types');
                Route::get('matrixs', 'matrixs');
                Route::get('matriz-description', 'matrizDescription');
                Route::get('conditions', 'conditions');
                Route::get('methodologies', 'methodologies');
                Route::get('units-measurement', 'unitsMeasurement');
                Route::get('essays', 'essays');
                Route::get('companies', 'companies');
                Route::get('services', 'services');
                Route::get('contacts', 'contacts');
                Route::get('teams', 'teams');
                Route::get('parameters', 'parameters');
                Route::get('users', 'users');
                Route::get('types-sampling', 'typesSampling');
                Route::get('types-analysis', 'typesAnalysis');
                Route::get('types-items', 'typesItems');
            });

            Route::controller(MatrizApiController::class)->prefix('matriz')->group(function () {

                Route::get('', 'index');
                Route::post('', 'store');
                Route::put('/{id}', 'update');
                Route::delete('/{id}', 'destroy');
            });

            Route::controller(EssaysApiController::class)->prefix('essays')->group(function () {

                Route::get('', 'index');
                Route::post('', 'store');
                Route::put('/{id}', 'update');
                Route::delete('/{id}', 'destroy');

                Route::get('get-relations-team/{id}', 'getRelationsTeam');
                Route::post('relations-team', 'relationsTeam');
            });

            Route::controller(MethodologiesApiController::class)->prefix('methodologies')->group(function () {

                Route::get('', 'index');
                Route::post('', 'store');
                Route::put('/{id}', 'update');
                Route::delete('/{id}', 'destroy');
            });

            Route::controller(UnitsMeasurementApiController::class)->prefix('units-measurement')->group(function () {

                Route::get('', 'index');
                Route::post('', 'store');
                Route::put('/{id}', 'update');
                Route::delete('/{id}', 'destroy');
            });

            Route::controller(ConditionsApiController::class)->prefix('conditions')->group(function () {

                Route::get('', 'index');
                Route::post('', 'store');
                Route::put('/{id}', 'update');
                Route::delete('/{id}', 'destroy');
            });

            Route::controller(LogisticCatsApiController::class)->prefix('logistic-cats')->group(function () {

                Route::get('', 'index');
                Route::post('', 'store');
                Route::put('/{id}', 'update');
                Route::delete('/{id}', 'destroy');
            });

            Route::controller(UserApiController::class)->prefix('users')->group(function () {

                Route::get('', 'index');
                Route::post('', 'store');
                Route::put('/{id}', 'update');
                Route::delete('/{id}', 'destroy');
            });
        });
    });
});
