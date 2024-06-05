<?php

namespace App\Services;

use App\DTO\ImageDTO;
use App\Exceptions\ServiceException;
use App\Models\Image;
use App\Models\Product;
use App\Models\ProductImage;

use App\Other\Contracts\UploadedImage;

use App\Other\Images\InteractsWithImages;
use App\Services\Core\ControlsTransaction;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProductService
{
    use ControlsTransaction, InteractsWithImages;

    public function create(
        array $attributes,
        ImageDTO $imageDTO
    ): Product
    {
        $product = new Product($attributes);
        return $this->saveProduct($product, $imageDTO);
    }

    public function update(
        int $productId,
        array $attributes,
        ImageDTO $imageDTO
    ): Product
    {
        $product = Product::find($productId);

        if ($product === null) {
            throw new ServiceException('Product not found');
        }

        $product->fill($attributes);
        return $this->saveProduct($product, $imageDTO);
    }

    private function saveProduct(Product $product, ImageDTO $imageDTO): Product
    {
        if ($imageDTO->image === null) {
            $product->save();
            return $product;
        }

        $this->beginTransaction();

        try {
            $product->save();

            $pipeline = $this->createImagePipeline(
                $this->generateName($product)
            );

            $file = $pipeline->createFile($imageDTO->image);
            $this->processImage($product, $imageDTO, $file);

        } catch (Throwable $throwable) {
            $this->rollbackTransaction();

            if (isset($file)) {
                $file->delete();
            }

            Log::error($throwable->getMessage());
            throw new ServiceException(
                'Failed to save product because transaction failed',
                $throwable->getCode(),
                $throwable
            );
        }

        $this->commitTransaction();
        return $product;
    }

    private function processImage(
        Product $product,
        ImageDTO $imageDTO,
        UploadedImage $uploadedImage
    ): void
    {
        $image = $this->createImage($imageDTO, $uploadedImage);

        if ($imageDTO->main) {
            $this->unsetCurrentMainImage($product->id);
        }

        $productImage = new ProductImage();

        $productImage->productId = $product->id;
        $productImage->imageId = $image->id;
        $productImage->main = $imageDTO->main;

        $productImage->save();
    }

    private function unsetCurrentMainImage(int $productId): void
    {
        ProductImage::query()
            ->where('product_id', $productId)
            ->where('main', true)
            ->update(['main' => false]);
    }

    private function generateName(
        Product $product
    ): string
    {
        return "product_{$product->id}_" . uniqueId();
    }

    private function createImage(
        ImageDTO $imageDTO,
        UploadedImage $uploadedImage
    ): Image
    {
        $image = new Image();

        $image->path = $uploadedImage->name;
        $image->name = $imageDTO->name;
        $image->description = $imageDTO->description;
        $image->type = $uploadedImage->getMimeType();
        $image->imageSize = $uploadedImage->getImageSize();
        $image->bytes = $uploadedImage->getFileSize();
        $image->fileSize = $uploadedImage->getFormattedFileSize();

        $image->save();

        return $image;
    }
}

