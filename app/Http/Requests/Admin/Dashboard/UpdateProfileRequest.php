<?php

namespace App\Http\Requests\Admin\Dashboard;

use App\Http\Requests\Core\CastsInputsBeforeValidation;
use App\Http\Requests\Core\FormRequest;
use Carbon\CarbonInterface;

/**
 * @property ?string $name
 * @property ?string $email
 * @property ?string $phone
 * @property ?string $address
 * @property ?CarbonInterface $birthday
 */
class UpdateProfileRequest extends FormRequest
{
    use CastsInputsBeforeValidation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => 'nullable|string|max:255',
            'email'    => 'nullable|email|max:255',
            'phone'    => 'nullable|string|max:255',
            'address'  => 'nullable|string|max:255',
            'birthday' => 'nullable|date',
        ];
    }

    public function getMessages(): array
    {
        return [];
    }

    public function getCasts(): array
    {
        return [
            'birthday' => 'date'
        ];
    }
}
