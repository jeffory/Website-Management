<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketMessage extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function ticket()
    {
        return $this->hasOne(Ticket::class);
    }

    /**
     * Determine if this ticket message is owned by a particular user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * File relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function file()
    {
        return $this->hasMany(TicketFile::class);
    }

    /**
     * Get the ticket's owners name.
     *
     * @return string
     */
    public function username()
    {
        return $this->user()['name'];
    }
}
