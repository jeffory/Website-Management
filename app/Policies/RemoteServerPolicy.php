<?php

namespace App\Policies;

use App\User;
use App\RemoteUser;

use Illuminate\Auth\Access\HandlesAuthorization;

class RemoteServerPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        // Reject early - even an admin user specifically needs server access.
        if (! $user->hasServerAccess()) {
            return false;
        }

        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the server manangement index.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function index(User $user)
    {
        return $user->hasServerAccess();
    }

    /**
     * Determine whether the user can view the server.
     *
     * @param  \App\User  $user
     * @param  \App\RemoteServer  $server
     * @return mixed
     */
    public function view(User $user, RemoteServer $server)
    {
        
    }

    /**
     * Determine whether the user can create servers.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        
    }

    /**
     * Determine whether the user can update the server.
     *
     * @param  \App\User  $user
     * @param  \App\RemoteServer  $server
     * @return mixed
     */
    public function update(User $user, RemoteServer $server)
    {
        
    }

    /**
     * Determine whether the user can delete the ???.
     *
     * @param  \App\User  $user
     * @param  \App\RemoteServer  $server
     * @return mixed
     */
    public function delete(User $user, RemoteServer $server)
    {
        
    }
}
