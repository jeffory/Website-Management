<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use App\User;
use App\RemoteServer;

class RemoteServerPermissionsTest extends DuskTestCase
{
    /**
     * Test an unauthorised user can't access the servers.
     *
     * @return void
     */
    public function testUnauthorisedUserCannotAccessServers()
    {
        $user = factory(User::class)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/client-area/management/')
                    ->assertSee('This action is unauthorized');
        });

        $user->forceDelete();
    }

    /**
     * Test an unauthorised user can't access a server they are not associated with.
     *
     * @return void
     */
    public function testUserCannotAccessAnUnassociatedServers()
    {
        $domain = 'geckode.com.au';
        $user = factory(User::class)->create([
            'has_server_access' => true
        ]);

        $this->browse(function (Browser $browser) use ($user, $domain) {
            $browser->loginAs($user)
                ->visit("/client-area/management/email/{$domain}")
                ->assertSee('This action is unauthorized');
        });

        $user->forceDelete();
    }

    /**
     * Test an authorised user can access the servers.
     *
     * @return void
     */
    public function testAuthorisedUserCanAccessServers()
    {
        $server = RemoteServer::where('domain', 'geckode.com.au')->first();

        $user = factory(User::class)->create([
            'has_server_access' => true
        ]);
        
        $this->browse(function (Browser $browser) use ($user, $server) {
            // Should be able to see the servers interface, but should not be able to see
            // any servers the user is not explicitly associated with.
            $browser->loginAs($user)
                    ->visit('/client-area/management/')
                    ->assertDontSee('This action is unauthorized')
                    ->assertSee('Website management')
                    ->assertDontSeeIn('table.datatable', $server->domain);

            $server->addAuthorisedUser($user);

            $browser->visit('/client-area/management/')
                    ->assertSee('Website management')
                    ->assertSeeIn('table.datatable', $server->domain);
        });

        $user->forceDelete();
    }
}
