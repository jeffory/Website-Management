<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use App\User;

class UserResendVerification
{
    use SerializesModels;

    public $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
