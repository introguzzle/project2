<?php

namespace App\Http\Controllers;

use App\DTO\PostOrderDTO;
use App\Http\Requests\OrderRequest;
use App\Services\CartService;
use App\Services\OrderService;
use App\Utils\Auth;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Log;
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


    public function index(): View
    {
        $profile = Auth::getProfile();
        $price = $this->cartService->getTotalAmount(Auth::getProfile());

        return view('checkout', compact('profile', 'price'));
    }

    public function order(
        OrderRequest $request
    ): View|Redirector|RedirectResponse
    {
        try {
            $this->orderService->order(
                Auth::getProfile(),
                PostOrderDTO::fromRequest($request)
            );

        } catch (Throwable $t) {
            Log::error($t);
            return redirect('checkout')
                ->with($this->internal());
        }

        return redirect('home');
    }
}
