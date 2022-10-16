<?php

use Illuminate\Support\Facades\Route;
use Orion\Larashared\Http\Controllers\API\CommandController;
use Orion\Larashared\Http\Middleware\Authenticate;

Route::middleware(Authenticate::class)
    ->prefix(config('larashared.path'))
    ->group(static function () {
        Route::controller(CommandController::class)->group(static function () {
            Route::post('optimize/{action?}', 'optimize');

            Route::post('config/{action?}', 'config');

            Route::post('route/{action?}', 'routeCommand');

            Route::post('view/{action?}', 'view');

            Route::post('migrate/{action?}', 'migrate');

            Route::post('seed', 'seed');

            Route::post('maintenance', 'maintenance');
        });
    });
