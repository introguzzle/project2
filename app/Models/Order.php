<?php

namespace App\Models;

use App\Events\OrderCreatedEvent;
use DateTimeInterface as DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property Product[] $products
 */

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone',
        'name',
        'address',
        'description',
        'total_quantity',
        'total_amount',
        'profile_id',
        'status_id'
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::created(function (self $order) {
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

    public function getRelatedProfile(): Profile
    {
        return $this->profile()->get()->all()[0];
    }

    public function getRelatedStatus(): Status
    {
        return $this->status()->get()->all()[0];
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
}
