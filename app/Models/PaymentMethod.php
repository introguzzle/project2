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
 * @property Collection<ReceiptMethod> $receiptMethods
 *
 * @property ?CarbonInterface $createdAt;
 * @property ?CarbonInterface $updatedAt
 */
class PaymentMethod extends Model
{
    protected $table = 'payment_methods';
    protected $fillable = [
        'name'
    ];

    public function flows(): HasMany
    {
        return $this->hasMany(Flow::class, 'payment_method_id', 'id');
    }

    public function receiptMethods(): HasManyThrough
    {
        return $this->hasManyThrough(
            ReceiptMethod::class,
            Flow::class,
            'payment_method_id',
            'id',
            'id',
            'receipt_method_id'
        );
    }
}
