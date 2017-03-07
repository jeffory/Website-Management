<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Facades\WHMApi;

class WHMApiTest extends TestCase
{
    /**
     * Test if we can get a list of email accounts.
     *
     * @return void
     */
    public function testEmailListing()
    {
        $cpanel_user = 'geckode';
        $cpanel_domain = 'geckode.com.au';
        $email_to_check_existence = 'no-reply@geckode.com.au';
        $email_exists = null;

        $email_accounts = WHMApi::emailList($cpanel_user, $cpanel_domain);

        $this->assertNotEmpty($email_accounts);

        foreach ($email_accounts as $index => $email) {
            if ($email->email == $email_to_check_existence) {
                $email_exists = true;
                break;
            }
        }

        $this->assertTrue($email_exists);
    }

    /**
     * Test if we can get a list of email accounts.
     *
     * @return void
     */
    public function testPasswordStrength()
    {
        $password = 'Password123';
        $password_strength_verification = 76;   // Pre-calculated value through Cpanel.

        $this->assertEquals(WHMApi::emailPasswordStrength($password), $password_strength_verification);
    }

    /**
     * Test if we can get a list of email accounts.
     *
     * @return void
     */
    public function testEmailAccountCreation()
    {
        $cpanel_user = 'geckode';
        $username = 'test';
        $password = env('APP_KEY');
        $domain = 'geckode.com.au';
        
        $this->assertTrue(WHMApi::emailAccountExists('geckode', 'no-reply', 'geckode.com.au'));

        if (WHMApi::emailAccountExists($cpanel_user, $username, $domain)) {
            $this->assertTrue(WHMApi::emailDeleteAccount($cpanel_user, $username, $domain));
        }

        $this->assertTrue(WHMApi::emailCreateAccount($cpanel_user, $username, $password, $domain));
    }

    /**
     * Test if we can get a list of email accounts.
     *
     * @return void
     */
    public function testPasswordVerification()
    {
        $cpanel_user = 'geckode';
        $email = 'test@geckode.com.au';
        $password = env('APP_KEY');

        $this->assertTrue(WHMApi::emailVerifyAccount($cpanel_user, $email, $password));
        $this->assertFalse(WHMApi::emailVerifyAccount($cpanel_user, $email, ''));
        $this->assertFalse(WHMApi::emailVerifyAccount($cpanel_user, $email, '243242'));
    }

    /**
     * Test if we can get a list of email accounts.
     *
     * @return void
     */
    public function testEmailAccountRemoval()
    {
        $cpanel_user = 'geckode';
        $username = 'test';
        $domain = 'geckode.com.au';
        
        $this->assertTrue(WHMApi::emailDeleteAccount($cpanel_user, $username, $domain));
    }
}
