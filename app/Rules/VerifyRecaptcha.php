<?php

namespace App\Rules;

use App\Helpers\Recaptcha;

class VerifyRecaptcha
{
    /**
     * Check if the Recaptcha token given is valid;
     *
     * @param $attribute
     * @param $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return Recaptcha::verify($value);
    }
}