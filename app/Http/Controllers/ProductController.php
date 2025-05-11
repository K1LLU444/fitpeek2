<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Avatar;
use Illuminate\Support\Facades\Auth;
use App\Models\RecommendedProduct;

class ProductController extends Controller
{
    public function show($id)
    {
        $product = RecommendedProduct::findOrFail($id);
    
        $avatar = Avatar::where('user_id', Auth::id())->first();
    
        return view('product', compact('product', 'avatar'));
    }
}
