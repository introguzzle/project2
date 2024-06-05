<?php

namespace App\Http\Requests\Admin\Flow;

use App\Http\Requests\Core\FormRequest;

/**
 * @property int $receiptMethodId
 * @property array<int, int> $paymentMethodIndices
 */
class UpdateRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'receipt_method_id'        => 'required|exists:receipt_methods,id',
            'payment_method_indices'   => 'required|array',
            'payment_method_indices.*' => 'integer|exists:payment_methods,id'
        ];
    }

    public function getMessages(): array
    {
        return [
            'receipt_method_id.required'       => 'Поле способа доставки обязательно для заполнения.',
            'receipt_method_id.exists'         => 'Выбранный способ доставки не существует.',
            'payment_method_indices.required'  => 'Поле способов оплаты обязательно для заполнения.',
            'payment_method_indices.array'     => 'Поле способов оплаты должно быть массивом.',
            'payment_method_indices.*.integer' => 'Каждый способ оплаты должен быть целым числом.',
            'payment_method_indices.*.exists'  => 'Выбранный способ оплаты не существует.',
        ];
    }

    public function passedValidation(): void
    {
        $key = 'payment_method_indices';

        $indices = $this->input($key);
        $indices = array_map(static fn (string $item): int => (int) $item, $indices);
        $this->request->set($key, $indices);
    }
}
