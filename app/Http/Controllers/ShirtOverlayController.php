<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use App\Models\Product;
use App\Models\Avatar;

class ShirtOverlayController extends Controller
{
    public function overlayBySize(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'selected_size' => 'required',
            'avatar_height' => 'required|numeric',
            'front_image' => 'required|string',
            'back_image' => 'nullable|string',
        ]);

        $product = Product::findOrFail($request->product_id);
        $productType = $product->product_type ?? 'top';
        $sizeChart = json_decode($product->size_chart_data, true);
        $selectedSize = $request->selected_size;

        $sizeData = collect($sizeChart)->firstWhere('size', $selectedSize);

        // Use dynamic fields based on product_type
        if ($productType === 'top') {
            if (!isset($sizeData['width']) || !isset($sizeData['length'])) {
                return back()->withErrors(['Size data for top is incomplete.']);
            }
            $dimension1 = $sizeData['width'];
            $dimension2 = $sizeData['length'];
        } else {
            if (!isset($sizeData['width']) || !isset($sizeData['length'])) {
                return back()->withErrors(['Size data for bottom is incomplete.']);
            }
            $dimension1 = $sizeData['width']; // Treat as waist
            $dimension2 = $sizeData['length']; // Treat as outseam length
        }

        Log::info('📏 Size dimensions', [
            'selected_size' => $selectedSize,
            'dimension1' => $dimension1,
            'dimension2' => $dimension2,
            'product_type' => $productType
        ]);

        $avatar = Avatar::where('user_id', Auth::id())->first();
        if (!$avatar) {
            return back()->withErrors(['No avatar found.']);
        }

        $python = 'C:\\Python310\\python.exe';
        $scriptPath = base_path('resources/python/overlay_product.py'); // ✅ use one path
        // $scriptFront = base_path('resources/python/overlay_product.py');
        // $scriptBack = base_path('resources/python/overlay_product.py');

        $avatarFront = storage_path("app/public/avatars/front_cleaned_user{$avatar->user_id}.png");
        $avatarBack = storage_path("app/public/avatars/back_cleaned_user{$avatar->user_id}.png");

        $shirtFront = public_path($request->front_image); // ✅ must be set
        $shirtBack = $request->back_image ? public_path($request->back_image) : null; // ✅ optional    

        $productFront = public_path($request->front_image);
        $productBack = public_path($request->back_image);

        $frontOutputPath = storage_path('app/public/results/tryon_result_front.png');
        $backOutputPath = storage_path('app/public/results/tryon_result_back.png');

        Log::info('🧢 FRONT CMD Arguments', [
            'height' => $avatar->height,
            'avatar' => $avatarFront,
            'shirt' => $shirtFront,
            'dimension1' => $dimension1,
            'dimension2' => $dimension2,
            'output' => $frontOutputPath,
            'product_type' => $product->product_type
        ]);
        

        // ▶️ Run Front Overlay
        $frontProcess = new Process([
            $python, $scriptPath,
            $avatar->height,
            $avatarFront,
            $shirtFront,
            $dimension1, // width or waist
            $dimension2, // length
            $frontOutputPath,
            $product->product_type, 
        ], base_path(), ['PYTHONHASHSEED' => '0']);
        $frontProcess->run();
        sleep(1);

        if (!$frontProcess->isSuccessful()) {
            Log::error("Front overlay failed", [
                'command' => $frontProcess->getCommandLine(),
                'error' => $frontProcess->getErrorOutput()
            ]);
        } else {
            Log::info("✅ Front overlay done", ['output' => $frontProcess->getOutput()]);
        }

        // ▶️ Run Back Overlay
        if ($request->back_image) {
            $backProcess = new Process([
                $python, $scriptPath,
                $avatar->height,
                $avatarBack,
                $shirtBack,
                $dimension1,
                $dimension2,
                $backOutputPath,
                $product->product_type,
            ], base_path(), ['PYTHONHASHSEED' => '0']);
            $backProcess->run();
            sleep(1);

            if (!$backProcess->isSuccessful()) {
                Log::error("Back overlay failed", [
                    'command' => $backProcess->getCommandLine(),
                    'error' => $backProcess->getErrorOutput()
                ]);
            } else {
                Log::info("Back overlay done", ['output' => $backProcess->getOutput()]);
            }
        }

        if (!File::exists($frontOutputPath)) {
            Log::error("Front tryon_result_front.png NOT FOUND");
        }
        if (!File::exists($backOutputPath)) {
            Log::error("Back tryon_result_back.png NOT FOUND");
        }

        return redirect()->route('product.show', ['product' => $product->id]);
    }
}
