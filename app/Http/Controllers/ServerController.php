<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Facades\Flash;
use App\RemoteServer;

use Auth;

class ServerController extends Controller
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
     * Display a listing of the servers the user has access to.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('index', RemoteServer::class);

        $user = Auth::user();
        $accounts = $user->accessibleServers();

        foreach ($accounts as $index => $account) {
            $account['disk-used-percentage'] = round(
                ($account['disk-used'] / $account['disk-limit']) * 100,
                        2) . '%';
        }

        return view('server.index', [
            'accounts' => $accounts
            ]);
    }

    /**
     * Start a cPanel session and redirect to it.
     *
     * @param RemoteServer $server
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function cpanelRedirect(RemoteServer $server)
    {
        $this->authorize('cpanel', RemoteServer::class);

        if (Auth::user()->isAdmin()) {
            $cpanel_session = ($server->createCpanelSession($server->domain));
        }

        if (isset($cpanel_session) && isset($cpanel_session->url)) {
            return redirect($cpanel_session->url);
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
