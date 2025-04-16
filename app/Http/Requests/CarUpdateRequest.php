<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CarUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('update_cars');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'name' => 'required|string|max:35|exists:cars',
            'color' => 'required|string|max:22',
            'plate_two' => 'required|integer|max_digits:2',
            'plate_letter' => 'required|string|max:1',
            'plate_three' => 'required|integer|max_digits:3',
            'plate_iran' => 'required|integer|max_digits:2',
        ];
    }
}
