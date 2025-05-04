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
        $sizeChart = json_decode($product->size_chart_data, true);
        $selectedSize = $request->selected_size;

        $sizeData = collect($sizeChart)->firstWhere('size', $selectedSize);
        if (!$sizeData || !isset($sizeData['width']) || !isset($sizeData['length'])) {
            return back()->withErrors(['Size data incomplete.']);
        }

        $width = $sizeData['width'];
        $length = $sizeData['length'];

        Log::info('📏 Size dimensions', [
            'selected_size' => $selectedSize,
            'width' => $width,
            'length' => $length
        ]);

        $avatar = Avatar::where('user_id', Auth::id())->first();
        if (!$avatar) {
            return back()->withErrors(['No avatar found.']);
        }

        $python = 'C:\Python310\python.exe';
        $scriptFront = base_path('resources/python/overlay_clothes.py');
        $scriptBack = base_path('resources/python/overlay_clothes_back.py');

        $avatarFront = storage_path("app/public/avatars/front_cleaned_user{$avatar->user_id}.png");
        $avatarBack = storage_path("app/public/avatars/back_cleaned_user{$avatar->user_id}.png");

        $shirtFront = public_path($request->front_image);
        $shirtBack = public_path($request->back_image);

        // ✅ Output files (define before use!)
        $frontOutputPath = storage_path('app/public/results/tryon_result_front.png');
        $backOutputPath = storage_path('app/public/results/tryon_result_back.png');

        // ▶️ Run Front Overlay
        $frontProcess = new Process([
            $python, $scriptFront,
            $request->avatar_height,
            $avatarFront,
            $shirtFront,
            $width,
            $length,
            $frontOutputPath
        ]);
        $frontProcess->run();
        sleep(1); // Optional safety delay

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
                $python, $scriptBack,
                $request->avatar_height,
                $avatarBack,
                $shirtBack,
                $width,
                $length,
                $backOutputPath
            ]);
            $backProcess->run();
            sleep(1);

            if (!$backProcess->isSuccessful()) {
                Log::error("Back overlay failed", [
                    'command' => $backProcess->getCommandLine(),
                    'error' => $backProcess->getErrorOutput()
                ]);
            } else {
                Log::info("✅ Back overlay done", ['output' => $backProcess->getOutput()]);
            }
        }

        // ✅ Confirm if images were created
        if (!File::exists($frontOutputPath)) {
            Log::error("❌ Front tryon_result_front.png NOT FOUND");
        }
        if (!File::exists($backOutputPath)) {
            Log::error("❌ Back tryon_result_back.png NOT FOUND");
        }

        return redirect()->route('product.show', ['product' => $product->id]);
    }

}