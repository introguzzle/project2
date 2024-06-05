<?php

namespace App\Http\Requests\Admin\Category;

use App\Http\Requests\Core\CastsInputsBeforeValidation;
use App\Http\Requests\Core\FormRequest;

/**
 * @property int $categoryId
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
            'category_id' => 'required|exists:categories,id',
        ];
    }

    public function getMessages(): array
    {
        return [];
    }

    public function getCasts(): array
    {
        return [
            'category_id' => 'int'
        ];
    }
}
