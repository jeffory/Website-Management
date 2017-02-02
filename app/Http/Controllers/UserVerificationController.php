<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserVerificationController extends Controller
{
    /**
     * Verify the requested user.
     *
     * @param  int  $id
     * @return Response
     */
    public function __invoke($token)
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
