<?php

namespace App\Http\Requests\Admin\Product;

use App\Http\Requests\Core\CastsInputsBeforeValidation;
use App\Http\Requests\Core\FormRequest;
use App\Http\Requests\Core\HasModelAttributes;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;

/**
 * @property string $name
 * @property float $price
 * @property string $shortDescription
 * @property string $fullDescription
 * @property float $weight
 * @property bool $availability
 * @property int $categoryId
 *
 * @property ?UploadedFile $image
 * @property ?string $imageName,
 * @property ?string $imageDescription
 */
class CreateRequest extends FormRequest
{
    use CastsInputsBeforeValidation, HasModelAttributes;
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'              => 'required|string|max:255',
            'price'             => 'required|numeric',
            'short_description' => 'required|string|max:500',
            'full_description'  => 'required|string',
            'weight'            => 'required|numeric',
            'availability'      => 'required|boolean',
            'category_id'       => 'required|exists:categories,id',

            'image'             => 'nullable|image|mimes:jpeg,png,jpg,svg',
            'main'              => 'nullable|boolean',
            'image_name'        => 'nullable|string',
            'image_description' => 'nullable|string',
        ];
    }

    public function getCasts(): array
    {
        return [
            'category_id'  => 'int',
            'availability' => 'bool',
            'weight'       => 'float',
            'price'        => 'float',
            'main'         => 'bool'
        ];
    }

    public function getMessages(): array
    {
        return [];
    }

    public function getExcept(): array
    {
        return [
            'image',
            'main'
        ];
    }
}
