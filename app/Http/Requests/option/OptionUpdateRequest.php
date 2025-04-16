<?php

namespace App\Http\Requests\option;

use App\Rules\OptionUniqueName;
use Illuminate\Foundation\Http\FormRequest;

class OptionUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('update_options');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'exists:options', new OptionUniqueName],
            'options' => 'required|array',
            'values' => 'required|array',
        ];
    }
}
