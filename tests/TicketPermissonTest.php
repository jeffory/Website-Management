<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TicketPermissonTest extends TestCase
{
    use DatabaseTransactions;
    
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

        // This fails before assertion if visitRoute() is used.
        $response = $this->actingAs($user2)
                         ->call('GET', route('tickets.show', ['id' => $ticket->id]));

        $this->assertEquals(403, $response->status());

        $this->assertTrue($admin->isAdmin());

        $this->actingAs($admin)
             ->visitRoute('tickets.show', ['id' => $ticket->id])
             ->see($title);
    }
}
