<?php

namespace App\Http\Requests\Admin\Category;

use App\Http\Requests\Core\CastsInputsBeforeValidation;
use App\Http\Requests\Core\FormRequest;
use App\Models\Category;
use Closure;

/**
 * @property string $name
 * @property int $parentId
 */
class CreateRequest extends FormRequest
{
    use CastsInputsBeforeValidation;
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'      => 'required',
            'parent_id' => [
                'nullable',
                $this->parentValidator(),
            ],
        ];
    }

    public function getMessages(): array
    {
        return [];
    }

    public function getCasts(): array
    {
        return [
            'parent_id' => 'int'
        ];
    }

    public function passedValidation(): void
    {
        if ($this->parentId === 0) {
            $this->request->set('parent_id', null);
        }
    }

    /**
     * @return Closure
     */
    public function parentValidator(): Closure
    {
        return static function (string $attribute, int $parentId, Closure $fail) {
            $notSelected = $parentId === 0;
            $doesNotExist = Category::find($parentId) === null;

            if (!$notSelected && $doesNotExist) {
                $fail('Выбранной категории не существует');
            }
        };
    }
}
