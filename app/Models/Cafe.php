<?php

namespace App\Models;

use App\Models\Core\Model;
use Carbon\CarbonInterface;

/**
 * @property int $id
 * @property string $name
 *
 * @property string $address
 * @property string $phone
 * @property string $email
 * @property array $settings
 *
 * @property string $description
 * @property float $requiredOrderSum
 * @property string $image
 *
 * @property ?CarbonInterface $createdAt
 * @property ?CarbonInterface $updatedAt
 */
class Cafe extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',

        'address',
        'phone',
        'email',
        'settings',

        'description',
        'required_order_sum',
        'image',
    ];

    protected $casts = [
        'settings'  => 'json'
    ];

    public function getImage(): string
    {
        return '/images/' . $this->image;
    }

    public static function get(): static
    {
        return static::all()->first();
    }
}
