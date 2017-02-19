<?php

use Modelizer\Selenium\SeleniumTestCase;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FileUploadTest extends SeleniumTestCase
{
    use DatabaseTransactions;

    /**
     * A Ticket file upload test.
     *
     * @return void
     */
    public function testTicketFileUpload()
    {
        $user_password = 'password';

        $user = factory(App\User::class)->create([
                'password' => bcrypt($user_password)
            ]);

        $ticket = factory(App\Ticket::class)->make([
            'message' => 'Hello world'
        ]);

        $file_path = base_path('tests/testfiles/cheese-01.jpg');
        $file_checksum = sha1_file($file_path);

        $this->visit(route('login'))
             ->submitForm('form', [
                'email' => $user->email,
                'password' => $user_password
                ])
             ->wait(1)
             ->visit(route('tickets.create'))
             ->see('Create a new ticket')
             ->type($ticket->title, 'title')
             ->type($ticket->message, 'message')
             ->execute([
                'script' => 'let el = document.querySelectorAll("input[type=file]")[0];'.
                            'el.style.display = "inline";'.
                            'el.dispatchEvent(new Event("change"));',
                'args'   => array()
             ]);

        $this->type($file_path, '#file-input')
             ->waitForElementsWithClass('file-list-item', 5000)
             ->wait(10)
             ->press('Create');
    }
}
