<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as BaseProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends BaseProvider
{
    public function boot()
    {
        Route::middleware('api')
            ->prefix('api')
            ->group(base_path('routes/api.php'));

        Route::middleware('web')
            ->group(base_path('routes/web.php'));
    }
}
