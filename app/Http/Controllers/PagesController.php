<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    /**
     * Serve up any controller-less pages.
     *
     * @return \Illuminate\Http\Response
     */
    public function serve($page = '')
    {
        if ($page == '' || $page == 'index') {
            return view('pages.index');
        }

        return abort(404);
    }
}
