<?php

namespace App\Http\Requests\Client;

use App\Http\Requests\Core\CastsInputsBeforeValidation;
use App\Http\Requests\Core\FormRequest;

/**
 * @property int $receiptMethodId
 */
class PaymentMethodsRequest extends FormRequest
{
    use CastsInputsBeforeValidation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'receipt_method_id' => 'required|integer|exists:receipt_methods,id',
        ];
    }

    public function getMessages(): array
    {
        return [];
    }

    /**
     * @return array<string, string>
     */
    public function getCasts(): array
    {
        return [
            'receipt_method_id' => 'int'
        ];
    }
}
