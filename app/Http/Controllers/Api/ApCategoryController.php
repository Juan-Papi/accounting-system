<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class ApCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return response()->json(['data' => $categories]);
    }
}
