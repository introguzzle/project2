<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Status extends Model
{
    use HasFactory;

    /**
     * @DBRecord
     */
    public const string NEW = 'Новый';

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
    public const string PROCESSING = 'В обработке';

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

    protected $fillable = [
        'name'
    ];

    public static function getByName(string $name): static
    {
        return static::query()
            ->where('name', '=', $name)
            ->get()
            ->first();
    }

    public function getName(): mixed
    {
        return $this->getAttribute('name');
    }

    public static function getNewStatus(): static
    {
        return static::getByName(self::NEW);
    }
}
