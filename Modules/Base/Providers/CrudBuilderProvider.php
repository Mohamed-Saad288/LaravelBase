<?php

namespace Modules\Base\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Base\Builder\CrudBuilder;

class CrudBuilderProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton("crud_builder", function ($app) {
            return new CrudBuilder();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

    }
}
