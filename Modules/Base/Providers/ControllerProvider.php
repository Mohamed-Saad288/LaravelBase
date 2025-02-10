<?php

namespace Modules\Base\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Base\Http\Controllers\GeneralController;


class ControllerProvider extends ServiceProvider
{
    /**
     * Register services.
     */

        public function register()
    {
        $this->app->singleton(GeneralController::class, function ($app) {
            return new GeneralController();
        });
    }
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
