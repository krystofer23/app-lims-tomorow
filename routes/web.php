<?php

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

$base = config('app.base_domain');

Route::domain($base)->group(function () {
    Route::get('/{any?}', fn() => view('app'))->where('any', '^(?!api).*$');
});

Route::domain("central.$base")->group(function () {
    Route::get('/{any?}', fn() => view('app'))->where('any', '^(?!api).*$');
});

Route::middleware([
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('/{any?}', fn() => view('app'))->where('any', '^(?!tenant).*$');
});
