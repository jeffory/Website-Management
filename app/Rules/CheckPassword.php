<?php

namespace App\Rules;

use Illuminate\Support\Facades\Hash;

class CheckPassword
{
    /**
     * Check if the given password is correct.
     *
     * @param $attribute
     * @param $value
     *
     * @return bool
     */
    public function passes($attribute, $value, $parameters)
    {
        return Hash::check($value, current($parameters));
    }
}