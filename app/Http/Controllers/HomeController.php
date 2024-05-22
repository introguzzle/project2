<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

use Illuminate\Contracts\Foundation\Application as App;


class HomeController extends Controller
{
    public function index(): View|Application|Factory|App
    {
        $products = Product::all()->all();
        $categories = Category::all()->all();

        return view('home', compact('products', 'categories'));
    }
}
