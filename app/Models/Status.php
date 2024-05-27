<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use JetBrains\PhpStorm\ExpectedValues;

/**
 * @property int $id
 * @property string $name
 * @property CarbonInterface $createdAt
 * @property CarbonInterface $updatedAt
 */
class Status extends Model
{
    /**
     * @DBRecord
     */
    public const string PENDING = 'Ожидание';

    /**
     * @DBRecord
     */
    public const string CONFIRMED = 'Подтвержден';

    /**
     * @DBRecord
     */
    public const string GETTING_READY = 'Готовится';

    /**
     * @DBRecord
     */
    public const string SHIPPED = 'Отправлен';

    /**
     * @DBRecord
     */
    public const string DELIVERED = 'Доставлен';

    /**
     * @DBRecord
     */
    public const string CANCELLED = 'Отменен';

    /**
     * @DBRecord
     */
    public const string RETURNED = 'Возвращен';

    /**
     * @DBRecord
     */
    public const string REFUNDED = 'Возврат средств';

    /**
     * @DBRecord
     */
    public const string COMPLETED = 'Завершен';

    /**
     * @DBRecord
     */
    public const string FAILED_DELIVERY = 'Доставка отменена';

    /**
     * @var string[]
     */
    protected $fillable = [
        'name'
    ];

    #[ExpectedValues(valuesFromClass: Status::class)]
    public static function findByName(string $name): static
    {
        return static::findUnique('name', $name);
    }

    public function getName(): mixed
    {
        return $this->getAttribute('name');
    }

    public static function pending(): static
    {
        return static::findByName(self::PENDING);
    }
}
