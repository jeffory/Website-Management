<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'verification_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get all the tickets a User can view/edit.
     *
     * @return boolean
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Determine if the user is an admin.
     *
     * @return boolean
     */
    public function isAdmin()
    {
        return (bool) $this->is_admin;
    }

    /**
     * Determine if the user is at least a staff member.
     *
     * @return boolean
     */
    public function isStaff()
    {
        return (bool) $this->is_staff || $this->isAdmin();
    }

    /**
     * Determine if the user has verified their email/account.
     *
     * @return boolean
     */
    public function isVerified()
    {
        return (bool) $this->is_verified;
    }

    /**
     * Determine if the user can access servers.
     *
     * @return boolean
     */
    public function hasServerAccess()
    {
        return (bool) $this->has_server_access;
    }

    /**
     * Retrieve a list of the users viewable tickets.
     *
     * @return collection
     */
    public function myTickets()
    {
        if ($this->isStaff()) {
            return Ticket::all();
        }
        return $this->tickets();
    }

    /**
     * Determine the count of the users viewable tickets.
     *
     * @return integer
     */
    public function myTicketCount()
    {
        if ($this->isStaff()) {
            return Ticket::count();
        }

        return $this->tickets()->count();
    }
}
