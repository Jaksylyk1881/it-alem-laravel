<?php

namespace App\Http\Requests\Api\User\Order;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Factory as ValidationFactory;

class UserOrderStoreRequest extends FormRequest
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
            'address_id' => 'required|exists:addresses,id',
            'delivery_type' => 'required|in:' . implode(',', Order::DELIVERY_TYPE),
            'payment_type' => 'required|in:' . implode(',', Order::PAYMENT_TYPE),
            'description' => 'required|string',
        ];
    }
}
