<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class TelegramAccessToken extends Model
{
    use HasFactory, ModelTrait;

    protected $table = 'telegram_access_tokens';

    protected $fillable = [
        'token',
        'profile_id',
    ];

    public static function findToken(int $token): ?static
    {
        return (static::hint())(static::query()
            ->where('token', '=', $token)
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
