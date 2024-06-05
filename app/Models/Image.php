<?php

namespace App\Models;

use App\Models\Core\Model;
use App\Other\Images\ImageSize;
use App\Other\Images\ImageSizeCast;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $path
 * @property string $name
 * @property string $description
 * @property string $type
 * @property int $bytes
 * @property string $fileSize
 * @property ImageSize $imageSize
 *
 * @property Collection<Product> $products
 *
 * @property CarbonInterface $createdAt
 * @property CarbonInterface $updatedAt
 */
class Image extends Model
{
    protected $fillable = [
        'path',
        'name',
        'description',
        'type',
        'file_size',
        'bytes',
        'image_size'
    ];

    protected $casts = [
        'image_size' => ImageSizeCast::class
    ];

    public function products(): BelongsToMany
    {
        return $this
            ->belongsToMany(Product::class, 'product_image')
            ->using(ProductImage::class);
    }

    public function url(): string
    {
        return '/images/' . $this->path;
    }
}
