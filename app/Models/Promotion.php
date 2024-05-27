<?php

namespace App\Models;

use Carbon\CarbonInterface;

/**
 * Class Promotion
 *
 * @property int $id
 *
 * @property string $name
 * @property string $description
 *
 * @property float $minSum
 * @property float $maxSum
 *
 * @property string $type
 * @property float $value
 *
 * @property ?CarbonInterface $createdAt
 * @property ?CarbonInterface $updatedAt
 */
class Promotion extends Model
{
    protected $table = 'promotions';
    protected $fillable = [
        'name',
        'description',
        'min_sum',
        'max_sum',
        'type',
        'value'
    ];
}
