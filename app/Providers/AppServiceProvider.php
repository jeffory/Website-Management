<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Laravel\Dusk\DuskServiceProvider;
use App\TicketFile;
use App\Observers\TicketFileObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        TicketFile::observe(TicketFileObserver::class);

        if (env('FORCE_HTTPS', false) === true) {
            \URL::forceScheme('https');
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local', 'testing')) {
            $this->app->register(DuskServiceProvider::class);
        }
    }
}
