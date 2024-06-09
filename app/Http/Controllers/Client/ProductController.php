<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Core\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use ArrayAccess;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Throwable;

class ProductController extends Controller
{
    public function index(
        int|string $id
    ): View|Redirector|RedirectResponse
    {
        $product = Product::find((int) $id);

        if ($product === null) {
            $this->abortNotFound();
        }

        $data = compact('product');
        return viewClient('product')->with($data);
    }

    /**
     * @param int|string $categoryId
     * @return JsonResponse
     */

    public function getProductResourceCollection(int|string $categoryId): JsonResponse
    {
        $products = Product::findAllByCategory((int) $categoryId);
        return $this->try(fn() => new ProductCollection($products));
    }

    /**
     * @param int|string $productId
     * @return JsonResponse
     */
    public function getProductResource(int|string $productId): JsonResponse
    {
        $product = Product::find((int) $productId);
        return $this->try(fn() => new ProductResource($product));
    }

    /**
     * @param int|string $categoryId
     * @return JsonResponse
     */
    public function getCategoryResource(int|string $categoryId): JsonResponse
    {
        $category = Category::find((int) $categoryId);
        return $this->try(fn() => new CategoryResource($category));
    }

    /**
     * @param Closure(): ArrayAccess  $setData
     * @return JsonResponse
     */

    public function try(Closure $setData): JsonResponse
    {
        try {
            return response()
                ->json()
                ->setData($setData());

        } catch (Throwable) {
            return $this->internalServerErrorResponse();
        }
    }
}
