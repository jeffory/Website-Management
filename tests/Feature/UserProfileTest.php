<?php

namespace tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function guests_cant_access_profile_edit_page()
    {
        $this->withExceptionHandling()
            ->get(route('user.edit'))
            ->assertRedirect('/login');

        $this->withExceptionHandling()
            ->post(route('user.update'))
            ->assertRedirect('/login');
    }

    /** @test */
    function users_can_access_profile_edit_page()
    {
        $this->withExceptionHandling()
            ->signIn()
            ->get(route('user.edit'))
            ->assertSee(auth()->user()->name);
    }

    /** @test */
    function a_user_can_change_their_password_with_the_current_password()
    {
        $password = str_random();
        $user = create('App\User', ['password' => bcrypt($password)]);
        $new_password = str_random();

        $this->withExceptionHandling()
            ->signIn($user);

        $this->post(route('user.update'), [
            'name' => $user->name,
            'current_password' => str_random(),
            'password' => $new_password,
            'password_confirmation' => $new_password
        ])->assertSessionHasErrors('current_password');

        $this->assertFalse(Hash::check($new_password, $user->fresh()->password));

        $this->post(route('user.update'), [
            'name' => $user->name,
            'current_password' => $password,
            'password' => $new_password,
            'password_confirmation' => $new_password
        ]);

        $this->assertTrue(Hash::check($new_password, $user->fresh()->password));
    }

    /** @test */
    function a_user_can_change_just_their_name()
    {
        $this->withExceptionHandling()
            ->signIn()
            ->post(route('user.update'), [
                'name' => 'John Doe'
            ]);

        $this->assertEquals('John Doe', auth()->user()->name);
    }

    /** @test */
    function a_user_can_resend_their_verification_email()
    {
        Mail::fake();
        $this->withExceptionHandling()
            ->signIn(create('App\User', [
                'is_verified' => false
            ]));

        $this->get(route('user.sendVerification'));

        Mail::assertSent(\App\Mail\UserRegistrationEmail::class, function ($mail) {
            return $mail->hasTo(auth()->user()->email);
        });
    }

    /** @test */
    function a_user_cant_verify_without_token()
    {
        Mail::fake();
        $user = create('App\User', [
            'is_verified' => false
        ]);

        $this->assertFalse($user->is_verified);

        $this->withExceptionHandling()
            ->signIn($user)
            ->get(route('user.verify'));

        $this->assertFalse($user->fresh()->is_verified);

        $this->signIn($user)
            ->get(route('user.verify', str_random()));

        $this->assertFalse($user->fresh()->is_verified);
    }

    /** @test */
    function a_user_can_verify_their_account()
    {
        Mail::fake();
        $user = create('App\User', [
            'is_verified' => false
        ]);

        $this->assertFalse($user->is_verified);

        $this->signIn($user)
            ->get(route('user.verify', $user->verification_token));

        $this->assertTrue($user->fresh()->is_verified);
    }
}