<?php

namespace App\Http\Controllers;

use App\RemoteServer;
use Illuminate\Http\Request;
use App\Facades\Flash;

use Auth;

class ServerEmailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\RemoteServer  $remoteServer
     * @return \Illuminate\Http\Response
     */
    public function index(RemoteServer $remoteServer)
    {
        $this->authorize('view', $remoteServer);

        $accounts = $remoteServer->emailList();

        return view('server.email-index', [
            'accounts' => $accounts['accounts'],
            'domain' =>  $remoteServer->domain
        ]);
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
     * @param  \App\RemoteServer  $remoteServer
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, RemoteServer $remoteServer)
    {
        $this->authorize('create', $remoteServer);

        $response = $remoteServer->createEmail(
            $request->input('username'),
            $request->input('password')
        );

        Flash::set('Email created.');

        // TODO: A return...
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\RemoteServer  $remoteServer
     * @return \Illuminate\Http\Response
     */
    public function show(RemoteServer $remoteServer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\RemoteServer  $remoteServer
     * @return \Illuminate\Http\Response
     */
    public function edit(RemoteServer $remoteServer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\RemoteServer  $remoteServer
     * @return array
     */
    public function update(Request $request, RemoteServer $remoteServer, $email)
    {
        $this->authorize('update', $remoteServer);

        $this->validate($request, [
            'password' => 'required|confirmed'
        ]);

        if ($request->has('password')) {
            return (array) $remoteServer->emailChangePassword(
                $request->input('email'),
                $request->input('password')
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\RemoteServer  $remoteServer
     * @return \Illuminate\Http\Response
     */
    public function destroy(RemoteServer $remoteServer, $email)
    {
        $this->authorize('destroy', $remoteServer);

        $response = $remoteServer->deleteEmail($email);

        return redirect()->route('server_email.index', [
            'domain' => $remoteServer->domain
        ]);
    }

    /**
     * Confirm the account exists and the password is correct.
     *
     * @param Request      $request
     * @param RemoteServer $remoteServer
     * @param              $email
     * @return array
     */
    public function confirm(Request $request, RemoteServer $remoteServer, $email)
    {
        $this->authorize('update', $remoteServer);

        $this->validate($request, [
            'password' => 'required',
        ]);

        return (array) $remoteServer->emailVerifyPassword(
            $email,
            $request->input('password')
        );
    }

    /**
     * Check the strength of a password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\RemoteServer  $remoteServer
     * @return array
     */
    public function password_strength(Request $request, RemoteServer $remoteServer)
    {
    	$remoteServer = auth()->user()->accessibleServers()->first();

        $this->authorize('update', $remoteServer);

        $this->validate($request, [
            'password' => 'required'
        ]);

        return (array) $remoteServer->emailPasswordStrength(
            $request->input('password')
        );
    }
}
