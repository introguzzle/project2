<?php

namespace App\Http\Requests\Admin\Promotion;

use App\Http\Requests\Core\CastsInputsBeforeValidation;
use App\Http\Requests\Core\FormRequest;
use App\Http\Requests\Core\HasModelAttributes;

/**
 * @property string $name
 * @property ?string $description
 * @property float $minSum
 * @property float $maxSum
 * @property int $promotionTypeId
 * @property float $value
 * @property int[] $flows
 */
class CreateRequest extends FormRequest
{
    use CastsInputsBeforeValidation, HasModelAttributes;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'              => 'required|string|max:255',
            'description'       => 'nullable|string|max:500',
            'min_sum'           => 'required|numeric|min:0',
            'max_sum'           => 'required|numeric|min:0|gte:min_sum',
            'promotion_type_id' => 'required|exists:promotion_types,id',
            'value'             => 'required|numeric|min:0',
            'flows'             => 'required|array',
            'flows.*'           => 'exists:flows,id',
        ];
    }

    public function getCasts(): array
    {
        return [
            'promotion_type_id' => 'int',
            'min_sum'           => 'float',
            'max_sum'           => 'float',
            'value'             => 'float',
            'flows'             => 'int[]'
        ];
    }

    public function getMessages(): array
    {
        return [
            'name.required'              => 'Поле название обязательно для заполнения.',
            'name.string'                => 'Название должно быть строкой.',
            'name.max'                   => 'Название не должно превышать 255 символов.',
            'description.string'         => 'Описание должно быть строкой.',
            'description.max'            => 'Описание не должно превышать 500 символов.',
            'min_sum.required'           => 'Поле минимальная сумма обязательно для заполнения.',
            'min_sum.numeric'            => 'Минимальная сумма должна быть числом.',
            'min_sum.min'                => 'Минимальная сумма не может быть отрицательной.',
            'max_sum.required'           => 'Поле максимальная сумма обязательно для заполнения.',
            'max_sum.numeric'            => 'Максимальная сумма должна быть числом.',
            'max_sum.min'                => 'Максимальная сумма не может быть отрицательной.',
            'max_sum.gte'                => 'Максимальная сумма должна быть больше или равна минимальной сумме.',
            'promotion_type_id.required' => 'Поле тип акции обязательно для заполнения.',
            'promotion_type_id.exists'   => 'Указанный тип акции не существует.',
            'value.required'             => 'Поле значение обязательно для заполнения.',
            'value.numeric'              => 'Значение должно быть числом.',
            'value.min'                  => 'Значение не может быть отрицательным.',
            'flows.required'             => 'Необходимо выбрать хотя бы одну связку получения и оплаты.',
            'flows.array'                => 'Поле связки получения и оплаты должно быть массивом.',
            'flows.*.exists'             => 'Один или несколько выбранных связок получения и оплаты не существуют.',
        ];
    }

    public function getExcept(): array
    {
        return [
            'promotion_type_id',
            'flows'
        ];
    }
}
