<?php

namespace App\Helpers;

use Illuminate\Http\Request;

/**
 * Flashes messages to the session.
 */
trait Recaptcha
{
    protected $verification_url = 'https://www.google.com/recaptcha/api/siteverify';

    public $error;

    static private function verifyToken($token)
    {
        $response = (new \GuzzleHttp\Client())->post(
            env('RECAPTCHA_API_URI'),
            [
                'form_params' => [
                    'secret' => env('RECAPTCHA_SECRET'),
                    'response' => $token,
                    'remoteip' => request()->ip()
                ]
            ]
        );

        if ($response->getStatusCode() == 200 && $response_json = json_decode($response->getBody())) {
            if (isset($response_json->success)) {
                return $response_json->success;
            }
        }

        return false;
    }

    /**
     * @param Request $request
     * @param $token
     * @return bool
     */
    static public function verify($token = '')
    {
        $token = $token ?: request()->input('g-recaptcha-response');

        return static::verifyToken($token);
    }
}
