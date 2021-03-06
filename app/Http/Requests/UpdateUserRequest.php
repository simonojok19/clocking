<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'name' => 'required|max:55',
            'email' => 'email|required',
            'password' => 'required|min:8',
            'role' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'A Name is required',
            'email.required' => 'A Email is required',
            'email.unique:users' => 'This Email is already Used',
            'email.email' => 'Please Provide Valid Email',
            'password.required' => 'Please provide password',
            'password.min' => 'Password Must be more than 8 characters',
            'role.required' => 'User Role is required',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(response()->json([
            'errors' => $errors
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    }
}
