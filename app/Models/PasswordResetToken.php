<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PasswordResetToken extends Model
{
    use HasFactory;

    protected $table = 'password_reset_tokens';

    protected $fillable = [
        'token',
        'identity_id',
        'active'
    ];

    public function identity(): BelongsTo
    {
        return $this->belongsTo(Identity::class, 'identity_id');
    }

    public function getIdentityId(): int
    {
        return $this->getAttribute('identity_id');
    }

    public function isActive(): bool
    {
        return $this->getAttribute('active');
    }

    public function setExpired(): void
    {
        $this->forceFill(['active' => false]);
        $this->save();
    }

    public static function isValid(string $token): bool
    {
        return static::query()
            ->where('token', '=', $token)
            ->where('active', '=', true)
            ->exists();
    }
}
