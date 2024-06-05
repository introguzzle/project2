<?php

namespace App\Http\Requests\Admin\Product;

use App\Http\Requests\Core\CastsInputsBeforeValidation;
use App\Http\Requests\Core\FormRequest;
use App\Http\Requests\Core\HasModelAttributes;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;

/**
 * @property ?string $name
 * @property ?float $price
 * @property ?string $shortDescription
 * @property ?string $fullDescription
 * @property ?float $weight
 * @property ?bool $availability
 * @property ?int $categoryId
 * @property int $productId
 *
 * @property ?UploadedFile $image
 * @property ?boolean $main
 * @property ?string $imageName,
 * @property ?string $imageDescription
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
            'product_id'        => 'required|exists:products,id',
            'name'              => 'string|max:255',
            'price'             => 'numeric',
            'short_description' => 'string|max:500',
            'full_description'  => 'string',
            'weight'            => 'numeric',
            'availability'      => 'boolean',
            'category_id'       => 'exists:categories,id',

            'image'             => 'nullable|image|mimes:jpeg,png,jpg,svg',
            'main'              => 'nullable|boolean',
            'image_name'        => 'nullable|string',
            'image_description' => 'nullable|string',
        ];
    }

    public function getCasts(): array
    {
        return [
            'product_id'   => 'int',
            'category_id'  => 'int',
            'availability' => 'bool',
            'weight'       => 'float',
            'price'        => 'float',
        ];
    }

    public function getProductId(): int
    {
        return $this->input('product_id');
    }

    public function getMessages(): array
    {
        return [];
    }

    public function getExcept(): array
    {
        return [
            'image',
            'main',
            'product_id'
        ];
    }
}
