<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ResetPasswordRequest extends FormRequest
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
            'phone' => 'required|string|exists:users,phone',
            'password' => 'required|string|min:6|confirmed',
        ];
    }
    public function failedValidation($validator)
    {
        throw new HttpResponseException(
            response()->json(['statusCode' => 400, 'message' => $validator->errors()->first(), 'data' => null], 400)
        );
    }
}
