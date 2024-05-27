<?php

namespace App\Models\User;

use App\Events\PasswordResetTokenCreatedEvent;
use App\Models\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Event;

/**
 * @property int $id
 * @property string $token
 * @property int $identityId
 * @property boolean $active
 * @property Identity $identity
 */
class PasswordResetToken extends Model
{
    protected $table = 'password_reset_tokens';
    protected $fillable = [
        'token',
        'identity_id',
        'active'
    ];

    public static function boot(): void
    {
        parent::boot();

        static::created(static function(self $passwordResetToken): void {
            Event::dispatch(new PasswordResetTokenCreatedEvent(
                $passwordResetToken
            ));
        });
    }

    public function identity(): BelongsTo
    {
        return $this->belongsTo(Identity::class, 'identity_id');
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
