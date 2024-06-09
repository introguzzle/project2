<?php

namespace App\Models\User;

use App\Events\RegisteredEvent;
use App\Models\Core\Model;
use Carbon\CarbonInterface;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

/**
 * @property int $id
 * @property ?string $email
 * @property ?string $phone
 * @property ?string $password
 * @property ?CarbonInterface $emailVerifiedAt;
 * @property Profile $profile
 * @property ?string $rememberToken
 * @property ?CarbonInterface $createdAt;
 * @property ?CarbonInterface $updatedAt
 */

class Identity extends Model implements MustVerifyEmail, Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'email',
        'phone',
        'password',
        'remember_token'
    ];

    protected $hidden = [
        'password'
    ];

    public static function boot(): void
    {
        parent::boot();

        static::created(static function (self $identity) {
            if ($identity->email) {
                static::$dispatcher->dispatch(new RegisteredEvent($identity));
            }
        });
    }

    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'profile_id');
    }

    public function getAuthIdentifierName(): string
    {
        return 'id';
    }

    public function getAuthIdentifier(): ?string
    {
        return $this->getAttribute($this->getAuthIdentifierName());
    }

    public function getAuthPassword(): ?string
    {
        return $this->getAttribute('password');
    }

    public function getRememberToken(): ?string
    {
        return $this->getAttribute($this->getRememberTokenName());
    }

    public function setRememberToken($value): void
    {
        $this->forceFill([$this->getRememberTokenName() => $value]);
        $this->save();
    }

    public function getRememberTokenName(): string
    {
        return 'remember_token';
    }

    public function getEmailVerifiedName(): string
    {
        return 'email_verified_at';
    }

    public function hasVerifiedEmail(): bool
    {
        return $this->emailVerifiedAt !== null;
    }

    public function markEmailAsVerified(): void
    {
        $this->forceFill([$this->getEmailVerifiedName() => now()]);
        $this->save();
    }

    /**
     * @throws Exception
     */
    public function sendEmailVerificationNotification(): never
    {
        throw new RuntimeException('Unsupported method: sendEmailVerificationNotification');
    }

    public function getEmailForVerification(): ?string
    {
        return $this->email;
    }

    public function updatePassword(string $rawPassword): void
    {
        $this->forceFill(['password' => Hash::make($rawPassword)]);
        $this->save();
    }

    /**
     * @param string|int $credential Primary key or login or email
     * @return static|null
     */
    public static function findByCredential(string|int $credential): ?static
    {
        $builder = static::query()
            ->where('email', '=', $credential)
            ->orWhere('phone', '=', $credential);

        if (is_numeric($credential)) {
            $builder->orWhere('id', '=', (int) $credential);
        }

        return (static fn($static): ?static => $static)($builder->first());
    }

    /**
     * @param string|int $credential Primary key or login or email
     * @return Profile|null
     */

    public static function findProfile(string|int $credential): ?Profile
    {
        return static::findByCredential($credential)?->profile;
    }
}
