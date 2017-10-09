<?php
namespace App\Facades;

use App\Helpers\WHMApiFake;
use Illuminate\Support\Facades\Facade;

class WHMApi extends Facade
{
    /**
     * Replace the bound instance with a fake.
     *
     * @return void
     */
    public static function fake()
    {
        static::swap(new WHMApiFake);
    }

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \App\Helpers\WHMApi::class;
    }
}
