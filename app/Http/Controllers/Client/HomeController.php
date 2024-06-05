<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Core\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        /**
         * @var Product[] $products
         */
        $products = Product::all()->all();

        /**
         * @var Category[] $categories
         */

        $categories = Category::all()->all();

        return view('home', compact('products', 'categories'));
    }
}
