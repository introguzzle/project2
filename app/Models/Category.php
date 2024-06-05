<?php

namespace App\Models;

use App\Models\Core\Model;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property integer $id
 * @property string $name
 * @property integer|null $parentId
 * @property static|null $parent
 *
 * @property Collection<int, Product> $products
 * @property Collection<int, static> $children
 *
 * @property ?CarbonInterface $createdAt;
 * @property ?CarbonInterface $updatedAt
 */
class Category extends Model
{
    protected $fillable = [
        'name',
        'parent_id'
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(static::class, 'parent_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(static::class, 'parent_id');
    }
}
