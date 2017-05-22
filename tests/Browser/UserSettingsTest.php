<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use App\User;

class UserSettingsTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testPasswordChange()
    {
        $old_password = '%t,r/:?;Sh&^y---U$hZ6f5,ZSywSUXgSZPqeW~`';
        $new_password = 'Yv/98C"`^cthL`-7sjh3dp#d9Z+BBZX;v+K@w4:s';

        $user = factory(User::class)->create([
            'password' => bcrypt($old_password)
        ]);

        $this->browse(function (Browser $browser) use ($user, $old_password, $new_password) {
            $browser->loginAs($user)
                    ->visit('/user/edit')
                    ->type('current_password', $old_password)
                    ->type('password', $new_password)
                    ->type('password_confirmation', $new_password)
                    ->press('Update')
                    ->waitFor('.notification');
        });

        $user->fresh();
        $this->assertTrue(Hash::check($new_password, $user->password));
        $user->forceDelete();
    }
}
