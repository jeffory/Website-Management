<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserCreationTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Create a new ticket, assert its existence.
     *
     * @return void
     */
    public function testUserCreationAndVerification()
    {
        $user = factory(App\User::class)->make();
        $password = 'password';

        $this->visit('register')
             ->see('Register')
             ->type($user->name, 'name')
             ->type($user->email, 'email')
             ->type($password, 'password')
             ->type($password, 'password_confirmation')
             ->press('Register')
             ->see('Welcome back '. $user->name);

        $user = App\User::where('email', '=', $user->email)->firstOrFail();

        $this->assertNotNull($user->verification_token);
        $this->visit('user/verify/'. $user->verification_token)
             ->see('Verification complete');

        $user = $user->fresh();
        $this->assertNull($user->verification_token);
        $this->assertTrue((bool) $user->is_verified);
    }
}
