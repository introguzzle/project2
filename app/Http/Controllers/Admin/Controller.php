<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Dashboard\FaviconUpdateRequest;
use App\Http\Requests\Admin\Dashboard\UpdateIdentityRequest;
use App\Http\Requests\Admin\Dashboard\UpdateProfileRequest;
use App\Models\User\Identity;
use App\Other\Auth;
use App\Services\Telegram\TelegramService;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Storage;

class Controller extends \App\Http\Controllers\Core\Controller
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


    public function showDashboard(): View
    {
        $profile = Auth::getProfile();
        return view('admin.dashboard', compact('profile'));
    }


    public function showFaviconUpdate(): View
    {
        return view('admin.favicon.update');
    }

    public function updateFavicon(FaviconUpdateRequest $request): RedirectResponse
    {
        if ($request->hasFile('favicon')) {
            $favicon = $request->favicon;
            $filesystem = Storage::disk('public');

            if ($filesystem->exists($favicon)) {
                $filesystem->delete('favicon.ico');
            }

            $favicon->storeAs('public', 'favicon.ico');
            return $this->back()->with($this->success('Иконка была успешно обновлена'));
        }

        return $this->back()->with($this->internal('Не удалось обновить иконку'));
    }

    public function showToken(): View
    {
        $token = $this->telegramService->generateToken(Auth::getProfile())->token;

        $id = Auth::getProfile()?->identity->id;
        $command = "/auth $id " . $token;

        return view('admin.main.token', compact('token', 'command'));
    }

    public function resetToken(): RedirectResponse
    {
        $this->telegramService->generateToken(Auth::getProfile(), true);

        return redirect()->route('admin.dashboard.token');
    }

    public function resetAllTokens(): RedirectResponse
    {
        $this->telegramService->resetAll();

        return redirect()->route('admin.dashboard.token');
    }

    public function updateProfile(UpdateProfileRequest $request): View|Redirector|RedirectResponse
    {
        /**
         * @var Identity $identity
         */

        $identity = $request->user();
        $profile = $identity->profile;

        $profile->name = $request->name;
        $profile->birthday = $request->birthday;
        $profile->address = $request->address;

        $identity->phone = $request->phone;
        $identity->email = $request->email;

        $profile->save();
        $identity->save();

        return $this->back()->with($this->success('Профиль был успешно обновлен'));
    }

    public function showUpdatePassword(): View
    {
        return view('admin.main.update-password');
    }

    public function updatePassword(UpdateIdentityRequest $request): View|Redirector|RedirectResponse
    {
        $request->user()?->updatePassword($request->newPassword);

        return $this->back()->with($this->success('Пароль был успешно обновлен'));
    }

    public function logout(Request $request): View|Redirector|RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with($this->success('Вы успешно вышли'));
    }
}
