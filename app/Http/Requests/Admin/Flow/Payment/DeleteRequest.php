<?php

namespace App\Http\Requests\Admin\Flow\Payment;

use App\Http\Requests\Core\CastsInputsBeforeValidation;
use App\Http\Requests\Core\FormRequest;

/**
 * @property int $paymentMethodId
 */
class DeleteRequest extends FormRequest
{
    use CastsInputsBeforeValidation;
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payment_method_id' => 'required',
        ];
    }

    public function getMessages(): array
    {
        return [];
    }

    public function getCasts(): array
    {
        return [
            'payment_method_id' => 'int'
        ];
    }
}
