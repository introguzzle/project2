<?php

namespace App\Models;

use App\Models\Core\Model;
use Carbon\CarbonInterface;

/**
 * @property int $id
 * @property string $name
 *
 * @property array $addresses
 * @property array $phones
 * @property array $emails
 * @property array $settings
 *
 * @property string $description
 * @property float $requiredOrderSum
 * @property string $image
 *
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

        'addresses',
        'phones',
        'emails',
        'settings',

        'description',
        'required_order_sum',
        'image',
    ];

    protected $casts = [
        'addresses' => 'json',
        'phones'    => 'json',
        'emails'    => 'json',
        'settings'  => 'json'
    ];
}
