<?php

namespace App\Rules;

use App\Models\Option;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class OptionUniqueName implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $options = Option::where('service_center_id', auth()->user()->serviceCenter->id)->get();

        if($options->contains('name', $value)) {
            $fail('این خدمت قبلا در مجموعه شما ثبت شده است.');
        }
    }
}
