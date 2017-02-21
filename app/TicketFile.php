<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketFile extends Model
{
    protected $fillable = ['name', 'path', 'user_id'];

    public function message()
    {
        return $this->belongsTo(TicketMessage::class);
    }
}
