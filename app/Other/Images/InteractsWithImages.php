<?php

namespace App\Other\Images;

use App\Other\Contracts\ImagePipeline as ImagePipelineContract;

trait InteractsWithImages
{
    protected function createImagePipeline(
        string $name
    ): ImagePipelineContract
    {
        ImagePipeline::boot();
        return new ImagePipeline($name);
    }
}
