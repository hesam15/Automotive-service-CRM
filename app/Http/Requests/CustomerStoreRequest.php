<?php

namespace App\Http\Requests;

use App\Rules\CustomerPhoneUnique;
use Illuminate\Foundation\Http\FormRequest;

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
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = auth()->user();
        return [
            'fullname' => ['required', 'string', 'max:255'],
            'phone' => ['required', new CustomerPhoneUnique],
        ];
    }
}
