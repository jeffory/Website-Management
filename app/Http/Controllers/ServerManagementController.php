<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RemoteServer;

use Auth;

class ServerManagementController extends Controller
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
     * Display a listing of the websites the user has access to.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('index', RemoteServer::class);

        $user = Auth::user();
        $accounts = $user->assessibleServers();

        foreach ($accounts as $index => $account) {
            $account['disk-used-percentage'] = round(($account['disk-used'] / $account['disk-limit']) * 100, 2) . '%';
        }

        return view('server.index', [
            'accounts' => $accounts
            ]);
    }

    /**
     * Create a new email account on the server.
     *
     * @param \Illuminate\Http\Request
     *
     * @return \Illuminate\Http\Response
     */
    public function storeNewEmail(Request $request)
    {
        $response = RemoteServer::createEmail(
            $request->input('username'),
            $request->input('password'),
            $request->input('domain')
        );

        if ($response) {
            return [
                'status' => 1
            ];
        };
    }

    /**
     * Delete an email account on the server.
     *
     * @param \Illuminate\Http\Request
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteEmail(Request $request)
    {
        $email_parts = explode('@', $request->input('email'));
        $email_username = $email_parts[0];
        $domain = $email_parts[1];

        $response = RemoteServer::deleteEmail(
            $email_username,
            $domain
        );

        // if ($response) {
        //     return [
        //         'status' => 1
        //     ];
        // };

        return redirect()->route('server.show', [
            'domain' => $domain
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
    public function show($domain)
    {
        $server = new RemoteServer();
        $accounts = $server->emailList($domain);

        return view('server.email-index', [
            'accounts' => $accounts['accounts'],
            'domain' => $domain
            ]);
    }

    /**
     * Check the strength of a password.
     *
     * @return \Illuminate\Http\Response
     */
    public function emailPasswordStrength(Request $request)
    {
        if ($request->has('password')) {
            return (array) RemoteServer::emailPasswordStrength($request->input('password'));
        }
    }

    /**
     * Change an account password.
     *
     * @return \Illuminate\Http\Response
     */
    public function emailPasswordChange(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required|confirmed'
        ]);

        if ($request->has('password')) {
            return (array) RemoteServer::emailChangePassword($request->input('email'), $request->input('password'));
        }
    }

    /**
     * Change an account password.
     *
     * @return \Illuminate\Http\Response
     */
    public function emailPasswordCheck(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($request->has('password')) {
            return (array) RemoteServer::emailVerifyPassword(
                $request->input('email'),
                $request->input('password')
            );
        }
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
