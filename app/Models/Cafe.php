<?php

namespace App\Models;

use Carbon\CarbonInterface;

/**
 * @property int $id
 * @property string $name
 * @property string $address
 * @property string $description
 * @property float $requiredOrderSum
 * @property string $image
 * @property array $settings
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
        'description',
        'required_order_sum',
        'image',
        'settings'
    ];
}
