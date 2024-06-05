<?php

namespace App\Http\Requests\Client;

use App\Http\Requests\Core\CastsInputsBeforeValidation;
use App\Http\Requests\Core\FormRequest;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * @property int $productId
 * @property int $quantityChange
 */
class UpdateCartRequest extends FormRequest
{
    use CastsInputsBeforeValidation;
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

    public function getCasts(): array
    {
        return [
            'product_id'      => 'int',
            'quantity_change' => 'int',
        ];
    }

    public function getMessages(): array
    {
        return [];
    }
}
