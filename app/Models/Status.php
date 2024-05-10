<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory, FindById;
    public const string AWAITING = 'Ожидание';
    public const string DONE = 'Выполнено';

    use HasFactory;

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

    public static function acquireAwaiting(): static
    {
        return static::acquireByName(self::AWAITING);
    }

    public static function acquireDone(): static
    {
        return static::acquireByName(self::DONE);
    }
}
