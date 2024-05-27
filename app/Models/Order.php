<?php

namespace App\Models;

use App\Events\OrderCreatedEvent;
use App\Models\User\Profile;
use Carbon\CarbonInterface;
use DateTimeInterface as DateTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $phone
 * @property string $address
 * @property string $description
 * @property int $totalQuantity
 * @property float $totalAmount
 * @property PaymentMethod $paymentMethod
 * @property DeliveryMethod $deliveryMethod
 *
 * @property Status $status
 * @property Profile $profile
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
        'payment_method_id',
        'delivery_method_id',
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

    public function products(): BelongsToMany
    {
        return $this
            ->belongsToMany(Product::class, 'order_product')
            ->withPivot('quantity')
            ->using(OrderProduct::class);
    }

    public function getPhone(): string
    {
        return $this->getAttribute('phone');
    }

    public function getName(): string
    {
        return $this->getAttribute('name');
    }

    public function getAddress(): string
    {
        return $this->getAttribute('address');
    }

    public function getTotalAmount(): float
    {
        return $this->getAttribute('total_amount');
    }

    public function getTotalQuantity(): int
    {
        return $this->getAttribute('total_quantity');
    }

    public function getStatusId(): int
    {
        return $this->getAttribute('status_id');
    }

    public function getProfileId(): int
    {
        return $this->getAttribute('profile_id');
    }

    protected function serializeDate(DateTime $date): string
    {
        return $date->format('Y-m-d H:i');
    }

    public function updateStatus(
        Status|int|string $status,
    ): bool
    {
        $id = match(true) {
            $status instanceof Status => $status->getId(),
            is_numeric($status) => (int) $status,
            is_string($status)  => Status::findByName($status)->getId()
        };

        return $this->update(['status_id' => $id]);
    }

    public function updateDescription(string $description): bool
    {
        return $this->update(['description' => $description]);
    }
}
