<?php

namespace App\Models;

use Carbon\CarbonInterface;

/**
 * @property int $id
 * @property string $name
 * @property ?CarbonInterface $createdAt
 * @property ?CarbonInterface $updatedAt
 */
class DeliveryMethod extends Model
{
    protected $table = 'delivery_methods';
    protected $fillable = [
        'name'
    ];
}
