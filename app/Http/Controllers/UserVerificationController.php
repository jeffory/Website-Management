<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\UserResendVerification;
use Auth;
use App\User;

class UserVerificationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Send a verification email to a requested user.
     *
     * @param  User  $user
     * @return view?
     */
    public function sendVerificationEmail()
    {
        $user = Auth::user();

        event(new UserResendVerification($user));

        return redirect('/');
    }

    /**
     * Verify the requested user.
     *
     * @param  str  $token
     * @return view?
     */
    public function verifyUserByToken($token)
    {
        if ($token == '') {
            return redirect('/');
        }

        $user = User::where('verification_token', '=', $token)->firstOrFail();

        $user->is_verified = true;
        $user->verification_token = null;
        $user->save();

        return view('user.verified');
    }
}
