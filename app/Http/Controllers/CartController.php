<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCartRequest;
use App\ModelView\ProductView;
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

    public function index(): View|Application|Factory|App
    {
        $productViews = $this->acquireAllByProfile();

        return view('cart', compact('productViews'));
    }

    /**
     * @param UpdateCartRequest $request
     * @return JsonResponse
     */

    public function update(UpdateCartRequest $request): JsonResponse
    {
        $profile = Auth::getProfile();

        if (!$profile) {
            return $this->forbiddenResponse();
        }

        $productId = $request->getProductId();

        if (!$productId) {
            return response()->json(['error' => 'Product ID not provided'], 400);
        }

        $gain = $request->getGain();

        try {
            $this->cartService->update(
                $profile,
                $productId,
                $gain
            );
        } catch (Throwable) {
            return $this->internalServerErrorResponse();
        }

        return response()->json()
            ->setData(['message' => 'Success']);
    }

    /**
     * @return JsonResponse
     */

    public function acquireTotalQuantity(): JsonResponse
    {
        if (!Auth::check()) {
            return $this->forbiddenResponse();
        }

        try {
            $totalQuantity = $this->cartService->acquireTotalQuantityByProfile(Auth::getProfile());
        } catch (Throwable) {
            return $this->internalServerErrorResponse();
        }

        return response()->json()
            ->setData($totalQuantity);
    }

    /**
     * @return ProductView[]
     */
    public function acquireAllByProfile(): array
    {
        $profile = Auth::getProfile();

        return $this->cartService->appendQuantityToProductViews(
            $profile,
            $this->cartService->acquireAllByProfile($profile)
        );
    }
}
