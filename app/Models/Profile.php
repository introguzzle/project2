<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'birthday',
        'role_id',
        'avatar',
        'vkontakte_id',
        'google_id'
    ];

    protected $casts = [
        'birthday' => 'date:d-m-Y',
    ];
    public static function findByName(string $name): ?static
    {
        return (fn($o): ?static => $o)(static::query()
            ->where('name', '=', $name)
            ->first()
        );
    }

    public static function findByService(
        string     $authService,
        int|string $value
    ): ?static
    {
        return (fn($o): ?static => $o)(static::query()
            ->where($authService . '_id', '=', $value)
            ->first()
        );
    }

    public function identity(): HasOne
    {
        return $this->hasOne(Identity::class, 'profile_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'profile_id');
    }

    /**
     * @return Order[]
     */

    public function getRelatedOrders(): array
    {
        return $this->getAttribute('orders')->all();
    }

    /**
     * @return Identity
     */
    public function getRelatedIdentity(): Identity
    {
        return $this->identity()->get()->first();
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * @return Role
     */

    public function getRelatedRole(): Role
    {
        return $this->role()->get()->all()[0];
    }

    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class, 'profile_id');
    }

    public function getRelatedCart(): ?Cart
    {
        return $this->cart()->get()->all()[0] ?? null;
    }

    public function getName(): mixed
    {
        return $this->getAttribute('name');
    }

    public function getBirthday(): mixed
    {
        return $this->getAttribute('birthday');
    }

    public function getAddress(): ?string
    {
        return $this->getAttribute('address');
    }

    public function getSerializedBirthday(): mixed
    {
        return $this->jsonSerialize()['birthday'];
    }

    public function isAdmin(): bool
    {
        return $this->role()->first()->getAttribute('name') === Role::ADMIN;
    }
}
