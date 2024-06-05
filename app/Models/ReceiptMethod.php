<?php

namespace App\Models;

use App\Models\Core\Model;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * @property int $id
 * @property string $name
 *
 * @property Collection<Flow> $flows
 * @property Collection<PaymentMethod> $paymentMethods
 *
 * @property ?CarbonInterface $createdAt
 * @property ?CarbonInterface $updatedAt
 */
class ReceiptMethod extends Model
{
    protected $table = 'receipt_methods';
    protected $fillable = [
        'name'
    ];

    public function flows(): HasMany
    {
        return $this->hasMany(Flow::class, 'receipt_method_id', 'id');
    }

    public function paymentMethods(): HasManyThrough
    {
        return $this->hasManyThrough(
            PaymentMethod::class,
            Flow::class,
            'receipt_method_id',
            'id',
            'id',
            'payment_method_id'
        );
    }
}
