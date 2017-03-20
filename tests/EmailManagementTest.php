<?php

use Modelizer\Selenium\SeleniumTestCase;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EmailManagementTest extends SeleniumTestCase
{
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testEmailCreation()
    {
        $user_password = 'password';

        $user = factory(App\User::class)->create([
                'password' => bcrypt($user_password),
                'has_server_access' => true
            ]);

        $email_username = 'testaccount01';
        $email_password = 'Password123';
        $email_password_2 = 'Password456';
        $email_domain = 'geckode.com.au';
        $email = $email_username. '@'. $email_domain;

        // Login, visit the email management
        $this->visit(route('login'))
             ->submitForm('form', [
                'email' => $user->email,
                'password' => $user_password
                ])
             ->wait(1)
             ->visit(route('server.show', [
                 'domain' => $email_domain
             ]));
        // Create a new email account
        $this->press('New account')
             ->type($email_username, 'username')
             ->type($email_password, 'password')
             ->type($email_password, 'password_confirmation')
             ->press('Create account')
             ->waitForElementsWithClass('status-successful', 8000)
             ->wait(8)
             ->see($email);

        // Change the password
        $this->findElement('', "//td[contains(text(), '". $email. "')]/parent::node()/td[4]/button[2]")
             ->click();
        $this->type($email_password_2, '#password_change_form input[name=password]')
             ->type($email_password_2, '#password_change_form input[name=password_confirmation]')
             ->findElement('', '//*[@id="password_change_form"]/div[5]/p[1]/button')
             ->click();
        $this->waitForElementsWithClass('status-successful', 8000)
             ->wait(8);

        // Delete the created account
        $this->findElement('', '//form[@id="delete_email_form"]//button/span[contains(text(), "Yes")]')
             ->click();
        $this->wait(3)
             ->dontSee($email);
    }
}
