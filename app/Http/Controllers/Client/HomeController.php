<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Core\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        $categoryQuery = $request->query('category-id');

        $products = Product::ordered('id');
        $categories = Category::ordered('id');

        $data = compact('products', 'categories', 'categoryQuery');
        return viewClient('home-main')->with($data);
    }
}
