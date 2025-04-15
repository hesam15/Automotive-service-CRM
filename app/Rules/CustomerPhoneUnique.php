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
        $customers = auth()->user()->serviceCenter->customers;
        $customerWithPhone = null;

        $updateRequestCustomer = request()->route()->customer;

        foreach ($customers as $customer) {
            if($customer->phone == $value) {
                $customerWithPhone = $customer;
                break;
            }
        }
        
        if($customers->contains('phone', $value) || $customerWithPhone != null && $customerWithPhone->id !== $updateRequestCustomer->id) {
            $fail("یک مشتری با این شماره تلفن قبلا در مجموعه شما ثبت شده است.");
        } elseif($customerWithPhone != null && $customerWithPhone->id == $updateRequestCustomer->id) {
            $fail("هیچ تغییری ایجاد نشد.");
        }
    }
}
