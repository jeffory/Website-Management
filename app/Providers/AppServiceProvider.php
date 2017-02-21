<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        //
    }
}
