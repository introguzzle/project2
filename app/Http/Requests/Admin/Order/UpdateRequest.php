<?php

namespace App\Http\Requests\Admin\Order;

use App\Http\Requests\Core\CastsInputsBeforeValidation;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'description' => [
                'nullable',
                'string'
            ],

            'order' => [
                'required',
                'int'
            ],

            'status' => [
                'required',
                'int'
            ],
        ];
    }

    public function getCasts(): array
    {
        return [
            'order'       => 'int',
            'status'      => 'int'
        ];
    }

    public function getDescriptionInput(): ?string
    {
        return $this->input('description');
    }

    public function getOrderIdInput(): int
    {
        return $this->input('order');
    }

    public function getStatusIdInput(): int
    {
        return $this->input('status');
    }
}
