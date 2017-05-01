<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use Tests\Browser\Pages\Registration;
use Tests\Browser\Pages\Login;
use Tests\Browser\Pages\Logout;
use Tests\Browser\Pages\AccountVerification;

use App\User;

class UserCreationTest extends DuskTestCase
{
    /**
     * Create a new User through registration form.
     *
     * @return void
     */
    public function testUserCreation()
    {
        $user = factory(User::class)->make();
        $password = 'password';

        $this->browse(function (Browser $browser) use ($user, $password) {
            $browser->visit(new Registration)
                    ->registration($user->name, $user->email, $password);
        });

        $user = User::where('email', '=', $user->email)
                    ->firstOrFail();

        $user->forceDelete();
    }

    /**
     * Tests verification of a new user.
     *
     * @return void
     */
    public function testAccountVerification()
    {
        $user = factory(User::class)->create([
            'is_verified' => false
        ]);

        $user->generateVerificationToken();
        $this->assertNotNull($user->verification_token);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit(new AccountVerification)
                    ->verifyByUser($user);

            $browser->visit(new Logout);
        });

        $user = $user->fresh();
        $this->assertTrue((bool) $user->is_verified);
        $user->forceDelete();
    }

    /**
     * Login using.
     *
     * @return void
     */
    public function testLogin()
    {
        $password = 'password';

        $user = factory(User::class)->create([
            'password' => bcrypt($password)
        ]);

        $this->browse(function (Browser $browser) use ($user, $password) {
            $browser->visit(new Login)
                    ->login($user->email, $password);
        });

        $user->forceDelete();
    }
}
