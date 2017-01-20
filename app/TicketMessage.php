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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function username()
    {
        $this->user_id;
        $user = User::find($this->user_id);
        return $user['name'];
    }
}
