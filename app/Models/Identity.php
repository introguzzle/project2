<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Identity extends Model implements Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'login',
        'password'
    ];

    protected $hidden = [
        'password'
    ];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'profile_id');
    }

    public function getAuthIdentifierName(): string
    {
        return 'login';
    }

    public function getAuthIdentifier(): string
    {
        return $this->getAttribute('login');
    }

    public function getAuthPassword(): string
    {
        return $this->getAttribute('password');
    }

    public function getRememberToken(): string
    {
        return $this->getAttribute($this->getRememberTokenName());
    }

    public function setRememberToken($value): void
    {
        $this->setAttribute($this->getRememberTokenName(), $value);
    }

    public function getRememberTokenName(): string
    {
        return 'remember_token';
    }
}
