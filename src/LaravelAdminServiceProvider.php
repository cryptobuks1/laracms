<?php

namespace Laracms;

use Illuminate\Support\ServiceProvider;
use Laracms\Singletons\Role;
use Laracms\Singletons\Menu;

class LaravelAdminServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/config.php', 'laraveladmin'
        );

        $this->app->singleton('role', function () {
            return new Role;
        });

        $this->app->singleton('menu', function () {
            return new Menu;
        });

        $this->app->bind(
            \Laracms\Repositories\Contracts\OptionInterface::class,
            \Laracms\Repositories\Eloquents\OptionRepository::class
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishVendor();
        $this->loadServices();
    }

    /**
     * Publish vendor
     * 
     * @return void
     */
    private function publishVendor()
    {
        if ($this->app->runningInConsole()) {
            # Publish config
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('laraveladmin.php')
            ], 'config');

            # Publish assets
            $this->publishes([
                __DIR__.'/../assets' => public_path('vendor/laraveladmin'),
            ], 'public');
        }
    }

    /**
     * Load services
     * 
     * @return void
     */
    private function loadServices()
    {
        $this->loadRoutesFrom(__DIR__.'/Routes/routes.php');
        $this->loadMigrationsFrom(__DIR__.'/Migrations');
        $this->loadViewsFrom(__DIR__.'/Views', 'laraveladmin');
    }
}
