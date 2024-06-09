<?php

namespace App\Models;

use App\Models\Core\Model;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection;
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

    public static function pending(): static
    {
        return static::findByName(self::PENDING);
    }

    public static function confirmed(): static
    {
        return static::findByName(self::CONFIRMED);
    }

    public static function gettingReady(): static
    {
        return static::findByName(self::GETTING_READY);
    }

    public static function shipped(): static
    {
        return static::findByName(self::SHIPPED);
    }

    public static function delivered(): static
    {
        return static::findByName(self::DELIVERED);
    }

    public static function cancelled(): static
    {
        return static::findByName(self::CANCELLED);
    }

    public static function returned(): static
    {
        return static::findByName(self::RETURNED);
    }

    public static function refunded(): static
    {
        return static::findByName(self::REFUNDED);
    }

    public static function completed(): static
    {
        return static::findByName(self::COMPLETED);
    }

    public static function failedDelivery(): static
    {
        return static::findByName(self::FAILED_DELIVERY);
    }

    /**
     * Возвращает коллекцию статусов, которые означают незавершенность заказа
     *
     * @return Collection<Status>
     */
    public static function activeStatuses(): Collection
    {
        return new Collection([
            static::pending(),
            static::confirmed(),
            static::gettingReady(),
            static::shipped(),
        ]);
    }

    /**
     * Возвращает коллекцию статусов, которые означает завершенность заказа
     *
     * @return Collection<Status>
     */
    public static function completedStatuses(): Collection
    {
        return new Collection([
            static::delivered(),
            static::cancelled(),
            static::returned(),
            static::refunded(),
            static::completed(),
            static::failedDelivery(),
        ]);
    }

    public function isCompleted(): bool
    {
        return static::completedStatuses()->contains('name', '=', $this->name);
    }

    public function isActive(): bool
    {
        return static::activeStatuses()->contains('name', '=', $this->name);
    }
}
