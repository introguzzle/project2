<?php

namespace App\Other\Contracts;

use App\Other\Images\ImageSize;

interface UploadedImage
{
    public function getMimeType(): string;

    public function getFileSize(): int;

    public function getFormattedFileSize(): string;

    public function getImageSize(): ImageSize;

    public function getExtension(): string;

    public function getContents(): string;

    public function delete(): bool;
}
