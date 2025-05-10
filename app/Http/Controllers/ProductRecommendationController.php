<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RecommendedProduct;

class ProductRecommendationController extends Controller
{
    public function fetch(Request $request)
    {
        $weather = ucfirst(strtolower($request->input('weather')));
        $event = ucwords(strtolower($request->input('event')));
        $style = ucwords(strtolower($request->input('style')));

        \Log::info('🎯 Filters received', compact('weather', 'event', 'style'));

        $query = RecommendedProduct::query();

        // Always filter by weather (required)
        if ($weather) {
            $query->whereJsonContains('tag_weather', $weather);
        }

        // Conditionally filter event and style
        if ($event && $event !== '--select--') {
            $query->whereJsonContains('tag_events', $event);
        }

        if ($style && $style !== '--select--') {
            $query->whereJsonContains('tag_styles', $style);
        }

        $products = $query->get()->groupBy('product_type');

        return response()->json([
            'top' => $products->get('top', collect())->values()->map(function ($product) {
                return $product->only([
                    'product_name',
                    'product_link',
                    'price',
                    'description',
                    'size_chart',
                    'product_type',
                    'pose', // ✅ Add this line
                    'front_image',
                    'back_image'
                ]);
            }),
            'bottom' => $products->get('bottom', collect())->values()->map(function ($product) {
                return $product->only([
                    'product_name',
                    'product_link',
                    'price',
                    'description',
                    'size_chart',
                    'product_type',
                    'pose', // ✅ Add this line
                    'front_image',
                    'back_image'
                ]);
            }),
        ]);
    }
}
