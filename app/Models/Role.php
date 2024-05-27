<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    /**
     * @DBRecord
     */
    public const string ADMIN = 'Admin';

    /**
     * @DBRecord
     */
    public const string USER = 'User';

    /**
     * @DBRecord
     */
    public const string GUEST = 'Guest';

    protected $fillable = [
        'name'
    ];

    public static function findByName(string $name): ?static
    {
        return (static fn($o): ?static => $o)(static::query()->where('name', '=', $name)->first());
    }
}
