<?php

namespace App\Http\Controllers;

use App\DTO\LoginDTO;
use App\DTO\RegistrationDTO;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Services\LoginService;
use App\Services\RegistrationService;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Throwable;
use Illuminate\Contracts\Foundation\Application as App;

class AuthController extends Controller
{
    private const string SUCCESS_REGISTRATION_MESSAGE = 'Вы успешно зарегистрировались. Добро пожаловать';
    private const string SUCCESS_LOGIN_MESSAGE = 'Вы успешно зашли в систему';
    private const string SUCCESS_LOGOUT_MESSAGE = 'Вы успешно вышли из системы';

    private RegistrationService $registrationService;
    private LoginService $loginService;

    public function __construct(
        RegistrationService $registrationService,
        LoginService $loginService
    )
    {
        $this->registrationService = $registrationService;
        $this->loginService = $loginService;
    }

    public function viewLogin(): View|Application|Factory|App
    {
        return view('login');
    }

    public function viewRegistration(): View|Application|Factory|App
    {
        return view('registration');
    }

    public function isLoginPresent(
        Request $request
    ): JsonResponse
    {
        $login = $request->input('login');

        return response()->json()->setData(
            $this->registrationService->isLoginPresent($login)
        );
    }

    public function login(
        LoginRequest $request
    ): Application|Redirector|RedirectResponse|App
    {

        try {
            $result = $this->loginService->login(LoginDTO::fromRequest($request));

            if (!$result) {
                return redirect('login')->withErrors([
                    'login' => 'Не удалось проверить данные',
                ]);
            }

        } catch (Throwable) {
            return redirect('login')->with(
                ['internal' => 'Внутренняя ошибка сервера']
            );
        }

        return redirect('/')
            ->with($this->success(self::SUCCESS_LOGIN_MESSAGE));
    }

    public function register(RegistrationRequest $request): Application|Redirector|RedirectResponse|App
    {
        try {
            $this->registrationService->register(RegistrationDTO::fromRequest($request));
        } catch (Throwable) {
            return redirect("registration")->with(
                ['internal' => 'Внутренняя ошибка сервера']
            );
        }

        return redirect("login")
            ->with($this->success(self::SUCCESS_REGISTRATION_MESSAGE));
    }

    public function logout(): Application|Redirector|RedirectResponse|App
    {
        Session::flush();
        Auth::logout();

        return redirect('login')
            ->with($this->success(self::SUCCESS_LOGOUT_MESSAGE));
    }

    public function success(string $message): array
    {
        return ['success' => $message];
    }
}
