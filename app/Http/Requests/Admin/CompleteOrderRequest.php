<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\CastInputs;
use Illuminate\Foundation\Http\FormRequest;

class CompleteOrderRequest extends FormRequest
{
    use CastInputs;
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

    public function getIdInput(): int
    {
        return $this->input('id');
    }
}
