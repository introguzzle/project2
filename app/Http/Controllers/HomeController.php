<?php

namespace App\Http\Controllers;

use App\Jobs\JobTest;
use App\Services\CategoryService;
use App\Services\ProductService;
use App\Services\RabbitMQService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

use Illuminate\Contracts\Foundation\Application as App;
use Illuminate\Support\Facades\Queue;


class HomeController extends Controller
{
    private ProductService $productService;
    private CategoryService $categoryService;

    /**
     * @param ProductService $productService
     * @param CategoryService $categoryService
     */
    public function __construct(
        ProductService $productService,
        CategoryService $categoryService
    )
    {
        $this->productService = $productService;
        $this->categoryService = $categoryService;
    }


    public function index(): View|Application|Factory|App
    {
        $products = $this->productService->acquireAll();
        $categories = $this->categoryService->acquireAllCategories();

        return view('home', compact('products', 'categories'));
    }
}
