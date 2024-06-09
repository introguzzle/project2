<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Core\Controller;
use App\Http\Requests\Client\UpdateCartRequest;
use App\Models\User\Profile;
use App\Other\Authentication;
use App\Other\AuthManager;
use App\Services\Auth\IdentityService;
use App\Services\CartService;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
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
        $profile = Authentication::profile();

        if ($profile === null) {
            return redirect()->route('login');
        }

        if ($profile->cart === null) {
            $this->cartService->createCart($profile);
            $products = new Collection();
            $price = 0;

        } else {
            $products = $profile->cart->products;
            $price = $profile->cart->getTotalAmount();
        }

        $data = compact('products', 'price');

        return viewClient('cart')->with($data);
    }

    /**
     * @param UpdateCartRequest $request
     * @return JsonResponse
     */

    public function updateQuantity(UpdateCartRequest $request): JsonResponse
    {
        $profile = $request->user()?->profile ?? $this->createGuestProfile();

        if (!$request->productId) {
            return response()->json(['error' => 'Product ID not provided'], 400);
        }

        try {
            $this->cartService->update(
                $profile,
                $request->productId,
                $request->quantityChange,
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

    public function getTotalQuantity(): JsonResponse
    {
        try {
            $totalQuantity = $this
                ->cartService
                ->getTotalQuantityByProfile(Authentication::profile());
        } catch (Throwable) {
            return response()->json()->setData(0);
        }

        return response()->json()->setData($totalQuantity);
    }

    public function getTotalPrice(): JsonResponse
    {
        try {
            $priceByProfile = $this
                ->cartService
                ->getTotalAmountByProfile(Authentication::profile());
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
        $profile = $identity->profile;

        Authentication::login($identity, true);
        $this->cartService->createCart($profile);

        return $profile;
    }
}
