<?php

namespace App\Models;

use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Identity extends Model implements MustVerifyEmail, Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'email',
        'phone',
        'password',
        'remember_token'
    ];

    protected $hidden = [
        'password'
    ];

    public function getEmail(): ?string
    {
        return $this->getAttribute('email');
    }

    public function getPhone(): ?string
    {
        return $this->getAttribute('phone');
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
        return $this->getAttribute($this->getEmailVerifiedName()) !== null;
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
        throw new Exception('Unsupported method');
    }

    public function getEmailForVerification(): ?string
    {
        return $this->getAuthIdentifier();
    }

    public function updatePassword(string $rawPassword): void
    {
        $this->forceFill(['password' => Hash::make($rawPassword)]);
        $this->save();
    }

    public function getRelatedProfile(): Profile
    {
        return $this->profile()->get()->first();
    }

    public static function findProfile(string $credential): ?Profile
    {
        return (fn($o): ?Identity => $o)(Identity::query()
            ->where('email', '=', $credential)
            ->orWhere('phone', '=', $credential)
            ->orWhere('id', '=', $credential)
            ->first()
        )?->getRelatedProfile();
    }
}
