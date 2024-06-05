<?php

namespace App\Other\Images;

use Stringable;

readonly class ImageSize implements Stringable
{
    public int $width;
    public int $height;

    public function __construct(int $width, int $height)
    {
        $this->width = $width;
        $this->height = $height;
    }

    public static function of(string $fromImage): static
    {
        [$width, $height] = explode('x', $fromImage);
        return new static($width, $height);
    }

    public function __toString(): string
    {
        return $this->width . 'x' . $this->height;
    }
}
