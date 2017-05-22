<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Laravel\Dusk\DuskServiceProvider;
use App\TicketFile;
use App\Observers\TicketFileObserver;
use Illuminate\Support\Facades\Hash;
use Validator;

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

        Validator::extend('current_password', function ($attribute, $value, $parameters, $validator) {
            return Hash::check($value, current($parameters));
        }, 'Password is incorrect, please try again.');
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
