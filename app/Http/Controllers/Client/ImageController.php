<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Core\Controller;
use App\Other\Images\ImageNotFoundException;
use App\Other\Images\InteractsWithImages;
use Illuminate\Http\Response;

class ImageController extends Controller
{
    use InteractsWithImages;
    /**
     * @throws ImageNotFoundException
     */
    public function show(string $name): Response
    {
        $pipeline = $this->createImagePipeline($name);

        if (!$pipeline->fileExists()) {
            $this->abortNotFound();
        }

        $image = $pipeline->getFile();

        return response($image->getContents(), 200)
            ->header('Content-Type', $image->getMimeType());
    }
}
