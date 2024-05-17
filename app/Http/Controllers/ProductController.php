<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\CategoryService;
use App\Services\ProductService;
use App\Utils\Auth;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\Foundation\Application as App;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProductController extends Controller
{
    private ProductService $productService;
    private CategoryService $categoryService;

    /**
     * @param ProductService $productService
     * @param CategoryService $categoryService
     */
    public function __construct(
        ProductService $productService,
        CategoryService $categoryService,
    )
    {
        $this->productService = $productService;
        $this->categoryService = $categoryService;
    }


    public function index(
        int|string $id
    ): Application|View|Factory|Redirector|App|RedirectResponse
    {
        $product = Product::find($id);

        if ($product === null) {
            return redirect('404');
        }

        return view('product', compact('product'));
    }

    /**
     * @param int|string $categoryId
     * @return JsonResponse
     */

    public function acquireProductCollection(int|string $categoryId): JsonResponse
    {
        $products = $this->productService->acquireAllByCategory($categoryId);
        return $this->try(fn() => new ProductCollection($products));
    }

    /**
     * @param int|string $productId
     * @return JsonResponse
     */
    public function acquireProductResource(int|string $productId): JsonResponse
    {
        $product = $this->productService->acquireById($productId);
        return $this->try(fn() => new ProductResource($product));
    }

    /**
     * @param int|string $categoryId
     * @return JsonResponse
     */
    public function acquireCategoryResource(int|string $categoryId): JsonResponse
    {
        $category = $this->categoryService->acquireById($categoryId);
        return $this->try(fn() => new CategoryResource($category));
    }

    public function try(callable $setData): JsonResponse
    {
        try {
            return response()->json()->setData($setData());
        } catch (Throwable $t) {
            Log::error($t);
            return response()->json()->setStatusCode(500);
        }
    }
}
