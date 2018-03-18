<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\UserResendVerification;
use App\Facades\Flash;
use Auth;
use App\User;

class UserController extends Controller
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
     * Get a list of current users
     *
     * @return User|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->authorize('index', auth()->user());

        $query = \App\User::select(['id', 'name']);

        $search_query = $request->input('query');
        $search_limit = $request->has('limit') ? intval($request->input('limit')) : 25;

        if ($search_query) {
            $query = $query->where('name', 'like', $search_query. '%');
        }

        return $query
            ->take($search_limit)
            ->get();
    }

    /**
     * Display a form for changing current user details.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        return view('user.edit', [
            'user' => Auth::user()
        ]);
    }

    /**
     * Update the current users data.
     *
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $this->validate($request, [
            'name' => 'required|max:255',
            'current_password' => 'required_with:password|current_password:'. $user->password,
            'password' => 'confirmed'
        ]);

        $user->name = $request->name;

        if ($request->has('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        Flash::set('Details updated.', 'success');

        return view('user.edit', [
            'user' => $user
        ]);
    }

    /**
     * Send a verification email to a requested user.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
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
     * @param string $token
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function verifyUserByToken($token = '')
    {
        if ($token == '') {
            return redirect('/');
        }

        $user = User::where('verification_token', '=', $token)->firstOrFail();

        $user->is_verified = true;
        $user->verification_token = null;
        $user->save();
        
        Flash::set('Email verified successfully.', 'success');

        return redirect()->route('home');
    }
}
