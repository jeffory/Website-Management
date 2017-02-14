<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\UserRegistration' => [
            'App\Listeners\SendUserRegistrationEmail'
        ],
        'App\Events\UserResendVerification' => [
            'App\Listeners\SendUserRegistrationEmail'
        ],
        'App\Events\TicketCreated' => [
            'App\Listeners\SendTicketCreationEmail'
        ],
        'App\Events\TicketAddMessage' => [],
        'App\Events\TicketDeleteMessage' => []
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
