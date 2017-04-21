<?php

namespace App\Helpers;

use Illuminate\Http\Request;

/**
 * Flashes messages to the session.
 */
class FlashMessage
{
    protected $request;

    /**
     * Initialise the class.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Check if there is a message.
     *
     * @return void
     */
    public function has()
    {
        return $this->request->session()->has('flash.message');
    }

    /**
     * Return message.
     *
     * @return void
     */
    public function get()
    {
        if ($this->has()) {
            return [
                'message' => $this->request->session()->pull('flash.message'),
                'level' => $this->request->session()->pull('flash.level')
            ];
        } else {
            return false;
        }
    }

    /**
     * Set a flash message.
     *
     * @return void
     */
    public function set($message, $level = 0)
    {
        $this->request->session()->flash('flash.message', $message);
        $this->request->session()->flash('flash.level', $level);
    }
}
