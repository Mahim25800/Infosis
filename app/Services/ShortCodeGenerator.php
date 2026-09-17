<?php

namespace App\Services;

use App\Models\Url;
use Illuminate\Support\Str;
use RuntimeException;

class ShortCodeGenerator
{
    /**
     * Make a random code and check the database before using it.
     *
     * A database unique index is still the final safety net. The loop simply
     * makes a collision extremely unlikely to reach the create operation.
     */
    public function generate(): string
    {
        for ($attempt = 0; $attempt < 10; $attempt++) {
            $code = Str::random(6);

            if (! Url::where('short_code', $code)->exists()) {
                return $code;
            }
        }

        throw new RuntimeException('Could not create a unique short code. Please try again.');
    }
}
