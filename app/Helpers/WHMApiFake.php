<?php
namespace App\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;

class WHMAPiFake extends WHMApi
{
    private $mock_responses = [];

    public function __construct()
    {
        $this->host = 'https://fake.com/json-api/';
        $this->username = 'test';
    }

    protected function getHandlerStack()
    {
        if (count($this->mock_responses) > 0) {
            $stack = MockHandler::createWithMiddleware($this->mock_responses);
        }

        $stack->push(
            Middleware::history($this->transaction_history)
        );

        return $stack;
    }

    public function addFakeResponse($status = 200, array $headers = [], $body = null, $version = '1.1', $reason = null)
    {
        array_push($this->mock_responses, new \GuzzleHttp\Psr7\Response($status, $headers, $body, $version, $reason));
    }
}