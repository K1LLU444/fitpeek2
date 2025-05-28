<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RecommendedProduct;
use App\Models\Avatar;
use Illuminate\Support\Facades\Log;

class SizeRecommendationController extends Controller
{
    public function recommend(Request $request, $productId)
    {
        $user = $request->user();
        $avatar = Avatar::where('user_id', $user->id)->first();

        if (!$avatar) {
            Log::warning("❌ No avatar found for user ID: {$user->id}");
            return response()->json(['error' => 'No avatar found.'], 404);
        }

        $product = RecommendedProduct::find($productId);

        if (!$product) {
            Log::warning("❌ Product not found with ID: {$productId}");
            return response()->json(['error' => 'Product not found.'], 404);
        }

        $type = $product->product_type;
        $chart = is_string($product->size_chart)
            ? json_decode($product->size_chart, true)
            : $product->size_chart;

        if (!$chart || !is_array($chart)) {
            Log::warning("⚠️ Invalid or missing size chart for product ID {$productId}");
            return response()->json(['error' => 'Invalid size chart.'], 422);
        }

        $target = match ($type) {
            'top' => $avatar->chest,
            'bottom' => $avatar->waist,
            default => null,
        };

        if (!$target) {
            Log::warning("⚠️ Missing avatar measurement for type '{$type}'");
            return response()->json(['error' => 'Missing avatar measurement.'], 422);
        }

        Log::info("🧍 Matching {$type} for measurement: {$target} cm");

        // Match the closest flat width * 2 to body circumference
        $bestSize = null;
        $minDiff = PHP_INT_MAX;

        foreach ($chart as $size => $data) {
            if (!isset($data['width'])) continue;

            $flat = $data['width'];
            $circumference = $flat * 2;
            $diff = abs($circumference - $target);

            Log::info("🔍 Testing size {$size}: flat={$flat}cm, round={$circumference}cm, diff={$diff}");

            if ($diff < $minDiff) {
                $minDiff = $diff;
                $bestSize = $size;
            }
        }

        Log::info("✅ Best match for product #{$productId} is size: {$bestSize}");

        return response()->json(['recommended_size' => $bestSize]);
    }
}
