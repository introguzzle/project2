<?php

namespace App\Models;

use DateTimeInterface as DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory, FindById;

    protected $fillable = [
        'phone',
        'name',
        'address',
        'price',
        'profile_id',
        'status_id'
    ];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'profile_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(OrderProduct::class);
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

    public function getPrice(): float
    {
        return $this->getAttribute('price');
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
