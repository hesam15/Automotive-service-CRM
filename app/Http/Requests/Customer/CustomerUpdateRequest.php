<?php

namespace App\Http\Requests;

use App\Rules\CustomerPhoneUnique;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CustomerUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('edit_customers');
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();


        if($errors->has('phone')) {
            $errorMessage = $errors->get('phone')[0];

            if(str_contains($errorMessage, "هیچ تغییری ایجاد نشد.")) {
                throw new HttpResponseException(
                    redirect()->back()
                            ->with([
                                'alert' => [$errorMessage, 'danger'],
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
            'phone' => ['required', new CustomerPhoneUnique],
        ];
    }
}
