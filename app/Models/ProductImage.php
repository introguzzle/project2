<?php

namespace App\Models;

use App\Models\Core\Pivot;
use Carbon\CarbonInterface;
use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * @property int $productId
 * @property int $imageId
 *
 * @property Product $product
 * @property Image $image
 *
 * @property bool $main
 *
 * @property ?CarbonInterface $createdAt;
 * @property ?CarbonInterface $updatedAt
 */
class ProductImage extends Pivot
{
    protected $primaryKey = ['product_id', 'image_id'];
    protected $table = 'product_image';
    protected $fillable = [
        'product_id',
        'image_id',
        'main'
    ];

    public function setMain(bool $main): bool
    {
        return DB::statement(
            'UPDATE product_image SET main = ? WHERE product_id = ? AND image_id = ?',
            [$main, $this->productId, $this->imageId]
        );
    }
}
