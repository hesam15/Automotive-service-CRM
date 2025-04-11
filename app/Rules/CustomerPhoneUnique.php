<?php

namespace App\Rules;

use App\Models\Customer;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CustomerPhoneUnique implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $customers = Customer::all();
        
        if($customers->contains('phone', $value)) {
            $fail("یک مشتری با این شماره تلفن قبلا در مجموعه شما ثبت شده است");
        }
    }
}
