<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class TelegramAccessToken extends Model
{
    use HasFactory;

    protected $table = 'telegram_access_tokens';

    protected $fillable = [
        'token',
        'profile_id',
    ];

    public static function findToken(int|string $token): ?static
    {
        return (fn($o): ?static => $o)(static::query()
            ->where('token', '=', $token)
            ->first());
    }

    /**
     * @param Profile $profile
     * @return static|null
     */

    public static function findByProfile(Profile $profile): ?static
    {
        return (fn($o): ?static => $o)(static::query()
            ->where('profile_id', '=', $profile->getId())
            ->first());
    }

    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'profile_id');
    }

    public function getRelatedProfile(): Profile
    {
        return (fn($o): Profile => $o)($this->profile()->get()->all()[0]);
    }
}
