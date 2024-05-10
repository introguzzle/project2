<?php

namespace App\Models;

use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Identity extends Model implements MustVerifyEmail, Authenticatable
{
    use HasFactory, Notifiable, FindById;

    protected $fillable = [
        'login',
        'password',
        'remember_token'
    ];

    protected $hidden = [
        'password'
    ];

    public function getLogin(): ?string
    {
        return $this->getAttribute('login');
    }

    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'profile_id');
    }

    public function getAuthIdentifierName(): string
    {
        return 'login';
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

    public function updatePassword(string $newPassword): void
    {
        $this->forceFill(['password' => Hash::make($newPassword)]);
        $this->save();
    }
}
