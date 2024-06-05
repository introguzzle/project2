<?php

namespace App\Http\Requests\Admin\Cafe;

use App\Http\Requests\Core\CastsInputsBeforeValidation;
use App\Http\Requests\Core\FormRequest;
use App\Http\Requests\Core\HasModelAttributes;
use Illuminate\Http\UploadedFile;

/**
 * @property ?string $name
 * @property ?string $description
 * @property ?float $requiredOrderSum
 *
 * @property ?array $addresses
 * @property ?array $phones
 * @property ?array $emails
 *
 * @property ?UploadedFile $image
 */
class UpdateRequest extends FormRequest
{
    use CastsInputsBeforeValidation, HasModelAttributes;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'               => 'nullable|string',
            'description'        => 'nullable|string',
            'required_order_sum' => 'nullable|numeric',
            'addresses'          => 'nullable|array',
            'phones'             => 'nullable|array',
            'emails'             => 'nullable|array',
            'image'              => 'nullable|image|mimes:jpeg,png,jpg,svg'
        ];
    }

    public function getMessages(): array
    {
        return [];
    }

    public function getCasts(): array
    {
        return [
            'required_order_sum' => 'float'
        ];
    }

    public function getExcept(): array
    {
        return [
            'image'
        ];
    }
}
