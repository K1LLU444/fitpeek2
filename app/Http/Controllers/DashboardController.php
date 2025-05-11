<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RecommendedProduct;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\RecommendedProduct::query();
    
        if ($request->filled('sex')) {
            $query->where('sex', $request->sex);
        }
    
        if ($request->filled('weather')) {
            $query->whereJsonContains('tag_weather', $request->weather);
        }
    
        if ($request->filled('events')) {
            $query->whereJsonContains('tag_events', $request->events);
        }
    
        if ($request->filled('style')) {
            $query->whereJsonContains('tag_styles', $request->style);
        }

        if ($request->filled('type')) {
            $query->where('product_type', $request->type); // top or bottom
        }
        
    
        $products = $query->orderBy('id', 'asc')->get();
    
        // If AJAX, return partial view only
        if ($request->ajax()) {
            return view('partials.product_cards', compact('products'))->render();
        }
    
        return view('dashboard', compact('products'));
    }    
}
