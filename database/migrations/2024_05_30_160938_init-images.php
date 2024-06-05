<?php

use App\Other\Images\InteractsWithImages;
use App\Other\Populate;

return new class extends Populate
{
    use InteractsWithImages;
    public function up(): void
    {
        $path = 'sample.png';

        $image = $this->createImagePipeline($path)->getFile();

        $this->insert('images', [
            'path'        => $path,
            'name'        => 'name',
            'description' => 'description',
            'type'        => $image->getMimeType(),
            'file_size'   => $image->getFormattedFileSize(),
            'bytes'       => $image->getFileSize(),
            'image_size'  => $image->getImageSize(),
        ]);
    }
};
