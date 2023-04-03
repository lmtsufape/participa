<?php

namespace App\Utils;

use Illuminate\Support\Arr;

class AfterTimeValidation
{
    /**
     * Valid after time.
     *
     * @param string $attribute
     * @param string $value
     * @param string $parameters
     *
     * @return bool
     */
    public function validate($attribute, $value, $parameters)
    {
        $this->requireParameterCount(1, $parameters, 'min_time');
        //dd($attribute);
        $valid = $this->validateTime($attribute, $value, $parameters);

        if (! $valid) {
            return false;
        }

        $other = Arr::get($this->data, $parameters[0]);

        $time = $this->validateTime($parameters[0], $other, []);

        if (! $time) {
            return false;
        }

        return $value > $other;
    }

    protected function validateTime($attribute, $value)
    {
        return preg_match('/^([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/', $value, $matches);
    }

    /**
     * Replace all params the AfterTime rule.
     *
     * @param string $message
     * @param string $attribute
     * @param string $rule
     * @param array  $parameters
     *
     * @return string
     */
    protected function replaceAfterTime($message, $attribute, $rule, $parameters)
    {
        $other = $this->getDisplayableAttribute($parameters[0]);

        return str_replace(':other', $other, $message);
    }
}
