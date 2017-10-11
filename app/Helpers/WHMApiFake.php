<?php

namespace App\Helpers;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\Assert as PHPUnit;

class WHMAPiFake extends WHMApi
{
    private $mock_responses = [];

    /**
     * WHMAPiFake constructor.
     */
    public function __construct()
    {
        config(['cpanel.host' => 'https://fake.com/json-api/']);
        config(['cpanel.username' => 'test']);
    }

    /**
     * Initialise any mock responses and add the history middleware to the Guzzle stack.
     *
     * @return \GuzzleHttp\HandlerStack
     */
    protected function getHandlerStack()
    {
        if (count($this->mock_responses) > 0) {
            // Retrieve and remove the first mock response
            $stack = MockHandler::createWithMiddleware([array_shift($this->mock_responses)]);
        }

        $stack->push(
            Middleware::history($this->transaction_history)
        );

        return $stack;
    }

    /**
     * Return a testable extended Uri object.
     *
     * @return UriTester
     */
    public function getQueryURL()
    {
        return new UriTester($this->previous_url);
    }

    /**
     * Add a fake response for the next request to send back.
     *
     * @param int $status
     * @param array $headers
     * @param null $body
     * @param string $version
     * @param null $reason
     */
    public function addFakeResponse($status = 200, array $headers = [], $body = null, $version = '1.1', $reason = null)
    {
        array_push($this->mock_responses, new \GuzzleHttp\Psr7\Response($status, $headers, $body, $version, $reason));
    }

    /**
     * Clear any fake responses queued up.
     */
    public function clearFakeResponses()
    {
        $this->mock_responses = [];
    }
}

class UriTester extends Uri
{
    /**
     * Assert if the URI has a matching path (without queries).
     *
     * @param $path
     */
    public function assertPathIs($path)
    {
        PHPUnit::assertEquals(
            substr($this->getPath(), -strlen($path)),
            $path,
            "The URI path '{$path}' does not match '{$this->getPath()}'."
        );
    }

    /**
     * Assert if the URI has a matching path (without queries).
     *
     * @param $path
     */
    public function assertQueryHas($key, $value = false)
    {
        parse_str($this->getQuery(), $query);
        PHPUnit::assertArrayHasKey($key, $query);

        if ($value !== false) {
            PHPUnit::assertEquals($value, $query[$key]);
        }
    }

    /**
     * Assert if the URI has matching query data.
     *
     * @param $path
     */
    public function assertHasQuery($key, $value = false)
    {
        parse_str($this->getQuery(), $query);
        PHPUnit::assertArrayHasKey($key, $query);

        if ($value !== false) {
            PHPUnit::assertEquals($value, $query[$key]);
        }
    }

    /**
     * Assert if the URI has a matching path (without queries).
     *
     * @param $path
     */
    public function assertHasNoQuery($key, $value = false)
    {
        parse_str($this->getQuery(), $query);
        PHPUnit::assertArrayNotHasKey($key, $query);

        if ($value !== false) {
            PHPUnit::assertNotEquals($value, $query[$key]);
        }
    }
}