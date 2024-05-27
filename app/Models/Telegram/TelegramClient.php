<?php

namespace App\Models\Telegram;

use App\Models\Model;
use App\Models\User\Profile;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property ?string $chatId
 * @property ?string $firstName
 * @property ?string $secondName
 * @property ?string $type
 * @property bool $hasAccess
 * @property bool $banned
 * @property ?int $profileId
 * @property ?Profile $profile
 */
class TelegramClient extends Model
{
    protected $table = 'telegram_clients';

    protected $fillable = [
        'chat_id',
        'first_name',
        'username',
        'type',
        'banned',
        'has_access',
        'profile_id'
    ];

    /**
     * @return static[]
     */
    public static function allWithAccess(): array
    {
        return static::query()
            ->where('has_access', '=', true)
            ->get()
            ->all();
    }

    public static function findByProfile(Profile $profile): ?static
    {
        return (static fn($o): ?static => $o)(static::query()
            ->where('profile_id', '=', $profile->id)
            ->first()
        );
    }

    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'profile_id');
    }

    public function hasAccess(): bool
    {
        return (bool) $this->getAttribute('has_access');
    }

    public static function findByChatId(string $chatId): ?static
    {
        return (static fn($o): ?static => $o)(static::query()
            ->where('chat_id', '=', $chatId)
            ->first()
        );
    }

    public function setAccess(bool $value): bool
    {
        return $this->update(['has_access' => $value]);
    }
}
