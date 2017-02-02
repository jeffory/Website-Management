<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function messages()
    {
        return $this->hasMany(TicketMessage::class);
    }

    /**
     * Ticket is owned by a user.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Determine if this Ticket is owned by a particular user.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ownedBy(User $user)
    {
        return $this->user_id == $user->id;
    }

    /**
     * Determine if a Ticket is open.
     *
     * @return boolean
     */
    public function isOpen()
    {
        return $this->status == 0;
    }
}
