<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\output;
use App\Models\Product;
use Intervention\Image\Facades\Image;
use App\Services\SizeRecommendationService; 


class ProductScrapeController extends Controller
{
public function scrape(Request $request)
{
    $url = $request->input('product_url');
    $python = '/var/www/fitpeek2/flaskenv310/bin/python';
    $script = base_path('resources/scraper/scrape_router.py');
    $escapedUrl = escapeshellarg($url);

    putenv("PYTHONHASHSEED=random");
    putenv("NUMBA_CACHE_DIR=/tmp");

    $command = "\"$python\" \"$script\" $escapedUrl 2>&1";
    file_put_contents(base_path('last_command.txt'), $command);
    $output = shell_exec($command);

    if ($output === null) {
        $output = '⚠️ shell_exec returned NULL. Possible permission or execution error.';
    }

    file_put_contents(base_path('last_output.txt'), $output);

    // ✅ Now only get the LAST LINE
    $outputLines = explode("\n", trim($output));
    $lastLine = end($outputLines); // 👈 Get the last non-empty line
    file_put_contents(base_path('debug_clean_output.txt'), $lastLine);

    // ✅ Decode only the last line (JSON)
    $data = json_decode($lastLine, true);
    file_put_contents(base_path('debug_decoded_data.json'), json_encode($data, JSON_PRETTY_PRINT));

    if (!$data || !isset($data['product_url'])) {
        return redirect()->route('dashboard')->withErrors(['error' => 'Scraping failed or returned invalid output.']);
    }

    // Save product
    $product = Product::firstOrNew(['product_url' => $data['product_url']]);
    $product->product_name = $data['product_name'] ?? null;
    $product->product_images = isset($data['product_images']) ? json_encode($data['product_images']) : null;
    $product->size_chart_image = $data['size_chart_image'] ?? null;
    $product->size_chart_data = isset($data['size_chart_data']) ? json_encode($data['size_chart_data']) : null;
    $product->price = $data['price'] ?? null;
    $product->description = $data['description'] ?? null;

    if (isset($data['product_name'])) {
        $lowerName = strtolower($data['product_name']);
        if (str_contains($lowerName, 'pants') || str_contains($lowerName, 'jeans') || str_contains($lowerName, 'bottom') || str_contains($lowerName, 'shorts')|| str_contains($lowerName, 'skirt')) {
            $product->product_type = 'bottom';
        } else if (str_contains($lowerName, 'dress')) {
            $product->product_type = 'top';
        } else {
            $product->product_type = 'top'; // fallback
        }
    } else {
        $product->product_type = 'top'; // fallback if name is missing
    }
    $product->save();

    return redirect()->route('dashboard')->with([
        'show_modal' => true,
        'product_images' => $data['product_images'],
        'product_id' => $product->id,
    ]);
}



    public function show(Product $product)
    {
        $otherProducts = Product::where('id', '!=', $product->id)
            ->latest()
            ->take(10)
            ->get();

        $recommender = new SizeRecommendationService();
        $recommendedSize = $recommender->recommendSize($product);

        return view('product', compact('product', 'otherProducts', 'recommendedSize'));
    }

    public function selectImages(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'front_image' => 'nullable|url',
            'back_image' => 'nullable|url',
        ]);

        $product = Product::find($request->product_id);

        // Remove background from both images
        $frontPath = $request->filled('front_image')
        ? $this->removeBackgroundFromUrl($request->front_image, 'front-' . $product->id)
        : null;

         $backPath = $request->filled('back_image')
        ? $this->removeBackgroundFromUrl($request->back_image, 'back-' . $product->id)
        : null;

        // Save to DB
        $product->front_image = $frontPath;
        $product->back_image = $backPath;
        $product->save();

        return redirect()->route('dashboard');
    }

public function removeBackgroundFromUrl($url, $slug)
{
    try {
        // ✅ Step 1: Download the image
        $imageData = file_get_contents($url);
        $tempPath = storage_path("app/product_images/temp-{$slug}.png");
        file_put_contents($tempPath, $imageData);

        // ✅ Step 2: Output destination (public/products/)
        $outputPath = public_path("products/{$slug}.png");

        // ✅ Step 3: Ensure folders exist
        if (!file_exists(storage_path('app/product_images'))) {
            mkdir(storage_path('app/product_images'), 0755, true);
        }
        if (!file_exists(public_path('products'))) {
            mkdir(public_path('products'), 0755, true);
        }

        // ✅ Step 4: Setup environment variables
        putenv("PYTHONHASHSEED=random");
        putenv("NUMBA_CACHE_DIR=/tmp");
        putenv("U2NET_HOME=" . storage_path('app/u2net')); // 🛡️ Correct model cache

        // ✅ Step 5: Prepare the command
        $python = '/var/www/fitpeek2/flaskenv310/bin/python';
        $script = base_path('resources/scraper/remove_bg.py');
        $escapedInput = escapeshellarg($tempPath);
        $escapedOutput = escapeshellarg($outputPath);
        $command = "\"$python\" \"$script\" $escapedInput $escapedOutput 2>&1";

        // ✅ Step 6: Execute the command
        $output = shell_exec($command);

        // ✅ Step 7: Check if result was created
        if (!file_exists($outputPath)) {
            throw new \Exception("rembg failed or did not create: {$outputPath}");
        }

        return 'products/' . basename($outputPath);
    } catch (\Exception $e) {
        logger()->error("❌ Background removal error: " . $e->getMessage());
        return null;
    }
}



}



