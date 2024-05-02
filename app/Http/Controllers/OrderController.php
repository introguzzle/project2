<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Contracts\Foundation\Application as App;

class OrderController extends Controller
{
    public function index(): View|Application|Factory|App
    {
        return view('checkout');
    }
}
