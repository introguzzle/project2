<?php

namespace App\Http\Requests\Admin\Image;

use App\Http\Requests\Core\FormRequest;

class BindRequest extends FormRequest
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
