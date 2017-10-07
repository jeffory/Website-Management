<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketFile extends Model
{
    protected $fillable = ['name', 'path', 'user_id'];

    /**
     * A ticket file belongs to a ticket.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function message()
    {
        return $this->belongsTo(TicketMessage::class);
    }
}
