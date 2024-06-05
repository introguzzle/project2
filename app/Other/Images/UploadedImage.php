<?php

namespace App\Other\Images;

use App\Other\Contracts\UploadedImage as UploadedImageContract;

class UploadedImage implements UploadedImageContract
{
    public string $name;
    public string $path;
    private ?string $contents = null;
    private ?array $info = null;
    private ?int $fileSize = null;

    private bool $closed = false;

    /**
     * @param string $name
     */
    public function __construct(
        string $name
    )
    {
        $this->name = $name;
        $this->path = ImagePipeline::$filesystem->path($this->name);
    }

    private function loadInfo(): void
    {
        if ($this->info === null && !$this->closed) {
            $this->info = getimagesize($this->path);
        }
    }

    public function getMimeType(): string
    {
        $this->loadInfo();
        return $this->info['mime'];
    }

    public function getFileSize(): int
    {
        if ($this->fileSize === null && !$this->closed) {
            $this->fileSize = filesize($this->path);
        }

        return $this->fileSize;
    }

    public function getFormattedFileSize(): string
    {
        return formatBytes($this->getFileSize());
    }

    public function getImageSize(): ImageSize
    {
        $this->loadInfo();
        return new ImageSize($this->info[0], $this->info[1]);
    }

    public function getExtension(): string
    {
        $this->loadInfo();
        return explode('/', $this->info['mime'])[1];
    }

    public function getContents(): string
    {
        if ($this->contents === null && !$this->closed) {
            $this->contents = ImagePipeline::$filesystem->get($this->name);
        }

        return $this->contents;
    }

    public function delete(): bool
    {
        $this->closed = true;
        return ImagePipeline::$filesystem->delete($this->name);
    }
}
