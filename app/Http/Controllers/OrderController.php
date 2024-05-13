<?php

namespace App\Http\Controllers;

use App\DTO\PostOrderDTO;
use App\Http\Requests\OrderRequest;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\ProfileService;
use App\Utils\Auth;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\Foundation\Application as App;
use Illuminate\Routing\Redirector;
use Throwable;

class OrderController extends Controller
{
    private ProfileService $profileService;
    private OrderService $orderService;
    private CartService $cartService;

    /**
     * @param ProfileService $profileService
     * @param OrderService $orderService
     * @param CartService $cartService
     */
    public function __construct(
        ProfileService $profileService,
        OrderService $orderService,
        CartService $cartService
    )
    {
        $this->profileService = $profileService;
        $this->orderService = $orderService;
        $this->cartService = $cartService;
    }


    public function index(): View|Application|Factory|App
    {
        $profileView = $this->profileService->createProfileViewByProfile(Auth::getProfile());
        $price = $this->cartService->computePriceByProfile(Auth::getProfile());

        return view('checkout', compact('profileView', 'price'));
    }

    public function order(
        OrderRequest $request
    ): View|Application|Factory|Redirector|App|RedirectResponse
    {
        try {
            $this->orderService->order(
                Auth::getProfile(),
                PostOrderDTO::fromRequest($request)
            );

        } catch (Throwable) {
            return redirect('checkout')->with(
                'internal', 'Internal server error'
            );
        }

        return redirect('home');
    }
}
