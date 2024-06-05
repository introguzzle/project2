<?php

namespace App\Models;

use App\Events\OrderCreatedEvent;
use App\Models\Core\Model;
use App\Models\User\Profile;

use Carbon\CarbonInterface;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use JetBrains\PhpStorm\ExpectedValues;

/**
 * @property int $id
 *
 * @property string $name
 * @property string $phone
 * @property string $address
 * @property string $description
 *
 * @property int $totalQuantity
 * @property float $totalAmount
 *
 * @property ReceiptMethod $receiptMethod
 * @property PaymentMethod $paymentMethod
 * @property Status $status
 * @property Profile $profile
 *
 * @property int $receiptMethodId
 * @property int $paymentMethodId
 * @property int $statusId
 * @property int $profileId
 *
 * @property Collection<Product> $products
 *
 * @property ?CarbonInterface $createdAt
 * @property ?CarbonInterface $updatedAt
 */

class Order extends Model
{
    protected $fillable = [
        'phone',
        'name',
        'address',
        'description',
        'total_quantity',
        'total_amount',
        'profile_id',
        'status_id',

        'receipt_method_id',
        'payment_method_id',
    ];

    public static function boot(): void
    {
        parent::boot();

        static::created(static function (self $order): void {
            event(new OrderCreatedEvent($order));
        });
    }

    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'profile_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function receiptMethod(): BelongsTo
    {
        return $this->belongsTo(ReceiptMethod::class, 'receipt_method_id');
    }

    public function products(): BelongsToMany
    {
        return $this
            ->belongsToMany(Product::class, 'order_product')
            ->withPivot('quantity')
            ->using(OrderProduct::class);
    }

    #[ExpectedValues(values: [], valuesFromClass: Order::class)]
    public function updateStatus(
        Status|int|string $status,
    ): bool
    {
        $id = match(true) {
            $status instanceof Status => $status->id,
            is_numeric($status)       => (int) $status,
            is_string($status)        => Status::findByName($status)->id
        };

        return $this->update(['status_id' => $id]);
    }

    public function updateDescription(string $description): bool
    {
        return $this->update(['description' => $description]);
    }
}
