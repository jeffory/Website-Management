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
     * @return boolean
     */
    public function has()
    {
        return $this->request->session()->has('flash.message');
    }

    /**
     * Return message.
     *
     * @return array|boolean
     */
    public function get()
    {
        if ($this->has()) {
            return [
                'message' => $this->request->session()->get('flash.message'),
                'level' => $this->request->session()->get('flash.level')
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
    public function set($message, $level = 'info')
    {
        $this->request->session()->flash('flash.message', $message);
        $this->request->session()->flash('flash.level', $level);
    }

    /**
     * Display flash message with Bulma framework styling.
     *
     * @return void
     */
    public function displayBulma($timeout = 5000)
    {
        $css_class = $this->get()['level'];

        if ($this->has()) {
            echo "<div class='notification is-{$css_class}' data-timeout='{$timeout}'>";
            echo "<button class='delete'></button>";
            echo $this->get()['message'];
            echo "</div>";
        }
    }
}
