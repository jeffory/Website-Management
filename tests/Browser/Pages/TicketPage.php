<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;

use Facebook\WebDriver\WebDriverBy;

use App\Ticket;

class TicketPage extends BasePage
{
    protected $ticket;

    public function __construct($id = null)
    {
        if ($id) {
            $this->ticket = Ticket::find($id);
        }
    }

    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        if (isset($this->ticket)) {
            return '/client-area/tickets/'. $this->ticket->id;
        }

        return '/client-area/tickets/create';
    }

    /**
     * Assert that the browser is on the page.
     *
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertPathIs($this->url());
    }

    /**
     * Create a new ticket and assert its existence.
     *
     * @return void
     */
    public function create(Browser $browser, $title, $message, $file = null)
    {
        $browser->visit(new TicketPage)
                ->type('@input.title', $title)
                ->type('@input.message', $message);

        if ($file !== null) {
            $browser->script('document.querySelector("#file-input").style.display = "block"');
            $browser->type('@input.file', $file);
        }

        // TODO: Actually wait for uploads to finish, not just a generic wait.
        sleep(1);
        $browser->press('@input.submit');

        $browser->assertSee($title)
                ->assertSee($message);

        if ($file !== null) {
            // $browser->assertSee("cheese-01.jpg");
        }
    }

    /**
     * Create a new ticket and assert its existence.
     *
     * @param \Laravel\Dusk\Browser $browser
     * @param integer $ticket_id id of the ticket
     * @return void
     */
    public function delete(Browser $browser, $ticket_id)
    {
        $browser->visit(new TicketPage($ticket_id))
                ->press('Options')
                ->clickLink('Delete Ticket')
                ->whenAvailable('.modal', function ($modal) {
                    $modal->press('Yes');
                });
    }

    /**
     * Add a message to a ticket.
     *
     * @return void
     */
    public function addMessage(Browser $browser, $ticket_id, $message, $toggle_status)
    {
        $browser->visit(new TicketPage($ticket_id))
                ->type('@input.message', $message);

        if ($toggle_status == true) {
            // NOTE: Webdriver tries to select elements that aren't visible even when using Vue.js and v-if
            $possible_elements = $browser->driver->findElements(
                WebDriverBy::xpath("//input[@name='status']")
            );

            foreach ($possible_elements as &$element) {
                if ($element->isDisplayed()) {
                    $element->click();
                    break;
                }
            }
        }
        
        $browser->press('Add new message')
                ->waitUntil('Object.keys(app.$refs["ticket-details"].$data["ticket_pending"]).length === 0')
                ->assertSee($message);
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@input.title' => 'input[name=title]',
            '@input.message' => 'textarea[name=message]',
            '@input.file' => 'input#file-input',
            '@input.submit' => 'button[type=submit]',
            '@button.options' => '.dropdown-menu button',

            '@input.toggle_status' => 'input[name=status]'
        ];
    }
}
