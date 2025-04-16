<?php

namespace App\Http\Requests\ServiceCenter;

use Illuminate\Foundation\Http\FormRequest;

class ServiceCenterUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('edit_serviceCenters');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => "required|string|max:50|exists:service_centers",
            "phone" => "required|string|max_digits:11|regex:/[0]{1}[0-9]{10}/|exists:service_centers",
            'manager' => 'required|exists:service_centers',
            "fridays_off" => "required|boolean",
            "working_hours" => "required|array|max:2"
        ];
    }
}
