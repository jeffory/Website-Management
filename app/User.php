<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User
 */
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Get all the tickets a User can view/edit.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function remoteServers()
    {
        return $this->belongsToMany(RemoteServer::class);
    }

    /**
     * Get all the tickets a User can view/edit.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function accessibleServers()
    {
        if ($this->is_admin) {
            return RemoteServer::all();
        }

        return $this->remoteServers;
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
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function myTickets($status = null)
    {
        $query = Ticket::select(['id', 'title', 'user_id', 'status', 'assigned_to', 'created_at', 'updated_at'])
                       ->with(['user' => function ($query) {
                            $query->select('id', 'name');
                       }]);

        if (!$this->isStaff()) {
            $query->where('user_id', $this->id);
        }

        if ($status !== null) {
            $query->where('status', $status);
        }

        return $query;
    }

    /**
     * Determine the count of the users viewable tickets.
     *
     * @return integer
     */
    public function myTicketCount()
    {
        if ($this->isStaff()) {
            return Ticket::where('status', 0)->count();
        }

        return $this->tickets()->where('status', 0)->count();
    }

    /**
     * Generate a user token.
     *
     * @return void
     */
    public function generateVerificationToken()
    {
        $this->verification_token = str_random(40);
        $this->save();
    }
}
