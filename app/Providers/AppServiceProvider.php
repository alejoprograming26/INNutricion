<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        // Compartir $ajuste con los layouts principales (Cacheado)
        View::composer(['components.layouts.app', 'components.layouts.auth'], function ($view) {
            $view->with('ajuste', \Illuminate\Support\Facades\Cache::rememberForever('global_ajuste', function () {
                return \App\Models\Ajuste::first();
            }));
        });
    }
}
