<?php

namespace App\Http\Controllers;

use App\Mail\ContactUsEmail;
use Illuminate\Http\Request;
use App\Helpers\Recaptcha;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;

class PagesController extends Controller
{
    use Recaptcha;

    /**
     * Serve up any controller-less pages.
     *
     * @return \Illuminate\Http\Response
     */
    public function serve($page = '')
    {
        $page = ($page == '') ? 'pages.index' : 'pages.'. str_slug($page);

        if (View::exists($page)) {
            return view($page);
        }

        return abort(404);
    }

    /**
     * @param Request $request
     * @return Request
     */
    public function sendMessage(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email',
            'body' => 'required',
            'g-recaptcha-response' => 'recaptcha'
        ]);

        Mail::to('keef05@gmail.com')
            ->send(new ContactUsEmail(
                $request->only(['name', 'email', 'body'])
            ));

        return redirect()->route('contact_us')->with('contact_success', true);
    }
}
