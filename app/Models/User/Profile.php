<?php

namespace App\Models\User;

use App\Models\Cart;
use App\Models\Model;
use App\Models\Order;
use App\Models\Role;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property ?string $name
 * @property ?string $address
 * @property ?CarbonInterface $birthday
 * @property int $roleId
 * @property ?string $avatar
 * @property ?string $vkontakteId
 * @property ?string $googleId
 * @property ?CarbonInterface $createdAt
 * @property ?CarbonInterface $updatedAt
 * @property ?Cart $cart
 * @property ?Identity $identity
 * @property Role $role
 */
class Profile extends Model
{
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
        return (static fn($o): ?static => $o)(static::query()
            ->where('name', '=', $name)
            ->first()
        );
    }

    public static function findByService(
        string     $authService,
        int|string $value
    ): ?static
    {
        return (static fn($o): ?static => $o)(static::query()
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

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class, 'profile_id');
    }

    public function getRelatedCart(): ?Cart
    {
        return $this->cart()->get()->all()[0] ?? null;
    }

    public function getSerializedBirthday(): mixed
    {
        return $this->jsonSerialize()['birthday'];
    }

    public function isAdmin(): bool
    {
        return $this->role()->first()?->getAttribute('name') === Role::ADMIN ?? false;
    }
}
