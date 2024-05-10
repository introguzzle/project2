<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\CategoryService;
use App\Services\ProductService;
use App\Utils\Auth;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

use Illuminate\Contracts\Foundation\Application as App;


class HomeController extends Controller
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

    public function index(): View|Application|Factory|App
    {
        $productViews = $this->productService->createAllProductViews();
        $productViews = $this->cartService->appendQuantityToProductViews(
            Auth::getProfile(),
            $productViews
        );

        $categories = $this->categoryService->acquireAllCategories();

        return view('home', compact('productViews', 'categories'));
    }
}
