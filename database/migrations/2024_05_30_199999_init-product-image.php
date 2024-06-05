<?php

use App\Models\Image;
use App\Models\Product;
use App\Models\ProductImage;
use App\Other\Populate;

return new class extends Populate
{
    public function up(): void
    {
        $image = Image::all()->first();

        Product::all()->each(function (Product $product) use ($image) {
            ProductImage::query()->create([
                'product_id' => $product->id,
                'image_id'   => $image->id,
                'main'       => true
            ]);
        });
    }
};
