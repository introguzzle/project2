<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User\Profile;
use App\Services\Telegram\TelegramService;
use App\Utils\Auth;
use Illuminate\Contracts\View\View;

class AdminController extends Controller
{
    private TelegramService $telegramService;

    /**
     * @param TelegramService $telegramService
     */
    public function __construct(
        TelegramService $telegramService
    )
    {
        $this->telegramService = $telegramService;
    }


    public function showAdmin(): View
    {
        $profile = Auth::getProfile();
        return view('admin.admin', compact('profile'));
    }

    /**
     */
    public function getToken(): string
    {
        $token = $this->telegramService->generateToken(Auth::getProfile());
        $id = Auth::getProfile()?->identity->id;

        return "/auth $id " . $token->getAttribute('token');
    }

    public function showProfile(int $profileId): View
    {
        $profile = Profile::find($profileId);
        return view('admin.associated.profile', compact('profile'));
    }
}
