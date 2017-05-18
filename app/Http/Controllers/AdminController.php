<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;


class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isAdmin()) {
                abort(403, 'Unauthorized action.');
            }

            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.index');
    }

    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\Http\Response
     */
    public function userIndex()
    {
        $users = \App\User::all();

        return view('admin.user_index', [
            'users' => $users
        ]);
    }

    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\Http\Response
     */
    public function userDestroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.user_index');
    }
}
