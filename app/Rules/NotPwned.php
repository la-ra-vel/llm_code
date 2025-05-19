<?php
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Http;

class NotPwned implements Rule
{
    public function passes($attribute, $value)
    {
        // Use Have I Been Pwned API to check the password
        $hash = sha1($value);
        $prefix = substr($hash, 0, 5);
        $suffix = substr($hash, 5);

        $response = Http::get("https://api.pwnedpasswords.com/range/{$prefix}");

        return !str_contains($response->body(), strtoupper($suffix));
    }

    public function message()
    {
        return 'The password you have entered has been found in a data breach. Please choose a different password.';
    }
}

