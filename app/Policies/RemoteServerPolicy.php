<?php

namespace App\Policies;

use App\User;
use App\RemoteServer;

use Illuminate\Auth\Access\HandlesAuthorization;

class RemoteServerPolicy
{
    use HandlesAuthorization;

    /**
     * At the bare minimum a user must have 'has_server_access' for any actions.
     * Users that are admin can access every server.
     *
     * @param \App\User $user
     * @param string $ability
     * @return mixed
     */
    public function before(User $user, $ability)
    {
        if (! $user->hasServerAccess()) {
            return false;
        }

        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the server management index.
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
        return $server->hasAuthorisedUser($user);
    }

	/**
	 * Determine whether the user can create emails on the server.
	 *
	 * @param  \App\User  $user
	 * @param  \App\RemoteServer  $server
	 * @return mixed
	 */
	public function create(User $user, RemoteServer $server)
	{
		return $server->hasAuthorisedUser($user);
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
        return $server->hasAuthorisedUser($user);
    }

    /**
     * Determine whether the user can access cPanel for the server.
     *
     * @param  \App\User  $user
     * @param  \App\RemoteServer  $server
     * @return mixed
     */
    public function cpanel(User $user, RemoteServer $server)
    {
        return $server->hasAuthorisedUser($user) && $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the ???.
     *
     * @param  \App\User  $user
     * @param  \App\RemoteServer  $server
     * @return mixed
     */
    public function destroy(User $user, RemoteServer $server)
    {
        return $server->hasAuthorisedUser($user);
    }
}
