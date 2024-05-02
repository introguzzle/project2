<?php

namespace App\Http\Controllers;

use App\ModelView\ProductView;
use App\Services\CartService;
use App\Services\CategoryService;
use App\Services\ProductService;
use App\Utils\Auth;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\Foundation\Application as App;

class ProductController extends Controller
{
    private ProductService $productService;
    private CategoryService $categoryService;
    private CartService $cartService;

    /**
     * @param ProductService $productService
     * @param CategoryService $categoryService
     * @param CartService $cartService
     */
    public function __construct(
        ProductService $productService,
        CategoryService $categoryService,
        CartService $cartService
    )
    {
        $this->productService = $productService;
        $this->categoryService = $categoryService;
        $this->cartService = $cartService;
    }


    public function index(
        Request $request,
        int|string $id
    ): View|Application|Factory|App|RedirectResponse
    {
        if (!Auth::check()) {
            return redirect('404');
        }

        $productView = $this->productService->acquireProductViewById($id);

        if ($productView === null) {
            return redirect('404');
        }

        $productView = $this->cartService->appendQuantityToProductView(
            Auth::getProfile(),
            $productView
        );

        return view('product', compact('productView'));
    }

    /**
     * @param int|string $categoryId
     * @return JsonResponse
     */

    public function acquireProductViewsByCategory(int|string $categoryId): JsonResponse
    {
        return response()->json()->setData(
            $this->acquireAndAppendProductViewsByCategory($categoryId)
        );
    }

    /**
     * @param int|string $productId
     * @return JsonResponse
     */
    public function acquireProductView(int|string $productId): JsonResponse
    {
        return response()->json()->setData(
            $this->acquireAndAppendProductView($productId)
        );
    }

    /**
     * @param int|string $categoryId
     * @return JsonResponse
     */
    public function acquireCategoryName(int|string $categoryId): JsonResponse
    {
        return response()->json()->setData(
            $this->categoryService->acquireCategoryName($categoryId)
        );
    }

    /**
     * @param int|string $productId
     * @return ProductView
     */
    public function acquireAndAppendProductView(int|string $productId): ProductView
    {
        return $this->cartService->appendQuantityToProductView(
            Auth::getProfile(),
            $this->productService->acquireProductViewById($productId)
        );
    }

    /**
     * @param int|string $categoryId
     * @return ProductView[]
     */
    public function acquireAndAppendProductViewsByCategory(int|string $categoryId): array
    {
        return $this->cartService->appendQuantityToProductViews(
            Auth::getProfile(),
            $this->productService->acquireProductViewsByCategory($categoryId)
        );
    }
}
