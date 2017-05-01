<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;

class Registration extends BasePage
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/register';
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
     * Register a new user.
     *
     * @return void
     */
    public function registration(Browser $browser, $name, $email, $password)
    {
        $browser->visit(new Registration)
                ->type('@input.name', $name)
                ->type('@input.email', $email)
                ->type('@input.password', $password)
                ->type('@input.password_confirmation', $password)
                ->press('@input.submit')
                ->assertSee('Welcome back '. $name);
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@input.name'                  => 'input[name=name]',
            '@input.email'                 => 'input[name=email]',
            '@input.password'              => 'input[name=password]',
            '@input.password_confirmation' => 'input[name=password_confirmation]',
            '@input.submit'                => 'button[type=submit]',
        ];
    }
}
