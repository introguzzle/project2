<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory, ModelTrait;
    public const string NEW = 'Новый';
    public const string PENDING = 'Ожидание';
    public const string CONFIRMED = 'Подтвержден';
    public const string PROCESSING = 'В обработке';
    public const string SHIPPED = 'Отправлен';
    public const string DELIVERED = 'Доставлен';
    public const string CANCELLED = 'Отменен';
    public const string RETURNED = 'Возвращен';
    public const string REFUNDED = 'Возврат средств';
    public const string COMPLETED = 'Завершен';
    public const string FAILED_DELIVERY = 'Доставка отменена';

    protected $fillable = [
        'name'
    ];

    public static function acquireByName(string $name): static
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

    public static function acquireNew(): static
    {
        return static::acquireByName(self::NEW);
    }
}
