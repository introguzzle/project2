<?php

namespace App\Models;

use Carbon\CarbonInterface;

/**
 * @property int $id
 * @property string $name
 * @property ?CarbonInterface $createdAt
 * @property ?CarbonInterface $updatedAt
 */
class PaymentMethod extends Model
{
    protected $table = 'payment_methods';
    protected $fillable = [
        'name'
    ];
}
