<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;

class Login extends BasePage
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/login';
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
     * Login user.
     *
     * @return void
     */
    public function login(Browser $browser, $email, $password)
    {
        $browser->visit(new Login)
                ->type('@input.email', $email)
                ->type('@input.password', $password)
                ->press('@input.submit')
                ->assertSee('Welcome back');
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@input.email'       => 'input[name=email]',
            '@input.password'    => 'input[name=password]',
            '@input.remember_me' => 'input[name=remember]',
            '@input.submit'      => 'button[type=submit]',
        ];
    }
}
