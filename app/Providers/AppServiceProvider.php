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
