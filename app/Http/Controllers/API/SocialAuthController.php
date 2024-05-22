<?php

namespace App\Http\Controllers\API;

use App\DTO\RegistrationDTO;
use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Services\IdentityService;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\Provider;
use Laravel\Socialite\Facades\Socialite;
use SocialiteProviders\Manager\OAuth2\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Throwable;

abstract class SocialAuthController extends Controller
{
    protected IdentityService $identityService;

    /**
     * @param IdentityService $identityService
     */
    public function __construct(IdentityService $identityService)
    {
        $this->identityService = $identityService;
    }

    public abstract function getDriverName(): string;

    public function handleProviderCallback(
    ): RedirectResponse|Redirector
    {
        try {
            $user = $this->getUser();
        } catch (Throwable $t) {
            Log::error($t);
            return redirect('/login')->with($this->internal);
        }

        $profile = Profile::findByService(
            $this->getDriverName(),
            $user->id
        );

        if (!$profile) {
            $dto = new RegistrationDTO(
                $user->getName(),
                null,
                null,
                Str::random()
            );

            $this->identityService->registerViaService(
                $dto,
                $this->getDriverName(),
                $user->id
            );
        }

        $profile = Profile::findByService(
            $this->getDriverName(),
            $user->id
        );

        Auth::login($profile->getRelatedIdentity(), true);

        return redirect()->intended();
    }

    public function getManager(): Provider
    {
        return Socialite::driver($this->getDriverName());
    }

    public function getUser(): \Laravel\Socialite\Contracts\User|User
    {
        return $this->getManager()->user();
    }

    public function redirectToProvider(
        Request $request
    ): RedirectResponse|\Illuminate\Http\RedirectResponse
    {
        return $this->getManager()->redirect();
    }
}
