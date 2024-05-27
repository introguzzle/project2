<?php

namespace App\Http\Controllers;

use App\Utils\Auth;
use App\DTO\UpdateProfileDTO;
use App\Http\Requests\UpdateProfileRequest;
use App\Services\ProfileService;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

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


    public function index(): View|RedirectResponse
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
        } catch (Throwable) {
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

