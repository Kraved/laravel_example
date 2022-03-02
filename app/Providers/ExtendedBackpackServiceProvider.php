<?php

namespace App\Providers;


class ExtendedBackpackServiceProvider extends \Backpack\CRUD\BackpackServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(\Illuminate\Routing\Router $router)
    {
        $this->loadViewsWithFallbacks();
        $this->loadTranslationsFrom(realpath(base_path().'/vendor/backpack/crud/src/resources/lang'), 'backpack');
        $this->loadConfigs();
        $this->registerMiddlewareGroup($this->app->router);
        $this->setupRoutes($this->app->router);
        $this->setupCustomRoutes($this->app->router);
        $this->publishFiles();
    }
}