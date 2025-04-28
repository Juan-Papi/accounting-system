<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(){
        $providers = Provider::orderBy('id', 'DESC')->paginate(6);
        return view('providers.index', compact('providers'));
    }

    
}
