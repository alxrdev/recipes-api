<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class StepsRule implements Rule
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
        $steps = json_decode($value, true);

        if (!$steps) return false;

        foreach ($steps as $step) {
            if (!$this->validateStep($step)) return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute json should be like "[{"position": 1, "image":"theFileInputName", "content":"The content"}]".';
    }

    private function validateStep(array $step) : bool
    {
        if (!array_key_exists('position', $step) || !array_key_exists('image', $step) || !array_key_exists('content', $step)) {
            return false;
        }

        if (empty($step['position']) || empty($step['content'])) {
            return false;
        }

        if ($step['position'] < 1) {
            return false;
        }

        return true;
    }
}
