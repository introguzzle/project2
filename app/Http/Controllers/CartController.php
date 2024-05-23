<?php

namespace App\Http\Controllers;

use App\DTO\RegistrationDTO;
use App\Http\Requests\UpdateCartRequest;
use App\Models\Cart;
use App\Models\Identity;
use App\Models\Product;
use App\Models\Profile;
use App\Models\Role;
use App\Services\CartService;
use App\Services\IdentityService;
use App\Utils\Auth;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class CartController extends Controller
{
    private CartService $cartService;
    private IdentityService $identityService;

    /**
     * @param CartService $cartService
     * @param IdentityService $identityService
     */
    public function __construct(
        CartService $cartService,
        IdentityService $identityService
    )
    {
        $this->cartService = $cartService;
        $this->identityService = $identityService;
    }

    /**
     * @return View|Redirector|RedirectResponse
     */

    public function showCart(): View|Redirector|RedirectResponse
    {
        $profile = Auth::getProfile();

        if ($profile === null) {
            return redirect()->route('login');
        }

        $products = $profile->cart->products;
        $price = $this->cartService->getTotalAmount($profile);

        return view('cart', compact('products', 'price'));
    }

    /**
     * @param UpdateCartRequest $request
     * @return JsonResponse
     */

    public function updateQuantity(UpdateCartRequest $request): JsonResponse
    {
        $profile = Auth::getProfile() ?? $this->createGuestProfile();

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
        } catch (Throwable $t) {
            Log::error($t);
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
            $totalQuantity = $this->cartService->getTotalQuantityByProfile(Auth::getProfile());
        } catch (Throwable) {
            return response()->json()->setData(0);
        }

        return response()->json()->setData($totalQuantity);
    }

    public function acquireTotalPrice(): JsonResponse
    {
        try {
            $priceByProfile = $this->cartService->getTotalAmount(Auth::getProfile());
            return response()->json()->setData($priceByProfile);

        } catch (Throwable) {
            $this->abortNotFound();
        }
    }

    /**
     * @return Profile
     */
    public function createGuestProfile(): Profile
    {
        $identity = $this->identityService->registerGuest(Str::random());

        Auth::login($identity, true);

        $this->cartService->createCart($identity->getRelatedProfile());
        return $identity->getRelatedProfile();
    }
}
