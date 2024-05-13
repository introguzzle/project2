<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCartRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'product_id'      => 'required',
            'quantity_change' => 'required'
        ];
    }

    public function getProductId(): mixed
    {
        return $this->input('product_id');
    }

    public function getQuantityChange(): mixed
    {
        return $this->input('quantity_change');
    }
}
