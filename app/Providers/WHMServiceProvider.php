<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\WHMApi;

class WHMServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider should be deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Helpers/WHMApi', function () {
            return new WHMApi();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            WHMApi::class
        ];
    }
}
