<?php

namespace App\Http\Requests\Admin\Category;

use App\Http\Requests\Core\CastsInputsBeforeValidation;
use App\Http\Requests\Core\FormRequest;
use App\Models\Category;
use Closure;

/**
 * @property int $categoryId
 * @property string $name
 * @property int $parentId
 */
class UpdateRequest extends FormRequest
{
    use CastsInputsBeforeValidation;
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => 'required',
            'parent_id'   => [
                'nullable',
                $this->parentValidator()
            ],
            'category_id' => 'required|exists:categories,id',
        ];
    }

    protected function passedValidation(): void
    {
        if ($this->parentId === 0) {
            $this->request->set('parent_id', null);
        }
    }

    public function getMessages(): array
    {
        return [
            'name.required' => 'Имя для категории обязательно'
        ];
    }

    public function getCasts(): array
    {
        return [
            'parent_id'   => 'int',
            'category_id' => 'int'
        ];
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
