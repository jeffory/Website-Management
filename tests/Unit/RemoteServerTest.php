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

        $this->assertCount(0,  RemoteServer::all());

        WHMApi::addFakeResponse(200, [], fake_http_response('accountList'));
        config(['cpanel.server_whitelist' => 'example.com']);
        RemoteServer::updateServerList();
    }

    /** @test */
    public function it_can_retrieve_and_update_the_server_accounts()
    {
        $this->assertCount(1,  RemoteServer::all());
    }

    /** @test */
    public function it_can_filter_retrieved_server_accounts()
    {
        WHMApi::addFakeResponse(200, [], fake_http_response('accountList'));
        config(['cpanel.server_whitelist' => 'example.com, example2.com']);

        RemoteServer::updateServerList();
        $this->assertCount(2,  RemoteServer::all());
    }

    /** @test */
    public function it_can_authorise_and_deauthorise_users_to_servers()
    {
        WHMApi::addFakeResponse(200, [], fake_http_response('accountList'));
        RemoteServer::updateServerList();

        $server = RemoteServer::first();
        $user = create('App\User');

        $this->assertFalse($server->hasAuthorisedUser($user));

        $server->addAuthorisedUser($user);
        $this->assertTrue($server->fresh()->hasAuthorisedUser($user));

        $server->removeAuthorisedUser($user);
        $this->assertFalse($server->fresh()->hasAuthorisedUser($user));
    }

    /** @test */
    public function it_can_create_a_new_email_account()
    {
        $server = RemoteServer::first();

        WHMApi::addFakeResponse(200, [], fake_http_response('emailNew'));

        $response = $server->createEmail('john', str_random(20));
        $this->assertTrue($response);

        WHMApi::addFakeResponse(200, [], fake_http_response('bad-emailNew'));

        $response = $server->createEmail('^^^^', str_random(20));
        $this->assertFalse($response);
    }

    /** @test */
    public function it_can_retrieve_email_accounts_from_a_domain()
    {
        $server = RemoteServer::first();

        WHMApi::addFakeResponse(200, [], fake_http_response('emailList'));

        $emails = $server->emailList();

        $this->assertArrayHasKey('accounts', $emails);
        $this->assertCount(3, $emails['accounts']);
        $this->assertObjectHasAttribute('email', $emails['accounts'][0]);
        $this->assertObjectHasAttribute('domain', $emails['accounts'][0]);
        $this->assertObjectHasAttribute('user', $emails['accounts'][0]);
    }

    /** @test */
    public function it_can_handle_bad_responses_on_email_account_retrieval_gracefully()
    {
        WHMApi::addFakeResponse(400);

        $server = RemoteServer::first();
        $emails = $server->emailList();

        $this->assertFalse($emails);
    }

    /** @test */
    public function it_can_delete_email_accounts()
    {
        $server = RemoteServer::first();

        WHMApi::addFakeResponse(200, [], fake_http_response('emailDelete'));

        $response = $server->deleteEmail('john@example.com');

        $this->assertTrue($response);
    }

    /** @test */
    public function it_cannot_delete_email_accounts_outside_of_domain_scope()
    {
        $server = RemoteServer::first();

        // This should throw a 400 error, that needs to be caught.
        $this->withExceptionHandling()
            ->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);

        $server->deleteEmail('john@bad_domain.com');
    }

    /** @test */
    public function it_can_retrieve_calculated_email_password_strength()
    {
        $server = RemoteServer::first();

        WHMApi::addFakeResponse(200, [], fake_http_response('passwordStrength-34'));
        $response = $server->emailPasswordStrength('test');

        $this->assertEquals(34, $response['strength']);

        WHMApi::addFakeResponse(200, [], fake_http_response('passwordStrength-55'));
        $response = $server->emailPasswordStrength('test');

        $this->assertEquals(55, $response['strength']);
    }

    /** @test */
    public function it_can_verify_an_email_account()
    {
        $server = RemoteServer::first();

        WHMApi::addFakeResponse(200, [], fake_http_response('verifyPassword'));
        $response = $server->emailVerifyPassword('test@example.com', str_random());

        $this->assertTrue($response['status']);

        WHMApi::addFakeResponse(200, [], fake_http_response('badAccount-verifyPassword'));
        $response = $server->emailVerifyPassword('test@example.com', str_random());

        $this->assertFalse($response['status']);
    }

    /** @test */
    public function it_can_change_an_email_password()
    {
        $server = RemoteServer::first();

        WHMApi::addFakeResponse(200, [], fake_http_response('emailPasswordChange'));
        $response = $server->emailChangePassword('test@example.com', str_random());

        $this->assertTrue($response['status']);

        WHMApi::addFakeResponse(200, [], fake_http_response('bad-emailPasswordChange'));
        $response = $server->emailChangePassword('test@example.com', str_random());

        $this->assertFalse($response['status']);
    }
}
