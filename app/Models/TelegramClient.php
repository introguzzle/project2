<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TelegramClient extends Model
{
    use HasFactory;

    protected $table = 'telegram_clients';

    protected $fillable = [
        'chat_id',
        'first_name',
        'username',
        'type',
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
        return (fn($o): ?static => $o)(static::query()
            ->where('profile_id', '=', $profile->getId())
            ->first()
        );
    }

    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'profile_id');
    }

    public function getRelatedProfile(): ?Profile
    {
        $collection = $this->profile()->get();
        return $collection->isEmpty() ? null : $collection->first();
    }

    public function hasAccess(): bool
    {
        return $this->getAttribute('has_access');
    }

    public static function findByChatId(int|string $chatId): ?static
    {
        return (fn($o): ?static => $o)(static::query()
            ->where('chat_id', '=', (int)$chatId)
            ->first()
        );
    }

    public function setAccess(bool $value): bool
    {
        return $this->update(['has_access' => $value]);
    }

    public function grantAccess(): bool
    {
        return $this->setAccess(true);
    }

    public function restrict(): bool
    {
        return $this->setAccess(false);
    }
}
