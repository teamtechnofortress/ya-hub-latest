<?php

namespace App\Rules;
use App\Models\Setting;
use Illuminate\Contracts\Validation\Rule;

class MatchesSecretCode implements Rule
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
        $secret_code=Setting::get_setting('admin_key');
        return $value===$secret_code;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Secret code does not match.';
    }
}
