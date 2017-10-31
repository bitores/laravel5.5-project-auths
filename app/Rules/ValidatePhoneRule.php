<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidatePhoneRule implements Rule
{

    const PHONEREG = '/^1[34578][0-9]{9}$/';
    private $value;

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
        $this->value = $value;
        return (bool) preg_match(self::PHONEREG, $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "手机号格式不正确";
    }
}
