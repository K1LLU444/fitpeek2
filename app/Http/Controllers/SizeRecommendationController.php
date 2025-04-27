<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Avatar;
use Illuminate\Support\Facades\Auth;

class SizeRecommendationController extends Controller
{
    private function cmToInches($cm)
    {
        return round($cm / 2.54, 2);
    }

    private function recommendShirtSize($bustCm, $shirtChart)
    {
        $bustInches = $this->cmToInches($bustCm);

        foreach ($shirtChart as $entry) {
            $shirtBust = $entry['width'] * 2;
            if ($shirtBust >= $bustInches) {
                return $entry['size'];
            }
        }

        return end($shirtChart)['size'];
    }

    private function recommendPantsSize($waistCm, $pantsChart)
    {
        $waistInches = $this->cmToInches($waistCm);

        foreach ($pantsChart as $entry) {
            if ($waistInches >= $entry['waistline_min'] && $waistInches <= $entry['waistline_max']) {
                return $entry['size'];
            }
        }

        return end($pantsChart)['size'];
    }

    public function recommend(Request $request)
    {
        $avatar = Avatar::where('user_id', Auth::id())->first();

        if (!$avatar) {
            return response()->json(['error' => 'No avatar found'], 404);
        }

        $shirtChart = json_decode($request->input('shirt_chart'), true);
        $pantsChart = json_decode($request->input('pants_chart'), true);

        $shirtSize = $this->recommendShirtSize($avatar->bust, $shirtChart);
        $pantsSize = $this->recommendPantsSize($avatar->waist, $pantsChart);

        return response()->json([
            'shirt' => $shirtSize,
            'pants' => $pantsSize
        ]);
    }

    public function showTryOn()
    {
        $avatar = Avatar::where('user_id', Auth::id())->first();

        if (!$avatar) {
            abort(404, 'No avatar found');
        }

        $shirtChart = [
            ["size" => "XS", "width" => 20.0, "length" => 24.5, "sleeve_length" => 8.0],
            ["size" => "S", "width" => 21.0, "length" => 25.5, "sleeve_length" => 8.5],
            ["size" => "M", "width" => 22.0, "length" => 26.5, "sleeve_length" => 9.0],
            ["size" => "L", "width" => 23.0, "length" => 27.5, "sleeve_length" => 9.5],
            ["size" => "XL", "width" => 24.0, "length" => 28.5, "sleeve_length" => 10.0],
            ["size" => "2XL", "width" => 25.0, "length" => 29.5, "sleeve_length" => 10.5],
            ["size" => "3XL", "width" => 26.0, "length" => 30.5, "sleeve_length" => 11.0],
        ];

        $pantsChart = [
            ["size" => "S", "waistline_min" => 27.0, "waistline_max" => 29.0, "length" => 36.0],
            ["size" => "M", "waistline_min" => 29.0, "waistline_max" => 31.0, "length" => 36.5],
            ["size" => "L", "waistline_min" => 31.0, "waistline_max" => 32.0, "length" => 37.0],
            ["size" => "XL", "waistline_min" => 32.0, "waistline_max" => 34.0, "length" => 39.0],
            ["size" => "2XL", "waistline_min" => 34.0, "waistline_max" => 36.0, "length" => 40.0],
        ];

        $recommendedShirt = $this->recommendShirtSize($avatar->bust, $shirtChart);
        $recommendedPants = $this->recommendPantsSize($avatar->waist, $pantsChart);

        // 🛠 Automatic detection from product name
        $productName = 'Sherman Tee White'; // Example static product name for now
        // ⚡ Later you will get this from database or scraping result

        if (stripos($productName, 'pants') !== false) {
            $productType = 'pants';
        } else {
            $productType = 'shirt';
        }

        return view('tryon', compact('recommendedShirt', 'recommendedPants', 'avatar', 'productType'));

    }
}
