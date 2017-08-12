<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

use Tests\Browser\Pages\RemoteServerEmail;

use App\User;

class RemoteServerEmailTest extends DuskTestCase
{
    /**
     * Test creation of an email account.
     *
     * @return void
     */
    public function testEmailAccountCreation()
    {
        $user = User::where('email', 'keef05@gmail.com')->first();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit(new RemoteServerEmail('geckode.com.au'))
                    ->create('test_account01', 'Password123')
                    ->delete('test_account01@geckode.com.au');
        });
    }

    /**
     * Test changing the password of an email account.
     *
     * @return void
     */
    public function testEmailAccountPasswordChange()
    {
        $user = User::where('email', 'keef05@gmail.com')->first();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit(new RemoteServerEmail('geckode.com.au'))
                    ->create('test_account01', 'Password123')
                    ->changePassword('test_account01@geckode.com.au', 'Password123!!')
                    ->checkPassword('test_account01@geckode.com.au', 'Password123!!')
                    ->delete('test_account01@geckode.com.au');
        });
    }
}
