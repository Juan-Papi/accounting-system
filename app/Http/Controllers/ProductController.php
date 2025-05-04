<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Provider;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(){
        $providers = Provider::all();
        $products = Product::all();
        return view('products.index', compact('products', 'providers'));
    }

}
