<?php

namespace App\Other\Images;

use App\Other\Contracts\UploadedImage as UploadedImageContract;
use App\Other\Contracts\ImagePipeline as ImagePipelineContract;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImagePipeline implements ImagePipelineContract
{
    public static Filesystem|FilesystemAdapter $filesystem;
    private string $name;

    /**
     * @param string $name
     */
    public function __construct(
        string $name
    )
    {
        $this->name = $name;
    }

    /**
     * @param UploadedFile $uploadedFile
     * @param bool $replaceIfExists
     * @return UploadedImageContract
     */

    public function createFile(
        UploadedFile  $uploadedFile,
        bool          $replaceIfExists = false
    ): UploadedImageContract
    {
        $content = $uploadedFile->getContent();
        $this->name .= '.' . $uploadedFile->getClientOriginalExtension();

        if ($this->fileExists()) {
            if ($replaceIfExists) {
                static::$filesystem->put($this->name, $content);
            }
        } else {
            static::$filesystem->put($this->name, $content);
        }

        return new UploadedImage($this->name);
    }

    /**
     * @throws ImageNotFoundException
     */
    public function getFile(): UploadedImageContract
    {
        if (!$this->fileExists()) {
            throw new ImageNotFoundException($this->name);
        }

        return new UploadedImage($this->name);
    }

    public function fileExists(): bool
    {
        return static::$filesystem->exists($this->name);
    }

    public static function boot(): void
    {
        if (!isset(static::$filesystem)) {
            static::$filesystem = Storage::disk('images');
        }
    }
}
