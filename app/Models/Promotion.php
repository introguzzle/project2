<?php

namespace App\Models;

use App\Models\Core\Model;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * Class Promotion
 *
 * @property int $id
 *
 * @property string $name
 * @property string $description
 *
 * @property float $minSum
 * @property float $maxSum
 *
 * @property PromotionType $promotionType
 * @property float $value
 *
 * @property Collection<Flow> $flows
 *
 * @property ?CarbonInterface $createdAt
 * @property ?CarbonInterface $updatedAt
 *
 */
class Promotion extends Model
{
    protected $table = 'promotions';
    protected $fillable = [
        'name',
        'description',
        'min_sum',
        'max_sum',
        'promotion_type_id',
        'value'
    ];

    public function promotionType(): BelongsTo
    {
        return $this->belongsTo(PromotionType::class, 'promotion_type_id');
    }

    public function flows(): HasManyThrough
    {
        return $this->hasManyThrough(
            Flow::class,
            PromotionFlow::class,
            'promotion_id',
            'id',
            'id',
            'flow_id'
        );
    }
}
