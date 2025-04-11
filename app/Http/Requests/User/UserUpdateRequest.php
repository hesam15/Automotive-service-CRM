<?php

namespace App\Http\Requests\User;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
       if(auth()->user()->can('edit_users') && request()->user()->service_center_id === $this->route('user')->service_center_id) {
            return true;
        }

        return false;
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

        if(request()->is('api/*')) {
            $response = response()->json([
                'message' => 'Invalid data send',
                'details' => $errors->messages(),
            ], 422);
    
            throw new HttpResponseException($response);
        } else {
            return $errors;
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
            'name' => 'required|string|max:25',
            'email' => ['required','email', Rule::unique('users')->ignore($this->user)],
            'phone' => ['required', Rule::unique('users')->ignore($this->user)],
            'role' => 'required|exists:roles,id'
        ];
    }
}
