<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RecommendedProduct;
use App\Models\Avatar;
use App\Services\OpenAIService; // ✅ Make sure this is included

class ProductRecommendationController extends Controller
{
    public function fetch(Request $request)
    {
        $weather = ucfirst(strtolower($request->input('weather')));
        $event   = ucwords(strtolower($request->input('event')));
        $style   = ucwords(strtolower($request->input('style')));

        \Log::info('🎯 Filters received', compact('weather', 'event', 'style'));

        // 🔐 Fetch user and avatar
        $user = $request->user();
        $avatar = Avatar::where('user_id', $user->id)->first();

        if (!$avatar) {
            \Log::warning("🚫 No avatar found for user ID {$user->id}");
            return response()->json(['error' => 'No avatar found for this user.'], 404);
        }

        // ✅ Instantiate OpenAI manually
        $openAI = new OpenAIService();

        $sex = strtolower($avatar->sex ?? 'unisex');
        \Log::info("🧍 Avatar sex detected: {$sex}");

        // 🧠 Query building
        $query = RecommendedProduct::query()->whereIn('sex', [$sex, 'unisex']);

        if (!empty($weather)) {
            $query->whereJsonContains('tag_weather', $weather);
        }

        if (!empty($event) && $event !== '--select--') {
            $query->whereJsonContains('tag_events', $event);
        }

        if (!empty($style) && $style !== '--select--') {
            $query->whereJsonContains('tag_styles', $style);
        }

        $products = $query->orderBy('id', 'desc')->get();
        \Log::info('🛍 Products matched', ['count' => $products->count()]);

        $grouped = $products->groupBy('product_type');

        return response()->json([
            'top' => $grouped->get('top', collect())->values()->map(function ($product) {
                return $product->only([
                    'id',
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
            'bottom' => $grouped->get('bottom', collect())->values()->map(function ($product) {
                return $product->only([
                    'id',
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

    public function aiRecommend(Request $request)
    {
        $user = $request->user();
        $avatar = Avatar::where('user_id', $user->id)->first();
        $product = RecommendedProduct::find($request->input('product_id'));

        if (!$avatar || !$product) {
            return response()->json(['error' => 'Missing avatar or product.'], 404);
        }

        $openAI = new \App\Services\OpenAIService();
        $recommendation = $openAI->recommendSize($avatar, $product->product_name, $product->size_chart);

        \Log::info("🤖 AI recommendation for {$product->product_name}", ['result' => $recommendation]);

        return response()->json(['recommendation' => $recommendation]);
    }
}
