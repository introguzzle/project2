<?php

namespace App\Http\Requests\Admin\Status;

/**
 * @property string $name
 * @property int $statusId
 */
class UpdateRequest
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
