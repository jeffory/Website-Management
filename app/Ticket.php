<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Iatstuti\Database\Support\CascadeSoftDeletes;

use App\TicketMessage;

class Ticket extends Model
{
    use SoftDeletes, CascadeSoftDeletes;

    protected $cascadeDeletes = ['messages', 'files'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Override the save method to create a message as well as a ticket.
     *
     * @param array $options
     */
    public function save(array $options = [])
    {
        // The message part is stored seperately from the ticket.
        if (isset($this->message)) {
            $ticket_message = $this->message;
            unset($this->message);
        }

        parent::save($options);

        // Add message after the save so there's a ticket id.
        if (isset($ticket_message)) {
            $this->addMessage($ticket_message);
        }
    }

    /**
     * Add a message to the current ticket.
     *
     * @param string $message Message text
     */
    public function addMessage($message, $status_change = null)
    {
        $ticket_message = new TicketMessage();
        $ticket_message->message = $message;

        if ($status_change) {
            $ticket_message->status_change = settype($status_change, 'integer');
        }

        $ticket_message->user_id = $this->user_id;
        $ticket_message->ticket_id = $this->id;
        $ticket_message->save();
    }

    /**
     * Return messages associated with the ticket.
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany(TicketMessage::class);
    }

    /**
     * Get any files associated with the ticket.
     *
     * Used for cascading deletions.
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files()
    {
        return $this->hasMany(TicketFile::class);
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

    /**
     * Determine if a Ticket is closed.
     *
     * @return boolean
     */
    public function isClosed()
    {
        return $this->status == 1;
    }
}
