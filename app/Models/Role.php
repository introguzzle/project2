<?php

namespace App\Models;

use App\Models\Core\Model;
use Carbon\CarbonInterface;
use JetBrains\PhpStorm\ExpectedValues;

/**
 * @property int $id
 * @property string $name
 * @property CarbonInterface $createdAt
 * @property CarbonInterface $updatedAt
 */
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

    #[ExpectedValues(values: [Role::ADMIN, Role::USER, Role::GUEST])]
    public static function findByName(string $name): ?static
    {
        return (static fn($o): ?static => $o)(static::query()->where('name', '=', $name)->first());
    }
}
