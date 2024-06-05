<?php

namespace App\Http\Controllers\Client;

use App\DTO\UpdateProfileDTO;
use App\Http\Controllers\Core\Controller;
use App\Http\Requests\Client\UpdateProfileRequest;
use App\Other\Auth;
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
                $request->user()->profile,
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

