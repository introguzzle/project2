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
use App\Http\Requests\TemporaryResourceRequest;
use App\Http\Requests\UpdateIdentityRequest;
use App\Models\PasswordResetToken;
use App\Models\Role;
use App\Services\IdentityService;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

use Illuminate\Support\Facades\Validator;
use Throwable;

class AuthController extends Controller
{
    private const string SUCCESS_LOGOUT_MESSAGE = 'Вы успешно вышли из системы';
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

            fn() => $this->identityService->updatePassword(PasswordResetDTO::fromRequest($request))
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
            $this->identityService->checkLoginPresence($login)
        );
    }

    public function authenticate(
        LoginRequest $request
    ): Redirector|RedirectResponse
    {
        return $this->try(
            '/home',
            'Вы успешно зашли в систему',
            'Не удалось проверить данные',

            fn() => $this->identityService->authenticate(LoginDTO::fromRequest($request))
        );
    }

    public function register(
        RegistrationRequest $request
    ): Redirector|RedirectResponse
    {
        dd($this);
        return $this->try(
            '/login',
            'Письмо для подтверждения было отправлено на вашу почту',
            'Не удалось создать аккаунт',

            fn() => $this->identityService->register(
                RegistrationDTO::fromRequest($request),
                Role::USER,
                true
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

            fn() => $this->identityService->updateIdentity(
                \App\Utils\Auth::getIdentity(),
                UpdateIdentityDTO::fromRequest($request)
            )
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
        } catch (Throwable $throwable) {
            Log::error($throwable);
            return Redirect::back()
                ->with($this->internal)
                ->withInput();
        }

        return Redirect::to($successRedirect)
            ->with($this->success($successMessage))
            ->withInput();
    }

    /**
     * @param RegistrationRequest $request
     * @return \Illuminate\Validation\Validator
     */
    public function getValidator(RegistrationRequest $request): \Illuminate\Validation\Validator
    {
        $messages = [
            'name.required' => 'Имя обязательно для заполнения',
            'name.max' => 'Имя не должно превышать 255 символов',

            'password.required' => 'Пароль обязателен для заполнения',
            'password.min' => 'Пароль должен быть не менее 4 символов',

            'password_confirmation.required' => 'Подтверждение пароля обязательно для заполнения',
            'password_confirmation.same' => 'Пароли не совпадают'
        ];

        $rules = [
            'name' => [
                'required',
                'max:255',
            ],
            'password' => [
                'required',
                'min:4',
            ],
            'password_confirmation' => [
                'required',
                'same:password'
            ]
        ];

        $validator = Validator::make(
            $request->all(),
            $rules,
            $messages
        );
        return $validator;
    }
}
