<?php

namespace App\Rules;

use App\TypeSponsoring;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidSponsoringType implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $validTypes = array_column(TypeSponsoring::cases(), 'value');
        
        if (!in_array($value, $validTypes)) {
            $fail('Le type de sponsoring sélectionné n\'est pas valide.');
        }
    }
}
