<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;

use Facebook\WebDriver\WebDriverBy;

class RemoteServerEmail extends BasePage
{
    /**
     * Stores the domain the test is dealing with.
     *
     * @var void
     */
    public $domain;

    /**
     * Set up the page.
     *
     * @return void
     */
    public function __construct($domain = null) {
        if ($domain) {
            $this->domain = $domain;
        }
    }

    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/client-area/management/email/'. $this->domain;
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
     * Create a new email account and assert its existence.
     *
     * @return void
     */
    public function create(Browser $browser, $username, $password)
    {
        $browser->waitUntil('window.app.$data.loaded')
                ->press('New account')
                ->whenAvailable('#new-email-modal', function ($modal) use ($username, $password) {
                    $modal->assertSee('New account')
                          ->type('input[name=username]', $username)
                          ->type('input[name=password]', $password)
                          ->type('input[name=password_confirmation]', $password)
                          ->press('Create account')
                          ->waitForText('Form submission sucessful.', 10);
                });

        // This will wait for the page to reload after the form is submitted...
        // Actually assert text so it shows up in test results.
        $browser->refresh()
                ->waitUntil('window.app.$data.loaded')
                ->assertSee($username. '@'. $this->domain);
    }

    /**
     * Change an email account's passwordl.
     *
     * @return void
     */
    public function changePassword(Browser $browser, $email, $password)
    {
        $browser->waitForText($email);

        $browser->driver->findElement(
            WebDriverBy::xpath("//td[contains(., '". $email. "')]/parent::tr//button[contains(.,'Options')]")
        )->click();

        $browser->whenAvailable('#email-options-modal', function ($modal) use ($password) {
            $modal->type('input[name=password]', $password)
                  ->type('input[name=password_confirmation]', $password)
                  ->press('Change password')
                  ->waitForText('Form submission sucessful.')
                  ->waitUntilMissing('.status-indicator');
        });
    }

    /**
     * Check an email account's passwordl.
     *
     * @return void
     */
    public function checkPassword(Browser $browser, $email, $password)
    {
        $browser->waitForText($email);

        $browser->driver->findElement(
            WebDriverBy::xpath("//td[contains(., '". $email. "')]/parent::tr//button[contains(.,'Options')]")
        )->click();

        $browser->waitFor('#email-options-modal')
                ->clickLink('Password Verification')
                ->whenAvailable('form[data-vv-scope="password_verify_form"]', function ($form) use ($password) {
                    $form->type('input[name=password]', $password)
                          ->press('Check account')
                          ->waitForText('Success, account verified.')
                          ->waitUntilMissing('.status-indicator')
                          ->press('Cancel')
                          ->waitUntilMissing('.modal');
                });
    }

    /**
     * Delete an email account and assert its removal.
     *
     * @return void
     */
    public function delete(Browser $browser, $email)
    {
        $browser->waitForText($email);

        $browser->driver->findElement(
            WebDriverBy::xpath("//td[contains(., '". $email. "')]/parent::tr//button[contains(.,'Delete')]")
        )->click();

        $browser->whenAvailable('#delete-email-modal', function ($modal) {
            $modal->press('Yes');
        });

        $browser->waitUntil('window.app.$data.loaded')
                ->assertDontSee($email);
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@element' => '#selector',
        ];
    }
}
