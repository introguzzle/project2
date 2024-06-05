<?php

namespace App\DTO;

use Illuminate\Http\UploadedFile;

readonly class ImageDTO
{
    public ?UploadedFile $image;
    public ?bool $main;
    public ?string $name;
    public ?string $description;

    /**
     * @param UploadedFile|null $image
     * @param bool|null $main
     * @param string|null $name
     * @param string|null $description
     */
    public function __construct(
        ?UploadedFile $image,
        ?bool $main,
        ?string $name,
        ?string $description
    )
    {
        $this->image = $image;
        $this->main = $main;
        $this->name = $name;
        $this->description = $description;
    }
}
