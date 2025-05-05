<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request){
        $query = Product::query();

        if ($request->has('name')) {
            $query->filterByName($request->name);
        }

        if ($request->has('category_name')) {
            $query->filterByCategoryName($request->category_name);
        }

        if ($request->has('provider_name')) {
            $query->filterByProviderName($request->provider_name);
        }

        if ($request->has('min_price') && $request->has('max_price')) {
            $query->filterByPriceRange($request->min_price, $request->max_price);
        }

        if ($request->has('min_stock')) {
            $query->filterByStock($request->min_stock);
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->filterByCreatedDate($request->start_date, $request->end_date);
        }

        if ($request->has('sort_price')) {
            $query->orderByPrice($request->sort_price);
        }

        return response()->json($query->paginate(10));
    }


    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }
        return response()->json($product);
    }

    public function store(Request $request)
    {
        $product = Product::create($request->all());
        return response()->json($product, 201);
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }
        $product->update($request->all());
        return response()->json($product);
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }
        $product->delete();
        return response()->json(['message' => 'Producto eliminado'], 200);
    }

    
}
