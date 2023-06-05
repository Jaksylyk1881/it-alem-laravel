<?php

namespace App\Http\Requests\Api\User\Review;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserReviewCompanyStoreRequest extends FormRequest
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
            'rate' => 'required|integer|min:1|max:5',
            'body' => 'required|string',
        ];
    }

    public function failedValidation($validator)
    {
        throw new HttpResponseException(
            response()->json(['statusCode' => 400, 'message' => $validator->errors()->first(), 'data' => null], 400)
        );
    }
}
