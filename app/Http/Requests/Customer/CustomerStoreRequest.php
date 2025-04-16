<?php

namespace App\Http\Requests;

use App\Models\Customer;
use App\Rules\CustomerPhoneUnique;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CustomerStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('create_customers');
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();


        if($errors->has('phone')) {
            $errorMessage = $errors->get('phone')[0];

            if(str_contains($errorMessage, "یک مشتری با این شماره تلفن قبلا در مجموعه شما ثبت شده است.")) {
                $customer = Customer::where('phone', $this->phone)->first();

                throw new HttpResponseException(
                    redirect()->route('customers.profile', compact('customer'))
                            ->with([
                                'alert' => [$errorMessage, 'info'],
                            ])
                );
            }
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50'],
            'phone' => ['required', 'regex:/^((\+98|0)9\d{9})$/', new CustomerPhoneUnique],
        ];
    }
}
