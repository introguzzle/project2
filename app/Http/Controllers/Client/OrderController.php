<?php

namespace App\Http\Controllers\Client;

use App\DTO\PostOrderDTO;
use App\Http\Controllers\Core\Controller;
use App\Http\Requests\Client\PostOrderRequest;
use App\Models\PaymentMethod;
use App\Models\ReceiptMethod;
use App\Other\Auth;
use App\Services\CartService;
use App\Services\OrderService;
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

        $paymentMethods = PaymentMethod::all()->all();
        $receiptMethods = ReceiptMethod::all()->all();

        $price = $this->cartService->getTotalAmountByProfile(Auth::getProfile());

        return view(
            'checkout',
            compact('profile', 'price', 'paymentMethods', 'receiptMethods')
        );
    }

    public function order(
        PostOrderRequest $request
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
