<?php

namespace App\Http\Requests\Admin\Flow\Receipt;

use App\Http\Requests\Core\FormRequest;

/**
 * @property string $name
 */
class CreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
        ];
    }

    public function getMessages(): array
    {
        return [];
    }
}
