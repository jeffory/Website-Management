<?php

namespace Tests\Browser;

use App\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UserSettingsTest extends DuskTestCase
{
    /** @test */
    public function a_user_can_change_the_password()
    {
        $old_password = '%t,r/:?;Sh&^y';
        $new_password = 'Yv_98CtgcthL';

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

        $this->assertTrue(Hash::check($new_password, $user->fresh()->password));
        $user->forceDelete();
    }

    /** @test */
    public function a_user_cannot_change_the_password_without_the_current_correct_password()
    {
        $user = factory(User::class)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/user/edit')
                ->type('current_password', 'Incorrect password')
                ->press('Update')
                ->waitFor('.help')
                ->assertSee('The current password entered is incorrect, please try again.');
        });

        $user->forceDelete();
    }
}
