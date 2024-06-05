<?php

namespace App\Other\Contracts;

use App\Other\Contracts\UploadedImage;
use App\Other\Images\ImageNotFoundException;
use Illuminate\Http\UploadedFile;

interface ImagePipeline
{
    /**
     * @param UploadedFile $uploadedFile
     * @param bool $replaceIfExists
     * @return UploadedImage
     */
    public function createFile(
        UploadedFile $uploadedFile,
        bool         $replaceIfExists = false
    ): UploadedImage;

    /**
     * @throws ImageNotFoundException
     */
    public function getFile(): UploadedImage;

    public function fileExists(): bool;
}
