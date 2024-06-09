<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Core\Controller;
use App\Http\Requests\Client\UpdateProfileRequest;
use App\Other\Authentication;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Throwable;


class ProfileController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $profile = Authentication::profile();

        $data = compact('profile');
        return viewClient('profile2')->with($data);
    }

    public function update(UpdateProfileRequest $request): JsonResponse
    {
        try {
            $profile = $request->user()->profile;

            $profile->address = $request->address;
            $profile->birthday = $request->birthday;
            $profile->name = $request->name;

            $profile->save();

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

