<?php

namespace App\Http\Requests\Admin\Dashboard;

use App\Http\Requests\Core\FormRequest;
use Illuminate\Http\UploadedFile;

/**
 * @property UploadedFile $favicon
 */
class FaviconUpdateRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'favicon' => 'required|mimes:png,jpg,ico|max:2048|dimensions:max_width=1000,max_height=1000'
        ];
    }

    public function getMessages(): array
    {
        return [
            'favicon.required' => 'Иконка обязательна для заполнения.',
            'favicon.image'    => 'Иконка должна быть изображением.',
            'favicon.mimes'    => 'Иконка должна быть формата: ico, png, jpg.',
            'favicon.max'      => 'Размер иконки не должен превышать 2MB.',
        ];
    }
}
