<?php

namespace Tests\Browser\Pages;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;

use App\User;

class AccountVerification extends BasePage
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/';
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
     * Verify a user.
     *
     * @return void
     */
    public function verifyByUser(Browser $browser, User $user)
    {
        $browser->visit('/user/verify/'. $user->verification_token)
                ->waitFor('.notification')
                ->assertSee('Email verified successfully');
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [];
    }
}
