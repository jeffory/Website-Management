<?php

namespace Tests\Unit;

use App\RemoteServer;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Facades\WHMApi;

class RemoteServerTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp()
    {
        parent::setUp();
        WHMApi::fake();
        WHMApi::addFakeResponse(200, [], fake_http_response('accountList'));

        $this->assertCount(0,  RemoteServer::all());
        config(['cpanel.server_whitelist' => 'example.com, example2.com']);
    }

    /** @test */
    public function it_can_retrieve_and_update_the_server_accounts()
    {
        RemoteServer::updateServerList();
        $this->assertCount(2,  RemoteServer::all());
    }

    /** @test */
    public function it_can_filter_retrieved_server_accounts()
    {
        config(['cpanel.server_whitelist' => 'example2.com']);

        RemoteServer::updateServerList();
        $this->assertCount(1,  RemoteServer::all());
    }

    /** @test */
    public function it_can_authorise_and_deauthorise_users_to_servers()
    {
        RemoteServer::updateServerList();

        $server = RemoteServer::first();
        $user = create('App\User');

        $this->assertFalse($server->hasAuthorisedUser($user));

        $server->addAuthorisedUser($user);
        $this->assertTrue($server->fresh()->hasAuthorisedUser($user));

        $server->removeAuthorisedUser($user);
        $this->assertFalse($server->fresh()->hasAuthorisedUser($user));
    }

}
