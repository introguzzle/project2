<?php

namespace App\Http\Requests\Admin\Order;

use App\Http\Requests\Core\CastsInputsBeforeValidation;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property int $id
 */
class CompleteRequest extends FormRequest
{
    use CastsInputsBeforeValidation;
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:orders,id',
        ];
    }

    public function getCasts(): array
    {
        return [
            'id' => 'int'
        ];
    }
}
