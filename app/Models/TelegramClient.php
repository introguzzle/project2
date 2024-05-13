<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramClient extends Model
{
    use HasFactory, FindById;

    protected $table = 'telegram_clients';

    protected $fillable = [
        'chat_id',
        'first_name',
        'username',
        'type'
    ];
}
