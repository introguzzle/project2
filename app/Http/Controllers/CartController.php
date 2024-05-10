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
use Illuminate\Support\Facades\Log;
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
        $profile = Auth::getProfile();

        if ($profile === null) {
            return redirect('login');
        }

        try {
            $price = $this->cartService->acquirePriceByProfile($profile);
        } catch (Throwable) {
            $price = 0.0;
        }

        $productViews = $this->cartService->createAllProductViewsAndAppendQuantity($profile);
        return view('cart', compact('productViews', 'price'));
    }

    /**
     * @param UpdateCartRequest $request
     * @return JsonResponse
     */

    public function update(UpdateCartRequest $request): JsonResponse
    {
        $profile = Auth::getProfile();

        if ($profile === null) {
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
        } catch (Throwable $t) {
            return $this->internalServerErrorResponse();
        }

        return response()
            ->json()
            ->setData(['message' => 'Success']);
    }

    /**
     * @return JsonResponse
     */

    public function acquireTotalQuantity(): JsonResponse
    {
        $notPresent = function(): JsonResponse {
            return response()->json()->setData(0);
        };

        if (!Auth::check()) {
            return $notPresent();
        }

        try {
            $totalQuantity = $this->cartService->acquireTotalQuantityByProfile(Auth::getProfile());
        } catch (Throwable) {
            return $notPresent();
        }

        return response()
            ->json()
            ->setData($totalQuantity);
    }
}
