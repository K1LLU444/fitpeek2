<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RecommendedProduct;
use App\Models\Avatar;

class ProductRecommendationController extends Controller
{
    public function fetch(Request $request)
    {
        // 📌 Normalize input filters
        $weather = ucfirst(strtolower($request->input('weather')));
        $event   = ucwords(strtolower($request->input('event')));
        $style   = ucwords(strtolower($request->input('style')));

        \Log::info('🎯 Filters received', compact('weather', 'event', 'style'));

        // 🔐 Get authenticated user and avatar
        $user = $request->user();
        $avatar = Avatar::where('user_id', $user->id)->first();

        if (!$avatar) {
            return response()->json(['error' => 'No avatar found for this user.'], 404);
        }

        $sex = strtolower($avatar->sex ?? 'unisex'); // default to unisex just in case
        \Log::info("🧍 Avatar sex detected: {$sex}");

        // 🧠 Start building the product query
        $query = RecommendedProduct::query();

        // ✅ Filter by sex ('male', 'female', or 'unisex')
        $query->whereIn('sex', [$sex, 'unisex']);

        // ✅ Filter by weather
        if (!empty($weather)) {
            $query->whereJsonContains('tag_weather', $weather);
        }

        // 🟡 Optional filters
        if (!empty($event) && $event !== '--select--') {
            $query->whereJsonContains('tag_events', $event);
        }

        if (!empty($style) && $style !== '--select--') {
            $query->whereJsonContains('tag_styles', $style);
        }

        // 📦 Retrieve and group by product_type
        $products = $query->get()->groupBy('product_type');

        \Log::info('🛍 Products returned:', ['sexes' => $query->get()->pluck('sex')->toArray()]);

        // 🔄 Format response
        return response()->json([
            'top' => $products->get('top', collect())->values()->map(function ($product) {
                return $product->only([
                    'product_name',
                    'product_link',
                    'price',
                    'description',
                    'size_chart',
                    'product_type',
                    'pose',
                    'front_image',
                    'back_image',
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
                    'pose',
                    'front_image',
                    'back_image',
                ]);
            }),
        ]);
    }
}