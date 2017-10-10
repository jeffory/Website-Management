<?php

namespace tests\Feature;

use App\Facades\WHMApi;
use App\RemoteServer;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ServerControllerTest extends TestCase
{
    use DatabaseMigrations;

    protected $servers;

    protected $authorised_user;

    protected function setUp()
    {
        parent::setUp();

        $this->servers = create('App\RemoteServer', [],3);

        $authorised_user = $this->authorised_user = create('App\User', [
            'is_admin' => true,
            'has_server_access' => true
        ]);

        $this->servers->each(function (RemoteServer $server) use ($authorised_user) {
            $server->addAuthorisedUser($authorised_user);
        });
    }

    /** @test */
    public function normal_users_cannot_access_server_commands()
    {
        $this->withExceptionHandling()
            ->signIn()
            ->get(route('server.index'))
            ->assertStatus(403);

        $this->withExceptionHandling()
            ->signIn()
            ->get(route('server.cpanel_redirect', $this->servers[0]->domain))
            ->assertStatus(403);
    }

    /** @test */
    public function not_even_admin_can_access_server_list_without_server_access_attribute()
    {
        $this->withExceptionHandling()
            ->signIn(create('App\User', ['is_admin' => true]))
            ->get(route('server.index'))
            ->assertStatus(403);

        $this->withExceptionHandling()
            ->signIn(create('App\User', ['is_admin' => true]))
            ->get(route('server.cpanel_redirect', $this->servers[0]->domain))
            ->assertStatus(403);
    }

    /** @test */
    public function admins_with_server_access_attribute_can_access_server_list()
    {
        $this->signIn($this->authorised_user);

        $response = $this->json('get', route('server.index'))
            ->json();

        $this->assertCount(3, $response);
    }

    /** @test */
    public function admins_with_server_access_attribute_can_log_into_cpanel()
    {
        $this->signIn($this->authorised_user);

        WHMApi::fake();
        WHMApi::addFakeResponse(200, [], fake_http_response('createUserSession'));

        $response = $this->json('get', route('server.cpanel_redirect', $this->servers[0]->domain))->json();

        $this->assertArrayHasKey('cpanel_url', $response);
    }
}