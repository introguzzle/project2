<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\CastInputs;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
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
