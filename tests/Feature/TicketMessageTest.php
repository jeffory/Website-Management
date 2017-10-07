<?php


namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class TicketMessageTest extends TestCase
{
    use DatabaseMigrations;

    public $ticket;

    protected function setUp()
    {
        parent::setUp();

        $this->ticket = create('App\Ticket');
    }

    /** @test **/
    function a_user_can_only_post_messages_on_their_own_ticket()
    {
        $this->withExceptionHandling()
            ->post(route('ticket_message.store', $this->ticket), make('App\TicketMessage')->toArray())
            ->assertRedirect('/login');

        $this->signIn()
            ->post(route('ticket_message.store', $this->ticket), make('App\TicketMessage')->toArray())
            ->assertStatus(403);
    }

    /** @test * */
    function a_user_can_only_delete_message_on_their_own_ticket()
    {
        $this->withExceptionHandling()
            ->signIn()
            ->delete(route('ticket_message.destroy', [$this->ticket, $this->ticket->messages[0]]))
            ->assertStatus(403);
    }

    /** @test * */
    function staff_users_can_post_on_any_ticket()
    {
        $this->signIn(create('App\User', ['is_staff' => true]))
            ->post(route('ticket_message.store', $this->ticket), make('App\TicketMessage')->toArray())
            ->assertStatus(302);

        $this->assertCount(2, $this->ticket->fresh()->messages);
    }

    /** @test * */
    function staff_can_delete_any_ticket_message()
    {
        $this->assertCount(1, $this->ticket->messages);

        $this->signIn(create('App\User', ['is_staff' => true]))
            ->delete(route('ticket_message.destroy', [$this->ticket, $this->ticket->messages[0]]));

        $this->assertCount(0, $this->ticket->fresh()->messages);
    }
}