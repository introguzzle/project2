<?php

namespace App\Http\Controllers;

use App\DTO\PostOrderDTO;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
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
    private OrderService $orderService;
    private CartService $cartService;

    /**
     * @param OrderService $orderService
     * @param CartService $cartService
     */
    public function __construct(
        OrderService $orderService,
        CartService $cartService
    )
    {
        $this->orderService = $orderService;
        $this->cartService = $cartService;
    }


    public function index(): View|Application|Factory|App
    {
        $profile = Auth::getProfile();
        $price = $this->cartService->computePriceByProfile(Auth::getProfile());

        return view('checkout', compact('profile', 'price'));
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

        } catch (Throwable $t) {
            return redirect('checkout')->with(
                'internal', $t->getMessage()
            );
        }

        return redirect('home');
    }
}
