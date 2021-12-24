<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NigeriaMobile implements Rule
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
        $withoutDialCode = preg_replace('/^\+?234/', '', $value);
        if (!preg_match('/^[0-9]{10,11}$/', $withoutDialCode)) {
            return false;
        }

        $first_three_digits = substr($withoutDialCode, 0, 3);

        $prefixes = [
            '070' => true,
            '080' => true,
            '090' => true,
            '081' => true,
            '091' => true,
        ];

        return isset($prefixes[$first_three_digits]);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid mobile number';
    }
}
