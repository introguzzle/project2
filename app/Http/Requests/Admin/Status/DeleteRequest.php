<?php

namespace App\Http\Requests\Admin\Status;

use App\Http\Requests\Core\FormRequest;

/**
 * @property int $statusId
 */
class DeleteRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [];
    }

    public function getMessages(): array
    {
        return [];
    }
}
