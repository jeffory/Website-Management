<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ticket;
use Auth;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ticket_count = Ticket::where('user_id', Auth::user()->id)->count();

        return view('home', [
            'ticket_count' => $ticket_count
        ]);
    }
}
