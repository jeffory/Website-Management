<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\TransferStats;
use Illuminate\Support\Facades\Log;

/**
 * WHMApi
 */
class WHMApi
{
    /**
     * CPanel host.
     * @var string
     */
    public $host;

    /**
     * CPanel username.
     * @var string
     */
    public $username;

    /**
     * GuzzleHTTP client.
     * @var GuzzleHttp|Client
     */
    public $client;

    /**
     * Last encountered error.
     * @var Array
     */
    public $last_error;

    /**
     * Guzzle middleware stack.
     * @var |GuzzleHttp|HandlerStack
     */
    public $stack;

    /**
     * Stores the last transactions for debugging.
     * @var Array
     */
    public $transaction_history = [];


    /**
     * Stores the last url for testing/debugging.
     * @var String
     */
    public $previous_url = '';

    /**
     *
     *
     * @return HandlerStack
     */
    protected function getHandlerStack()
    {
        $stack = HandlerStack::create();

        $stack->push(
            Middleware::history($this->transaction_history)
        );

        return $stack;
    }

    /**
     * Initialise a Guzzle client.
     */
    protected function initClient()
    {
        $this->host = config('cpanel.host');
        $this->username = config('cpanel.username');
        $access_hash = config('cpanel.password');

        $this->client = new Client([
            'allow_redirects' => true,
            'base_uri' => "https://{$this->host}/json-api/",
            'handler' => $this->getHandlerStack(),
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => "WHM {$this->username}:{$access_hash}"
            ],
            'timeout' => 10,
            'verify' => true,
            'query' => [
                'cpanel_jsonapi_user' => $this->username,
                'cpanel_jsonapi_apiversion' => 3
            ],
            'on_stats' => function (TransferStats $stats) use (&$url) {
                $this->previous_url = $stats->getEffectiveUri();
            }
        ]);
    }

    /**
     * List all the accounts on a server.
     *
     * WHM API v1 function. Documentation:
     * https://documentation.cpanel.net/display/SDK/WHM+API+1+Functions+-+listaccts
     *
     * @return object|boolean JSON data or FALSE on request failure.
     */
    public function accountList()
    {
        $accounts = $this->call(
            'listaccts',
            ['api.version' => 1]
        );


        if (!$accounts) {
            return false;
        }

        return $accounts->data->acct;
    }

    /**
     * Run a call on the server.
     *
     * @var string
     *
     * @return object|boolean JSON data or FALSE on request failure.
     */
    public function call($method, $data = [], $action = 'GET')
    {
        $action = strtoupper($action);

        $this->initClient();

        try {
            $response = $this->client->request($action, $method, [
                'query' => $data
            ]);
        } catch (ClientException $e) {
            if (isset($response) && $response->getStatusCode()) {
                $this->last_error['http_code'] = $response->getStatusCode();
            }

            $this->last_error['text'] = $e->getMessage();
            $this->last_error['method'] = $method;
            $this->last_error['method_data'] = $data;
            $this->last_error['method_action'] = $action;

            Log::error('CPanelAPI::call() failed', $this->last_error);
            return false;
        }

        if (isset($response) and $response->getStatusCode() == 200) {
            $body = $response->getBody()->getContents();

            try {
                $body = json_decode($body);
            } catch (Exception $e) {
                return $body;
            }

            return $body;
        }

        return false;
    }

    /**
     * Run a CPanel specific call on the server.
     *
     * Defaults to API version 2 unless specified.
     *
     * @param string $module CPanel module to query
     * @param string $function CPanel function to run
     * @param string $user CPanel username to run command under
     * @param string $data
     *
     * @return object|boolean JSON data or FALSE on request failure.
     */
    public function cpanelCall($module, $function, $user, $data = [])
    {
        $api_version = 2;

        if (isset($data['cpanel_jsonapi_apiversion'])) {
            $api_version = $data['cpanel_jsonapi_apiversion'];
            unset($data['cpanel_jsonapi_apiversion']);
        }

        return self::call(
            'cpanel',
            ['cpanel_jsonapi_module' => $module,
                'cpanel_jsonapi_func' => $function,
                'cpanel_jsonapi_apiversion' => $api_version,
                'cpanel_jsonapi_user' => $user,
            ] + $data
        );
    }

    /**
     * Create a new email account.
     *
     * CPanel UAPI function. Documentation:
     * https://documentation.cpanel.net/display/SDK/UAPI+Functions+-+Email%3A%3Aadd_pop
     *
     * @param string $cpanel_user CPanel user to run the command under
     * @param string $username new email account's username, eg. user
     * @param string $password new email account's password
     * @param string $domain new email account's domain
     * @param integer $quota new email account's quota
     *
     * @return object|boolean JSON data or FALSE on request failure.
     */
    public function emailCreateAccount($cpanel_user, $username, $password, $domain, $quota = 1024)
    {
        $data = self::cpanelCall(
            'Email',
            'add_pop',
            $cpanel_user,
            [
                'email' => $username,
                'password' => $password,
                'domain' => $domain,
                'quota' => $quota,
                'cpanel_jsonapi_apiversion' => 3
            ]
        );

        if (!$data) {
            return false;
        }

        return intval($data->result->status) == 1;
    }

    /**
     * Delete an email account.
     *
     * CPanel UAPI function. Documentation:
     * https://documentation.cpanel.net/display/SDK/UAPI+Functions+-+Email%3A%3Aadd_pop
     *
     * @param string $cpanel_user CPanel user to run the command under
     * @param string $username new email account's username, eg. user
     * @param string $domain new email account's domain
     *
     * @return object|boolean JSON data or FALSE on request failure.
     */
    public function emailDeleteAccount($cpanel_user, $username, $domain)
    {
        $data = self::cpanelCall(
            'Email',
            'delete_pop',
            $cpanel_user,
            [
                'email' => $username,
                'domain' => $domain,
                'cpanel_jsonapi_apiversion' => 3
            ]
        );

        if (!$data) {
            return false;
        }

        return intval($data->result->status) == 1;
    }

    /**
     * Create a new email account.
     *
     * CPanel UAPI function. Documentation:
     * https://documentation.cpanel.net/display/SDK/UAPI+Functions+-+Email%3A%3Aadd_pop
     *
     * @param string cpanel user to run the command under
     * @param string new email account's username, eg. user
     * @param string new email account's domain
     *
     * @return object|boolean JSON data or FALSE on request failure.
     */
    public function emailAccountExists($cpanel_user, $username, $domain)
    {
        $email_accounts = self::emailList($cpanel_user, $domain);

        foreach ($email_accounts as $index => $email) {
            if ($email->email == $username . '@' . $domain) {
                return true;
            }
        }

        return false;
    }

    /**
     * List all the email accounts for a domain.
     *
     * CPanel UAPI Function. Documentation:
     * https://documentation.cpanel.net/display/SDK/UAPI+Functions+-+Email%3A%3Alist_pops_with_disk
     *
     * @param string cpanel user to run the command under
     * @param string domain to list emails for
     *
     * @return object|boolean JSON data or FALSE on request failure.
     */
    public function emailList($cpanel_user, $domain)
    {
        $accounts = self::cpanelCall(
            'Email',
            'list_pops_with_disk',
            $cpanel_user,
            [
                'domain' => $domain,
                'cpanel_jsonapi_apiversion' => 3
            ]
        );

        if (!$accounts) {
            return false;
        }

        return $accounts->result->data;
    }

    /**
     * Return a password strength for a given password.
     *
     * CPanel v2 Function. Documentation:
     * https://documentation.cpanel.net/display/SDK/cPanel+API+2+Functions+-+PasswdStrength%3A%3Aget_password_strength
     *
     * @param string password to test strength on
     *
     * @return object|boolean JSON data or FALSE on request failure.
     */
    public function emailPasswordStrength($password)
    {
        $data = $this->cpanelCall(
            'PasswdStrength',
            'get_password_strength',
            $this->username,
            [
                'password' => $password
            ]
        )->cpanelresult->data[0]->strength;

        return intval($data);
    }

    /**
     * Verify an email account exists and password is as provided.
     *
     * CPanel UAPI Function. Documentation:
     * https://documentation.cpanel.net/display/SDK/UAPI+Functions+-+Email%3A%3Averify_password
     *
     * @param string cpanel user to run the command under
     * @param string email address
     * @param string password
     *
     * @return object|boolean JSON data or FALSE on request failure.
     */
    public function emailVerifyAccount($cpanel_user, $email, $password)
    {
        $data = $this->cpanelCall(
            'Email',
            'verify_password',
            $cpanel_user,
            [
                'cpanel_jsonapi_apiversion' => 3,
                'email' => $email,
                'password' => $password
            ]
        );

        if (isset($data->result)) {
            if ($data->result->errors !== null) {
                return false;
            }

            return intval($data->result->data) == 1;
        }
    }

    /**
     * Change an email accounts password.
     *
     * CPanel UAPI Function. Documentation:
     * https://documentation.cpanel.net/display/SDK/UAPI+Functions+-+Email%3A%3Apasswd_pop
     *
     * @param string cpanel user to run the command under
     * @param string email account username
     * @param string password
     *
     * @return object|boolean JSON data or FALSE on request failure.
     */
    public function emailChangePassword($cpanel_user, $username, $password, $domain)
    {
        $data = $this->cpanelCall(
            'Email',
            'passwd_pop',
            $cpanel_user,
            [
                'cpanel_jsonapi_apiversion' => 3,
                'email' => $username,
                'password' => $password,
                'domain' => $domain,
            ]
        );

        if (isset($data->result)) {
            if ($data->result->errors !== null) {
                $this->last_error = $data->result;
                return false;
            }

            return intval($data->result->status) == 1;
        }
    }

    /**
     * Create a user session.
     *
     * CPanel UAPI Function. Documentation:
     * https://documentation.cpanel.net/display/SDK/WHM+API+1+Functions+-+create_user_session
     *
     * @param string cpanel user to run the command under
     * @param string email account username
     * @param string password
     *
     * @return array|boolean JSON data or FALSE on request failure.
     */
    public function createUserSession($cpanel_user, $service = 'cpaneld', $locale = 'en')
    {
        $data = $this->call(
            'create_user_session',
            [
                'user' => $cpanel_user,
                'api.version' => 1,
                'service' => $service,
                'locale' => $locale
            ]
        );

        if (isset($data->data)) {
            $data = $data->data;
            $data->status = true;

            return $data;
        } else {
            if (isset($data->metadata)) {
                return [
                    'status' => false,
                    'error' => $data->metadata->reason
                ];
            }
        }
    }

    public function getQueryURL()
    {
        return $this->previous_url;
    }

    /**
     * Debug function to echo last HTTP requests.
     */
    public function dumpRequests()
    {
        echo '<pre style="background-color:#fff; padding: 1em 2em;">';
        foreach ($this->transaction_history as $transaction) {
            echo (string)$transaction['request']->getBody();
        }
        echo '</pre>';
    }
}
