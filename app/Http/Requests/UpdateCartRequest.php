<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCartRequest extends FormRequest
{
    use CastInputs;
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

    public function getProductId(): int
    {
        return $this->input('product_id');
    }

    public function getQuantityChange(): int
    {
        return $this->input('quantity_change');
    }

    public function getCasts(): array
    {
        return [
            'product_id'      => 'int',
            'quantity_change' => 'int',
        ];
    }
}
