<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TicketTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Create a new ticket, assert its existence.
     *
     * @return void
     */
    public function testBasicTicketCreation()
    {
        $user = App\User::where('email', 'keef05@gmail.com')->first();

        $title = 'This is my title';
        $message = 'This is my message';

        $this->actingAs($user)
             ->visit('tickets/create')
             ->see('Create a new Ticket')
             ->type($title, 'title')
             ->type($message, 'message')
             ->press('Create');

        $new_ticket = App\Ticket::where('user_id', $user->id)
                        ->orderBy('created_at', 'desc')
                        ->with('messages')
                        ->first();

        $this->assertEquals($title, $new_ticket->title);
        $this->assertEquals($message, $new_ticket->messages[0]->message);
    }

    /**
     * Test permissions between User and Tickets.
     *
     * @return void
     */
    public function testTicketPermissions()
    {
        $user1 = factory(App\User::class)->create();
        $user2 = factory(App\User::class)->create();

        $admin = App\User::where('email', 'keef05@gmail.com')->first();

        if (! $admin->isAdmin()) {
            $admin->is_admin = true;
            $admin->save();
        }

        $title = 'This is my title';
        $message = 'This is my message';

        $ticket = factory(App\Ticket::class)
            ->create([
                'title' => $title,
                'user_id' => $user1->id
            ]);

        $ticket->messages()->save(
            factory(App\TicketMessage::class)->make([
                'user_id' => $user1->id,
                'message' => $message
            ])
        );

        $this->actingAs($user1)
             ->visitRoute('tickets.show', ['id' => $ticket->id])
             ->see($title);

        $this->actingAs($user2)
             ->get('tickets/'. $ticket->id)
             ->assertResponseStatus(403);

        $this->assertTrue($admin->isAdmin());

        $this->actingAs($admin)
             ->visitRoute('tickets.show', ['id' => $ticket->id])
             ->see($title);
    }
}
