<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Facades\WHMApi;

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
     * List of domains to never be stored or be manipulated by the system.
     *
     * @var array
     */
    protected static $ignore_servers = [
        '***REMOVED***',
        '***REMOVED***',
        '***REMOVED***',
        '***REMOVED***',
        '***REMOVED***',
        '***REMOVED***',
        '***REMOVED***',
        '***REMOVED***',
        '***REMOVED***'
    ];

    /**
     * The users that belong to the server.
     *
     * @return Illuminate\Database\Eloquent\Relations\belongsToMany
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

        foreach ($accounts as $index => $account) {
            if (in_array($account->domain, RemoteServer::$ignore_servers)) {
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
        foreach ($this->users as $current_user) {
            if ($user->id === $current_user->id) {
                return true;
            }
        }

        return false;
    }

    /**
     * Authorises a user to use server.
     *
     * @return boolean
     */
    public function addAuthorisedUser(User $user)
    {
        if (! $this->hasAuthorisedUser($user)) {
            return $this->users()->attach($user);
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
     * Searches the database for the user belonging to a domain.
     *
     * CPanel's API requires a matching domain and user to perform most commands.
     *
     * @param string $domain the domain to lookup
     *
     * @return string the username associated with the domain.
     *                FALSE if domain is non-existent.
     */
    public static function getDomainUsername($domain)
    {
        $server = RemoteServer::where('domain', $domain)->first();

        if ($server) {
            return $server->username;
        }

        return false;
    }

    /**
     * Create an email account.
     *
     * @param string email username, ie. user
     * @param string account password
     * @param string domain
     * @param string account quota in MB
     *
     * @return boolean|array
     */
    public static function createEmail($email, $password, $domain, $quota = 2048)
    {
        $cpanel_user = self::getDomainUsername($domain);
        $result = WHMApi::emailCreateAccount($cpanel_user, $email, $password, $domain, $quota);

        return $result;
    }

    /**
     * Delete an email account.
     *
     * @param string email username, ie. user
     * @param string domain
     *
     * @return boolean|array
     */
    public static function deleteEmail($email_username, $domain)
    {
        $cpanel_user = self::getDomainUsername($domain);
        $result = WHMApi::emailDeleteAccount($cpanel_user, $email_username, $domain);

        return $result;
    }

    /**
     * Downloads a list of the current emails for a given domain.
     *
     * @param string the domain's email accounts to look up
     *
     * @return array
     */
    public function emailList($domain)
    {
        $username = $this->getDomainUsername($domain);

        return [
            'domain' => $domain,
            'username' => $username,
            'accounts' => WHMApi::emailList($username, $domain)
        ];
    }

    /**
     * Retrieve the strength of a email password.
     *
     * @param array
     */
    public static function emailPasswordStrength($password)
    {
        return [
            'strength' => WHMApi::emailPasswordStrength($password)
        ];
    }

    /**
     * Change an email accounts password.
     *
     * @param array
     */
    public static function emailChangePassword($email, $password)
    {
        list($username, $domain) = explode('@', $email);
        $cpanel_user = self::getDomainUsername($domain);

        return [
            'status' => WHMApi::emailChangePassword($cpanel_user, $username, $password, $domain)
        ];
    }

    /**
     * Check an email accounts password.
     *
     * @param array
     */
    public static function emailVerifyPassword($email, $password)
    {
        list($username, $domain) = explode('@', $email);
        $cpanel_user = self::getDomainUsername($domain);

        return [
            'status' => WHMApi::emailVerifyAccount($cpanel_user, $email, $password)
        ];
    }
}
