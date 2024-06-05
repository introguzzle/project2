<?php

namespace App\Http\Requests\Client;

use App\Http\Requests\Core\CastsInputsBeforeValidation;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PostOrderRequest extends FormRequest
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
            'name'               => 'required|string',
            'phone'              => 'required|string',
            'address'            => 'required|string',
            'price'              => 'required|numeric',
            'payment_method_id'  => 'required|exists:payment_methods,id',
            'receipt_method_id'  => 'required|exists:receipt_methods,id',
        ];
    }

    public function getCasts(): array
    {
        return [
            'price'              => 'float',
            'payment_method_id'  => 'int',
            'receipt_method_id'  => 'int',
        ];
    }
}
