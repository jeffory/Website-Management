<?php

namespace App\Policies;

use App\User;
use App\Ticket;
use Illuminate\Auth\Access\HandlesAuthorization;

class TicketPolicy
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
     * Determine whether the user can view the ticket index.
     *
     * @param  \App\User  $user
     * @return boolean
     */
    public function index(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the ticket.
     *
     * @param  \App\User  $user
     * @param  \App\Ticket  $ticket
     * @return boolean
     */
    public function view(User $user, Ticket $ticket)
    {
        return $ticket->ownedBy($user);
    }

    /**
     * Determine whether the user can create tickets.
     *
     * @param  \App\User  $user
     * @return boolean
     */
    public function create(User $user)
    {
        return $user->isVerified();
    }

    /**
     * Determine whether the user can update the ticket.
     *
     * @param  \App\User  $user
     * @param  \App\Ticket  $ticket
     * @return boolean
     */
    public function update(User $user, Ticket $ticket)
    {
        return $ticket->ownedBy($user);
    }

    /**
     * Determine whether the user can delete the ticket.
     *
     * @param  \App\User  $user
     * @param  \App\Ticket  $ticket
     * @return boolean
     */
    public function delete(User $user, Ticket $ticket)
    {
        return $ticket->ownedBy($user);
    }
}
