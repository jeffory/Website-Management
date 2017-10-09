<?php

namespace tests\Feature;

use App\Facades\WHMApi;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;


class WHMApiTest extends TestCase
{
    /** @test */
    public function it_can_retrieve_server_accounts()
    {
        WHMApi::fake();
        WHMApi::addFakeResponse(200, [], $this->response_file('accountList'));

        $accounts = WHMApi::accountList();

        $this->assertCount(2, $accounts);
        $this->objectHasAttribute("disklimit", $accounts[0]);
        $this->objectHasAttribute("domain", $accounts[0]);
        $this->objectHasAttribute("user", $accounts[0]);
    }

    // Helper function
    public function response_file($name)
    {
        return file_get_contents("./tests/JSON_Responses/{$name}.json");
    }
}

