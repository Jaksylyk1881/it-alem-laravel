<?php

namespace App\Http\Requests\Api\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductStoreRequest extends FormRequest
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
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string',
            'price' => 'required|integer',
            'count' => 'required|integer',
            'characteristics' => 'required|string',
            'description' => 'required|string',
            'images' => 'required|array',
        ];
    }
    public function failedValidation($validator)
    {
        throw new HttpResponseException(
            response()->json(['statusCode' => 400, 'message' => $validator->errors()->first(), 'data' => null], 400)
        );
    }
}
