<?php

use Illuminate\Database\Seeder;

class TicketsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ticket = factory(App\Ticket::class)->create();

        $ticketMessage = factory(App\TicketMessage::class, 5)->create([
            'ticket_id' => $ticket->id
        ]);
    }
}
