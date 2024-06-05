<?php

namespace App\Other\Images;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class ImageSizeCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param Model $model
     * @param string $key
     * @param string $value
     * @param array $attributes
     * @return ImageSize
     */
    public function get(
        Model  $model,
        string $key,
        mixed  $value,
        array  $attributes
    ): ImageSize
    {
        return ImageSize::of($value);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param Model $model
     * @param string $key
     * @param ImageSize  $value
     * @param array $attributes
     * @return string
     */
    public function set(
        Model  $model,
        string $key,
        mixed  $value,
        array  $attributes
    ): string
    {
        return $value->__toString();
    }
}
