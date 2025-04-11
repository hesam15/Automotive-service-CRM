<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceCenterStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {        
        return auth()->user()->can("create_serviceCenters");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => "required|string|max:50",
            "phone" => "required|string|max_digits:11|unique:service_centers|regex:/[0]{1}[0-9]{10}/",
            'manager' => 'required',
            "fridays_off" => "required|boolean",
            "working_hours" => "required|array|max:2"
        ];
    }
}
