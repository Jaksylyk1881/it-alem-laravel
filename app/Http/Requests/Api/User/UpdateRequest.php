<?php

namespace App\Http\Requests\Api\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'string',
            'phone' => 'unique:users,phone',
            'password' => 'string|min:6|confirmed',
            'avatar' => 'image',
        ];
    }
    public function failedValidation($validator)
    {
        throw new HttpResponseException(
            response()->json(['statusCode' => 400, 'message' => $validator->errors()->first(), 'data' => null], 400)
        );
    }
}
