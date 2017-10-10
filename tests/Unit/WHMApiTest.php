<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Facades\WHMApi;

class WHMApiTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp()
    {
        parent::setUp();
        WHMApi::fake();
    }

    /** @test */
    public function it_does_escape_query_data()
    {
        WHMApi::addFakeResponse(200, [], $this->response_file('emailList'));

        WHMApi::emailList('user&cpanel_jsonapi_user=baduser', 'good.com&bad_action=bad.com');

        $uri = WHMApi::getQueryURL();
        $uri->assertHasQuery('cpanel_jsonapi_user', 'user&cpanel_jsonapi_user=baduser');
        $uri->assertHasQuery('domain', 'good.com&bad_action=bad.com');
        $uri->assertHasNoQuery('bad_action');
    }

    /** @test */
    public function it_can_retrieve_server_accounts()
    {
        WHMApi::addFakeResponse(200, [], $this->response_file('accountList'));

        $accounts = WHMApi::accountList();
        $uri = WHMApi::getQueryURL();

        $uri->assertPathIs('/listaccts');

        $this->assertCount(2, $accounts);
        $this->objectHasAttribute("disklimit", $accounts[0]);
        $this->objectHasAttribute("domain", $accounts[0]);
        $this->objectHasAttribute("user", $accounts[0]);
    }

    /** @test */
    public function it_can_retrieve_email_accounts()
    {
        WHMApi::addFakeResponse(200, [], $this->response_file('emailList'));

        $emails = WHMApi::emailList('user', 'example.com');

        $this->assertCount(3, $emails);
        $this->assertEquals('john@example.com', $emails[0]->email);
        $this->assertEquals('bob@example.com', $emails[1]->email);
        $this->assertEquals('jane@example.com', $emails[2]->email);
    }

    // Helper function
    public function response_file($name)
    {
        return file_get_contents("./tests/JSON_Responses/{$name}.json");
    }
}
