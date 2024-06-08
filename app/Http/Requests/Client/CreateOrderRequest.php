<?php

namespace App\Http\Requests\Client;

use App\Http\Requests\Core\CastsInputsBeforeValidation;
use App\Http\Requests\Core\FormRequest;
use App\Models\Flow;
use Illuminate\Validation\ValidationException;

class CreateOrderRequest extends FormRequest
{
    use CastsInputsBeforeValidation;

    public function authorize(): bool
    {
        return true;
    }

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

    /**
     * @throws ValidationException
     */
    public function passedValidation(): void
    {
        $attributes = [
            'receipt_method_id' => $this->input('receipt_method_id'),
            'payment_method_id' => $this->input('payment_method_id')
        ];

        if (! Flow::query()->where($attributes)->exists()) {
            $validator = $this->validator ?? $this->getValidatorInstance();
            $validator->errors()->add('receipt_method_id', 'Выбранный способ оплаты не доступен');

            throw new ValidationException($this->validator
                ?? $this->getValidatorInstance());
        }
    }

    public function getMessages(): array
    {
        return [
            'name.required'              => 'Поле "Имя" обязательно для заполнения.',
            'name.string'                => 'Поле "Имя" должно быть строкой.',
            'phone.required'             => 'Поле "Телефон" обязательно для заполнения.',
            'phone.string'               => 'Поле "Телефон" должно быть строкой.',
            'address.required'           => 'Поле "Адрес" обязательно для заполнения.',
            'address.string'             => 'Поле "Адрес" должно быть строкой.',
            'payment_method_id.required' => 'Поле "Способ оплаты" обязательно для заполнения.',
            'payment_method_id.exists'   => 'Выбранный способ оплаты недействителен.',
            'receipt_method_id.required' => 'Поле "Способ получения" обязательно для заполнения.',
            'receipt_method_id.exists'   => 'Выбранный способ получения недействителен.',
        ];
    }
}
