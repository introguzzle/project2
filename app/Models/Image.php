<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'main'
    ];

    protected $hidden = [

    ];

    public function products(): BelongsToMany
    {
        return $this
            ->belongsToMany(Product::class, 'product_image')
            ->using(ProductImage::class);
    }
}
