<?php

namespace App\Models\Telegram;

use App\Models\Model;
use App\Models\User\Profile;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $token
 * @property int $profileId
 * @property Profile $profile
 */
class TelegramAccessToken extends Model
{
    protected $table = 'telegram_access_tokens';
    protected $fillable = [
        'token',
        'profile_id',
    ];

    public static function findToken(int|string $token): ?static
    {
        return (static fn($o): ?static => $o)(static::query()
            ->where('token', '=', $token)
            ->first());
    }

    /**
     * @param Profile $profile
     * @return static|null
     */

    public static function findByProfile(Profile $profile): ?static
    {
        return (static fn($o): ?static => $o)(static::query()
            ->where('profile_id', '=', $profile->getId())
            ->first());
    }

    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'profile_id');
    }
}
