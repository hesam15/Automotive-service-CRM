<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return !auth()->check() || auth()->user()->can('create_users');
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
            throw new HttpResponseException(
                redirect()->back()
                         ->withErrors($errors)
                         ->withInput());
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $nullRole = request()->route()->named("register.user") ? "nullable" : "required";

        return [
            'name' => 'required|string|max:25',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'phone' => 'required|unique:users',
            'role' => $nullRole.'|exists:roles,name',
        ];
    }
}
