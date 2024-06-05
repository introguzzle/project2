<?php

namespace App\Http\Requests\Client;

use App\Http\Requests\Core\CastsInputsBeforeValidation;
use App\Http\Requests\Core\FormRequest;
use Carbon\CarbonInterface;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * @property ?string $name;
 * @property ?CarbonInterface $birthday;
 * @property ?string $address;
 */
class UpdateProfileRequest extends FormRequest
{
    use CastsInputsBeforeValidation;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name'     => 'nullable|string|max:255',
            'birthday' => 'nullable|date',
            'address'  => 'nullable|string|max:255',
        ];
    }

    public function getCasts(): array
    {
        return [
            'birthday' => 'date'
        ];
    }

    public function getMessages(): array
    {
        return [];
    }
}
