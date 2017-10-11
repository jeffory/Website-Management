<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Facades\WHMApi;
use Illuminate\Support\Facades\Log;

class RemoteServer extends Model
{
    /**
     * List of model fields acceptable to mass-assignment by Eloquent.
     *
     * @var array
     */
    protected $fillable = ['uid', 'domain', 'username', 'plan-name',
                           'max-emails', 'disk-used', 'disk-limit', 'active'];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'domain';
    }

    /**
     * The users that belong to the server.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    /**
     * Downloads a list of the current accounts on the web hosting server.
     *
     * Run as a artisan command or job, so error exceptions are allowed.
     *
     * @return void
     */
    public static function updateServerList()
    {
        $accounts = WHMApi::accountList();

        $whitelisted_servers = preg_split(
            '@(?:\s*,\s*|^\s*|\s*$)@',
            config('cpanel.server_whitelist'),
            null,
            PREG_SPLIT_NO_EMPTY
        );

        if (! $accounts) return false;

        foreach ($accounts as $index => $account) {
            if (!in_array($account->domain, $whitelisted_servers)) {
                Log::info('updateServerList() - Not in whitelist: '. $account->domain);
                continue;
            }

            RemoteServer::updateOrCreate(
                ['uid' => $account->uid],
                [
                    'uid' => $account->uid,
                    'domain' => $account->domain,
                    'username'  => $account->user,
                    'plan-name' => $account->plan,
                    'max-emails' => $account->maxpop,
                    'disk-used' => preg_replace('[\D]', '', $account->diskused),
                    'disk-limit' => preg_replace('[\D]', '', $account->disklimit),
                    'active' => ($account->suspended == 0)
                ]
            );
        }
    }

    /**
     * Checks if a user is authorised with the server.
     *
     * @return boolean
     */
    public function hasAuthorisedUser(User $user)
    {
        foreach ($this->users()->get() as $attached_user) {
            if ($user->id === $attached_user->id) {
                return true;
            }
        }

        return false;
    }

    /**
     * Authorises a user to use server.
     *
     * @return void
     */
    public function addAuthorisedUser(User $user)
    {
        if (! $this->hasAuthorisedUser($user)) {
            $this->users()->attach($user);
        }
    }

     /**
     * Remove authorisation of user to use server.
     *
     * @return boolean
     */
    public function removeAuthorisedUser(User $user)
    {
        if ($this->hasAuthorisedUser($user)) {
            return $this->users()->detach($user);
        }
    }

    /**
     * Create an email account.
     *
     * @param string $username email username, ie. user
     * @param string $password account password
     * @param string $quota account quota in MB
     *
     * @return boolean|array
     */
    public function createEmail($username, $password, $quota = 2048)
    {
        $result = WHMApi::emailCreateAccount(
            $this->username,
                $username,
                $password,
                $this->domain,
                $quota
        );

        return $result;
    }

    /**
     * Delete an email account.
     *
     * @param string $email_username email username, ie. user
     * @param string $domain domain
     *
     * @return boolean|array
     */
    public function deleteEmail($email)
    {
        list($username, $domain) = explode('@', $email);

        // Trying to change an email outside of the server's scope.
        if ($domain !== $this->domain) {
            abort(400);
        }

        return WHMApi::emailDeleteAccount($this->username, $username, $this->domain);
    }

    /**
     * Downloads a list of the current emails for a given domain.
     *
     * @return array
     */
    public function emailList()
    {
        $email_accounts = WHMApi::emailList($this->username, $this->domain);

        if (!$email_accounts) {
            return false;
        }

        return [
            'domain' => $this->domain,
            'username' => $this->username,
            'accounts' => $email_accounts
        ];
    }

    /**
     * Retrieve the strength of a email password.
     *
     * @param array
     * @return array
     */
    public function emailPasswordStrength($password)
    {
        return [
            'strength' => WHMApi::emailPasswordStrength($password)
        ];
    }

    /**
     * Change an email accounts password.
     *
     * @param array
     * @return array
     */
    public function emailChangePassword($email, $password)
    {
        list($username, $domain) = explode('@', $email);

        // Trying to change an email outside of the server's scope.
        if ($domain !== $this->domain) {
            abort(400);
        }

        return [
            'status' => WHMApi::emailChangePassword($this->username, $username, $password, $this->domain)
        ];
    }


    /**
     * Verify the email/password of an account.
     *
     * @param $email
     * @param $password
     * @return array
     */
    public function emailVerifyPassword($email, $password)
    {
        return [
            'status' => WHMApi::emailVerifyAccount($this->username, $email, $password)
        ];
    }

    /**
     * Create a logged in cpanel session and return the URL.
     *
     * @param string $username email username, ie. user
     * @param string $password account password
     * @param string $quota account quota in MB
     *
     * @return boolean|array
     */
    public function createCpanelSession($username)
    {
        $result = WHMApi::createUserSession($this->username);

        return $result;
    }
}
