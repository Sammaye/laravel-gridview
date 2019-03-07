<?php

namespace sammaye\Grid\Providers;

use Illuminate\Support\ServiceProvider;

class GridServiceProvider extends ServiceProvider
{

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../views', 'grid');
        $this->publishes([
          __DIR__ . '/../views' => base_path('resources/views/vendor/grid')
        ]);
    }
}
