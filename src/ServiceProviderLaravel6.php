<?php

namespace Zibal;

class ServiceProviderLaravel6 extends \Illuminate\Support\ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/migrations');
        // $this->publishes([__DIR__.'/migrations'=> database_path('migrations')]);
        // $this->publishes([__DIR__ . '/config/config.php' => config_path('zibal.php')]);
    }
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/config.php', 'zibal');
        $this->app->singleton('zibal', function ($app) {
            return new ZibalApi();
        });
    }
}
