<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Profile extends Model
{
    use HasFactory, ModelTrait;

    protected $fillable = [
        'name',
        'address',
        'birthday',
        'role_id'
    ];

    protected $casts = [
        'birthday' => 'date:d-m-Y',
    ];

    public function identity(): HasOne
    {
        return $this->hasOne(Identity::class, 'profile_id');
    }

    /**
     * @return Identity
     */

    public function getRelatedIdentity(): Identity
    {
        return $this->identity()->get()->all()[0];
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

    /**
     * @return Cart|null
     */

    public function getRelatedCart(): ?Cart
    {
        return $this->cart()->get()->all()[0] ?? null;
    }

    public function getId(): mixed
    {
        return $this->getAttribute('id');
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
