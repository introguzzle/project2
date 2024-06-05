<?php

namespace App\Models;

use App\Models\Core\Pivot;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $quantity
 *
 * @property ReceiptMethod $receiptMethod
 * @property PaymentMethod $paymentMethod
 *
 * @property int $receiptMethodId
 * @property int $paymentMethodId
 *
 * @property ?CarbonInterface $createdAt
 * @property ?CarbonInterface $updatedAt
 */
class Flow extends Pivot
{
    protected $table = 'flows';
    protected $primaryKey = [
        'receipt_method_id',
        'payment_method_id'
    ];

    protected $fillable = [
        'receipt_method_id',
        'payment_method_id'
    ];

    public function receiptMethod(): BelongsTo
    {
        return $this->belongsTo(ReceiptMethod::class, 'receipt_method_id');
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }
}