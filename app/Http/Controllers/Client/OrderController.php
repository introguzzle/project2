<?php

namespace App\Http\Controllers\Client;

use App\DTO\PostOrderDTO;
use App\Http\Controllers\Core\Controller;
use App\Http\Requests\Client\PaymentMethodsRequest;
use App\Http\Requests\Client\CreateOrderRequest;
use App\Models\PaymentMethod;
use App\Models\ReceiptMethod;
use App\Other\Authentication;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        $profile = Authentication::profile();
        $receiptMethods = ReceiptMethod::all()->all();
        $price = $this->cartService->getTotalAmountByProfile(Authentication::profile());

        $data = compact('profile', 'price', 'receiptMethods');

        return viewClient('checkout')->with($data);
    }

    public function order(
        CreateOrderRequest $request
    ): View|Redirector|RedirectResponse
    {
        try {
            $this->orderService->order(
                Authentication::profile(),
                PostOrderDTO::fromRequest($request)
            );

        } catch (Throwable $t) {
            Log::error($t);
            return redirect('checkout')
                ->with($this->internal());
        }

        return redirect('home')->with($this->notification('Заказ успешно создан'));
    }

    public function getPaymentMethods(PaymentMethodsRequest $request): JsonResponse
    {
        $paymentMethods = ReceiptMethod::find($request->receiptMethodId)
            ->paymentMethods
            ->map(static function (PaymentMethod $paymentMethod) {
                return [
                    'id'   => $paymentMethod->id,
                    'name' => $paymentMethod->name,
                ];
            });


        return response()->json()->setData($paymentMethods);
    }
}
