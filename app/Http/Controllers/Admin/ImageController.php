<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Core\Controller;
use App\Http\Requests\Admin\Image\BindRequest;

use App\Models\Image;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ImageController extends Controller
{
    public function showImages(): View
    {
        $images = Image::ordered('id');

        $data = compact('images');
        return view('admin.images.index')->with($data);
    }

    public function bindTo(BindRequest $request): RedirectResponse
    {
        return $this->unsupported();
//        $imageId = $request->imageId;
//        $productId = $request->productId;
//
//        ProductImage::find([
//            'product_id' => $productId,
//            'main' => true
//        ])?->update(['main' => false]);
//
//        ProductImage::create([
//            'product_id' => $productId,
//            'image_id'   => $imageId,
//            'main'       => true
//        ]);
    }
}
