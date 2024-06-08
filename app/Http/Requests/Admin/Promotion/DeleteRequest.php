<?php

namespace App\Http\Requests\Admin\Promotion;

use App\Http\Requests\Core\FormRequest;

/**
 * @property int $promotionId
 */
class DeleteRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'promotion_id' => 'required|exists:promotions,id'
        ];
    }

    public function getMessages(): array
    {
        return [];
    }
}
