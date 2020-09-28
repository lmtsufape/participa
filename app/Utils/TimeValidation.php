<?php namespace App\Utils;

use Illuminate\Support\ServiceProvider;

class TimeValidation
{
    public function validate($attribute, $value, $parameters, $validator)
    {
        return $this->isValidate($attribute, $value);
    }

    protected function isValidate($attribute, $value)
    {
        return preg_match('/^([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/', $value, $matches);
    }
}