<?php

namespace App\Http\Controllers\Client;

use App\DTO\LoginDTO;
use App\DTO\PasswordResetDTO;
use App\DTO\RegistrationDTO;
use App\Http\Controllers\Core\Controller;
use App\Http\Requests\Client\ForgotPasswordRequest;
use App\Http\Requests\Client\LoginRequest;
use App\Http\Requests\Client\PasswordResetRequest;
use App\Http\Requests\Client\RegistrationRequest;
use App\Http\Requests\Client\TemporaryResourceRequest;
use App\Http\Requests\Client\UpdateIdentityRequest;
use App\Models\Role;
use App\Models\User\Identity;
use App\Models\User\PasswordResetToken;
use App\Services\Auth\EmailNotVerifiedException;
use App\Services\Auth\IdentityService;
use App\Services\Auth\InvalidCredentialsException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Throwable;

class AuthController extends Controller
{
    private const string SUCCESS_LOGOUT_MESSAGE = 'Вы успешно вышли';
    private const string SUCCESS_VERIFY_EMAIL_MESSAGE = 'Вы успешно подтвердили почту';

    private IdentityService $identityService;

    public function __construct(
        IdentityService $identityService
    )
    {
        $this->identityService = $identityService;
    }

    public function showLoginForm(): View
    {
        return view('login');
    }

    public function showRegistrationForm(): View
    {
        return view('registration');
    }

    public function showForgotPasswordForm(): View
    {
        return view('forgot');
    }

    public function showForgotPasswordSuccess(): View
    {
        return view('forgot-success');
    }

    public function requestPasswordReset(
        ForgotPasswordRequest $request
    ): Redirector|RedirectResponse
    {
        return $this->try(
            '/forgot-password/success',
            'Письмо успешно отправлено',
            'Не удалось отправить письмо для сброса пароля',

            fn() => $this->identityService->requestPasswordReset($request->getLoginInput())
        );
    }

    public function showPasswordResetForm(
        TemporaryResourceRequest $request,
        string                   $token
    ): View
    {
        if (!PasswordResetToken::isValid($token)) {
            $this->abortNotFound();
        }

        return view('forgot-proceed', compact('token'));
    }

    /**
     * POST
     * @param PasswordResetRequest $request
     * @return Redirector|RedirectResponse
     */

    public function resetPassword(
        PasswordResetRequest $request
    ): Redirector|RedirectResponse
    {
        return $this->try(
            '/login',
            'Пароль успешно обновлен',
            'Не удалось обновить пароль',

            fn() => $this->identityService->updatePasswordWithToken(PasswordResetDTO::fromRequest($request))
        );
    }

    /**
     * GET METHOD
     * @param TemporaryResourceRequest $request
     * @param string $id
     * @param string $hash
     * @return Redirector|RedirectResponse
     */
    public function verifyEmail(
        TemporaryResourceRequest $request,
        string $id,
        string $hash
    ): Redirector|RedirectResponse
    {
        if (!$this->identityService->verifyEmail($id, $hash)) {
            $this->abortNotFound();
        }

        return redirect('/login')
            ->with($this->success(self::SUCCESS_VERIFY_EMAIL_MESSAGE));
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
            (bool) Identity::findProfile($login) !== null
        );
    }

    public function authenticate(
        LoginRequest $request
    ): Redirector|RedirectResponse
    {
        try {
            $this->identityService->authenticate(LoginDTO::fromRequest($request));
        } catch (EmailNotVerifiedException) {
            return Redirect::back()->with([
                'notVerified' => 'Ваш адрес электронной почты не подтверждён. Если вы не получили письмо, нажмите здесь, чтобы отправить его снова.'
            ]);
        } catch (InvalidCredentialsException) {
            return Redirect::back()->with($this->fail('Не удалось проверить данные'));
        }

        return redirect()->intended('/home');
    }

    public function register(
        RegistrationRequest $request
    ): Redirector|RedirectResponse
    {
        return $this->try(
            '/login',
            'Письмо для подтверждения было отправлено на вашу почту',
            'Не удалось создать аккаунт',

            fn() => $this->identityService->register(
                RegistrationDTO::fromRequest($request),
                Role::USER
            )
        );
    }

    public function logout(
        Request $request
    ): Redirector|RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')
            ->with($this->success(self::SUCCESS_LOGOUT_MESSAGE));
    }

    public function update(
        UpdateIdentityRequest $request
    ): Redirector|RedirectResponse
    {
        return $this->try(
            '/profile',
            'Пароль успешно обновлен',
            'Не удалось обновить пароль',

            fn() => $request->user()->updatePassword($request->newPassword)
        );
    }

    /**
     * Выполняет переданный callback и перенаправляет в зависимости от результата.
     *
     * @param string $successRedirect URL для перенаправления при успешном выполнении.
     * @param string $successMessage Сообщение об успехе.
     * @param string $failMessage Сообщение об ошибке.
     * @param callable(): false $callback
     * @return Redirector|RedirectResponse
     */

    public function try(
        string   $successRedirect,
        string   $successMessage,
        string   $failMessage,
        callable $callback
    ): Redirector|RedirectResponse
    {
        try {
            $return = $callback();
            if ($return === false || $return === null) {
                return Redirect::back()
                    ->with($this->fail($failMessage))
                    ->withInput();
            }
        } catch (Throwable) {
            return Redirect::back()
                ->with($this->internal())
                ->withInput();
        }

        return Redirect::to($successRedirect)
            ->with($this->success($successMessage))
            ->withInput();
    }
}
