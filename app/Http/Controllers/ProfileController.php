<?php

namespace App\Http\Controllers;

use App\Services\ProfileService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Foundation\Application as App;


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
        $authenticatable = Auth::user();

        if (!$authenticatable) {
            return redirect('404');
        }

        $profileView = $this->profileService->acquireProfileView(
            $authenticatable
        );

        return view('profile', compact('profileView'));
    }
}

