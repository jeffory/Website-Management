<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Give admin and staff permission to every ticket.
     *
     * @param  \App\User  $user
     * @param  string $ability
     * @return boolean
     */
    public function before(User $user, $ability)
    {
        if ($user->isStaff() || $user->isAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can view a list of other users.
     *
     * @param  \App\User  $user
     * @return boolean
     */
    public function index(User $user)
    {
        return ($user->isStaff() || $user->isAdmin());
    }
}
