<?php

namespace App\Http\Controllers;

use App\DTO\LoginDTO;
use App\DTO\PasswordResetDTO;
use App\DTO\RegistrationDTO;
use App\DTO\UpdateIdentityDTO;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\PasswordResetRequest;
use App\Http\Requests\RegistrationRequest;
use App\Http\Requests\UpdateIdentityRequest;
use App\Models\PasswordResetToken;
use App\Services\IdentityService;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Throwable;
use Illuminate\Contracts\Foundation\Application as App;

class AuthController extends Controller
{
    private const string SUCCESS_REGISTRATION_MESSAGE = 'Письмо для подтверждения было отправлено на вашу почту';
    private const string SUCCESS_LOGIN_MESSAGE = 'Вы успешно зашли в систему';
    private const string SUCCESS_LOGOUT_MESSAGE = 'Вы успешно вышли из системы';
    private const string SUCCESS_VERIFY_EMAIL_MESSAGE = 'Вы успешно подтвердили почту';

    private IdentityService $identityService;

    public function __construct(
        IdentityService $identityService
    )
    {
        $this->identityService = $identityService;
    }

    public function showLoginForm(): View|Application|Factory|App
    {
        return view('login');
    }

    public function showRegistrationForm(): View|Application|Factory|App
    {
        return view('registration');
    }

    public function showForgotPasswordForm(): View|Application|Factory|App
    {
        return view('forgot');
    }

    public function showForgotPasswordSuccess(): View|Application|Factory|App
    {
        return view('forgot-success');
    }

    public function sendPasswordResetLink(
        ForgotPasswordRequest $request
    ): Application|Redirector|App|RedirectResponse
    {
        if ($this->identityService->sendPasswordResetLink($request->getLoginInput())) {
            return redirect('/forgot/success');

        } else {
            return back()->with('fail', 'Что-то пошло не так. Пожалуйста, попробуйте еще раз');
        }
    }

    public function forwardPasswordResetFromTemporaryLink(
        Request $request,
        string $token
    ): View|Application|Factory|App
    {
        if (!URL::signatureHasNotExpired($request)) {
            $this->abortExpired();
        }

        try {
            if (!$this->identityService->isValidToken($token)) {
                $this->abortNotFound();
            }
        } catch (Throwable) {
            $this->abortNotFound();
        }

        return view('forgot-proceed', compact('token'));
    }

    /**
     * POST
     * @param PasswordResetRequest $request
     * @return Application|Redirector|RedirectResponse|App
     */

    public function handlePasswordReset(
        PasswordResetRequest $request
    ): Application|Redirector|RedirectResponse|App
    {
        if ($request->getPasswordInput() !== $request->getPasswordConfirmationInput()) {
            return back()->with($this->error('Пароли должны совпадать'));
        }

        $redirect = redirect('/login');

        try {
            $this->identityService->updatePasswordWithToken(PasswordResetDTO::fromRequest($request));
        } catch (Throwable) {
            return $redirect->with($this->internal);
        }

        return $redirect->with($this->success('Пароль успешно сброшен'));
    }

    /**
     * GET METHOD
     * @param Request $request
     * @param string $id
     * @param string $hash
     * @return Application|Redirector|RedirectResponse|App
     */
    public function verifyEmail(
        Request $request,
        string $id,
        string $hash
    ): Application|Redirector|RedirectResponse|App
    {
        if (!URL::signatureHasNotExpired($request)) {
            $this->abortExpired();
        }

        if ($this->identityService->verifyEmail($id, $hash)) {
            return redirect('/login')
                ->with($this->success(self::SUCCESS_VERIFY_EMAIL_MESSAGE));

        } else {
            $this->abortNotFound();
        }
    }

    /**
     * JSON GET
     * @param Request $request
     * @return JsonResponse
     */

    public function checkLoginPresence(
        Request $request
    ): JsonResponse
    {
        $login = $request->input('login');

        return response()->json()->setData(
            $this->identityService->checkLoginPresence($login)
        );
    }

    public function authenticate(
        LoginRequest $request
    ): Application|Redirector|RedirectResponse|App
    {
        try {
            $result = $this->identityService->authenticate(LoginDTO::fromRequest($request));

            if (!$result) {
                return redirect('login')->withErrors([
                    'login' => 'Не удалось проверить данные',
                ]);
            }

        } catch (Throwable) {
            return redirect('login')->with($this->internal);
        }

        return redirect('/')
            ->with($this->success(self::SUCCESS_LOGIN_MESSAGE));
    }

    public function register(
        RegistrationRequest $request
    ): Application|Redirector|RedirectResponse|App
    {
        try {
            $this->identityService->register(RegistrationDTO::fromRequest($request));
        } catch (Throwable) {
            return redirect('registration')->with($this->internal);
        }

        return redirect('login')
            ->with($this->success(self::SUCCESS_REGISTRATION_MESSAGE));
    }

    public function logout(Request $request): Application|Redirector|RedirectResponse|App
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')
            ->with($this->success(self::SUCCESS_LOGOUT_MESSAGE));
    }

    public function update(
        UpdateIdentityRequest $request
    ): Application|Redirector|RedirectResponse|App
    {
        $redirect = redirect('/profile');

        if ($request->getNewPasswordInput() !== $request->getNewPasswordConfirmationInput()) {
            return $redirect->with($this->error('Пароли не совпадают'));
        }

        try {
            $this->identityService->updateIdentity(
                \App\Utils\Auth::getIdentity(),
                UpdateIdentityDTO::fromRequest($request)
            );

        } catch (Throwable) {
            return $redirect->with($this->internal);
        }

        return $redirect->with($this->success('Пароль успешно обновлен'));
    }
}
