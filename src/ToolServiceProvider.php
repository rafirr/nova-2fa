<?php

namespace CarlosCGO\Google2fa;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use CarlosCGO\Google2fa\Http\Middleware\Authorize;

class ToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            // Publishing the configuration file.
            $this->publishes([
                __DIR__ . '/../config/google2fa.php' => config_path('google2fa.php'),
            ], 'google2fa.config');

            // Publishing the migrations.
            $this->publishes([
                __DIR__.'/../database/migrations/' => database_path('migrations')
            ], 'migrations');
            
            // Publishing the views.
            $this->publishes([
                __DIR__.'/../resources/views/' => base_path('resources/views/vendor/nova-google2fa')
            ], 'views');
        }

        $this->app->booted(function () {
            $this->routes();
        });
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'nova-google2fa');
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova', Authorize::class])
            ->prefix('los/2fa')
            ->group(__DIR__ . '/../routes/api.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/google2fa.php', 'google2fa');
    }
}
