<?php

namespace App\Http\Controllers;

use App\DTO\UpdateProfileDTO;
use App\Http\Requests\UpdateProfileRequest;
use App\ModelView\ProfileView;
use App\Services\ProfileService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use App\Utils\Auth;
use Illuminate\Contracts\Foundation\Application as App;
use Illuminate\Support\Facades\Log;
use Throwable;


class ProfileController extends Controller
{
    private ProfileService $profileService;

    /**
     * @param ProfileService $profileService
     */
    public function __construct(
        ProfileService $profileService
    )
    {
        $this->profileService = $profileService;
    }


    public function index(): View|Application|Factory|App|RedirectResponse
    {
        $profile = Auth::getProfile();

        return view('profile', compact('profile'));
    }

    public function update(UpdateProfileRequest $request): JsonResponse
    {
        try {
            $this->profileService->update(
                Auth::getProfile(),
                UpdateProfileDTO::fromRequest($request)
            );
        } catch (Throwable $throwable) {
            Log::error($throwable);
            return response()
                ->json()
                ->setStatusCode(500)
                ->setData(['error' => 'Internal server error']);
        }

        return response()
            ->json()
            ->setData(['success' => 'Success']);
    }
}

