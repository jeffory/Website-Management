<?php

namespace App\Listeners;

use App\Events\UserRegistration;
use App\Events\UserResendVerification;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Support\Facades\Mail;
use App\Mail\UserRegistrationEmail;

class SendUserRegistrationEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserRegistration  $event
     * @return void
     */
    public function handle($event)
    {
        $user = $event->user;

        Mail::to($user->email)
            ->queue(new UserRegistrationEmail($user));
    }
}
