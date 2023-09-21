<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

class ForbiddenFile implements ValidationRule
{

    private array $types = [];
    public function __construct(string ...$types)
    {
        $this->types = $types;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        $type = $value->getMimeType();

        if (in_array($type, $this->types)){
            $fail("File {$value->getClientOriginalName()} not allowed.");
        }

    }
}
