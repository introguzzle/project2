<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCartRequest;
use App\Services\CartService;
use App\Utils\Auth;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Foundation\Application as App;
use Throwable;

class CartController extends Controller
{
    private CartService $cartService;

    /**
     * @param CartService $cartService
     */
    public function __construct(
        CartService $cartService,
    )
    {
        $this->cartService = $cartService;
    }

    /**
     * @return View|Application|Factory|App
     */

    public function showCart(): View|Application|Factory|App
    {
        $profile = Auth::getProfile();

        if ($profile === null) {
            return redirect('login');
        }

        $products = $this->cartService->acquireAllByProfile($profile);
        $price = $this->cartService->computePriceByProfile($profile);

        return view('cart', compact('products', 'price'));
    }

    /**
     * @param UpdateCartRequest $request
     * @return JsonResponse
     */

    public function updateQuantity(UpdateCartRequest $request): JsonResponse
    {
        $profile = Auth::getProfile();

        if ($profile === null) {
            return $this->forbiddenResponse();
        }

        $productId = $request->getProductId();

        if (!$productId) {
            return response()->json(['error' => 'Product ID not provided'], 400);
        }

        $quantityChange = $request->getQuantityChange();

        try {
            $this->cartService->update(
                $profile,
                $productId,
                $quantityChange
            );
        } catch (Throwable) {
            return $this->internalServerErrorResponse();
        }

        return response()->json()->setData(['message' => 'Success']);
    }

    /**
     * @return JsonResponse
     */

    public function acquireTotalQuantity(): JsonResponse
    {
        try {
            $totalQuantity = $this->cartService->computeTotalQuantityByProfile(Auth::getProfile());
        } catch (Throwable) {
            return response()->json()->setData(0);
        }

        return response()->json()->setData($totalQuantity);
    }

    public function acquireTotalPrice(): JsonResponse
    {
        try {
            $priceByProfile = $this->cartService->computePriceByProfile(Auth::getProfile());
            return response()->json()->setData($priceByProfile);

        } catch (Throwable) {
            $this->abortNotFound();
        }
    }
}
