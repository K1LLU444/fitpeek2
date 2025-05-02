<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Avatar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Process;


class ShirtOverlayController extends Controller
{
    public function applyDefaultOverlay(Request $request)
    {
        $productId = $request->input('product_id');
        $userId = Auth::id();

        // Get the product and user avatar
        $product = Product::findOrFail($productId);
        $avatar = Avatar::where('user_id', $userId)->first();

        // ⛔ If no avatar exists, redirect user to avatar creation
        if (!$avatar) {
            return redirect()->route('createavatar')->with('error', 'Please create your avatar first.');
        }

        // Fallback default: use "M" as starting shirt size
        $selectedSize = 'M';

        // Convert size chart array to JSON
        $sizeChart = $product->size_chart ?? []; // Ensure it's not null
        $sizeChartJson = json_encode($sizeChart);

        // Get avatar height in cm
        $avatarHeight = $avatar->height ?? 170;

        // Call the Python overlay script
        $process = new Process([
            'python', 'resources/python/overlay_clothes.py'
        ], base_path(), [
            'SELECTED_SIZE' => $selectedSize,
            'AVATAR_HEIGHT' => $avatar->height,
            'AVATAR_CHEST' => $avatar->chest,
            'AVATAR_TORSO' => $avatar->torso_length,
            'SIZE_CHART_JSON' => json_encode($product->size_chart),
        ]);

        $process->run();

        if (!$process->isSuccessful()) {
            return back()->with('error', 'Overlay failed: ' . $process->getErrorOutput());
        }

        // ✅ Return to product view with success message and avatar
        return view('product', [
            'product' => $product,
            'avatar' => $avatar,
            'success' => 'Shirt overlay applied successfully!',
        ]);
    }

    public function overlayShirt()
    {
        $height = Avatar::where('user_id', Auth::id())->value('height') ?? 160.0;

        $command = "python resources/python/overlay_clothes.py {$height}";
        Process::run($command);
    
        return response()->json(['status' => 'Shirt overlay complete.']);
    }
    
    public function overlay(Product $product)
    {
        $avatar = Avatar::where('user_id', auth()->id())->first();
    
        if (!$avatar || !$avatar->height) {
            return back()->withErrors(['error' => 'Missing avatar height.']);
        }
    
        // Full paths from database
        $frontShirtPath = public_path($product->front_image); // products/front-3.png
        $backShirtPath = public_path($product->back_image);   // optional use
    
        // Validate image exists
        if (!file_exists($frontShirtPath)) {
            return back()->withErrors(['error' => 'Selected front shirt image not found.']);
        }
    
        $python = 'C:\\Python310\\python.exe';
        $script = base_path('resources/python/overlay_clothes.py');
    
        // Add height and image path arguments
        $command = "\"$python\" \"$script\" {$avatar->height} \"$frontShirtPath\"";
        shell_exec($command);
    
        return redirect()->route('product.show', $product->id);
    }
     
    
    public function applyDefault()
    {
        // Optional: fallback default shirt
        $defaultShirt = public_path('products/front-default.png');
        $target = public_path('products/front-shirt.png');
        if (file_exists($defaultShirt)) {
            copy($defaultShirt, $target);
        }
    
        $height = Avatar::where('user_id', Auth::id())->value('height') ?? 160;
        Process::run("python resources/python/overlay_clothes.py {$height}");
    
        return redirect()->back()->with('status', 'Default shirt applied.');
    }
    
}
