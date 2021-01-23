<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PreparationTimeRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $result = preg_grep('/^([0-5][0-9]):([0-5][0-9]):([0-5][0-9])$/', explode('\n', $value));

        if (count($result) == 0) return false;

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute format should be like: 00:30:59.';
    }
}
