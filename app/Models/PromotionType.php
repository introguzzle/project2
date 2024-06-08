<?php

namespace App\Models;

use App\Models\Core\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property ?string $description
 *
 * @property Collection<Promotion> $promotions
 */
class PromotionType extends Model
{
    /**
     * @DBRecord
     */
    public const string FIXED = 'Фиксированная';

    /**
     * @DBRecord
     */
    public const string PERCENTAGE = 'Процентная';

    protected $table = 'promotion_types';
    protected $fillable = [
        'name',
        'description'
    ];

    public function promotions(): HasMany
    {
        return $this->hasMany(Promotion::class, 'promotion_type_id', 'id');
    }

    public static function findByName(string $name): ?static
    {
        return static::findUnique('name', $name);
    }

    public static function fixed(): static
    {
        return static::findByName(self::FIXED);
    }

    public static function percentage(): static
    {
        return static::findByName(self::PERCENTAGE);
    }

    public function equals(self $promotionType): bool
    {
        return $this->is($promotionType);
    }
}
