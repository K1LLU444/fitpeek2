<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\output;
use App\Models\Product;
use Intervention\Image\Facades\Image;

class ProductScrapeController extends Controller
{
    public function scrape(Request $request)
    {
        $url = $request->input('product_url');
        $python = 'C:\\Python310\\python.exe';
        $script = base_path('resources/scraper/scrape_nckatmtc.py');
        $escapedUrl = escapeshellarg($url);

        putenv("PYTHONHASHSEED=random");
        putenv("NUMBA_CACHE_DIR=C:\\xampp\\tmp");

        $command = "\"$python\" \"$script\" $escapedUrl 2>&1";
        file_put_contents(base_path('last_command.txt'), $command);
        $output = shell_exec($command);
        file_put_contents(base_path('last_output.txt'), $output);

        $outputLines = explode("\n", trim($output));
        if (str_starts_with($outputLines[0], 'INFO:')) {
            array_shift($outputLines);
        }
        $cleanOutput = implode("\n", $outputLines);
        $data = json_decode($cleanOutput, true);
        
        $product = Product::updateOrCreate(
            ['product_url' => $data['product_url']],
            [
                'product_name' => $data['product_name'] ?? null,
                'product_images' => $data['product_images'] ?? null,
                'size_chart_image' => $data['size_chart_image'] ?? null,
                'size_chart_data' => isset($data['size_chart_data']) ? json_encode($data['size_chart_data']) : null,
                'price' => $data['price'] ?? null,
                'description' => $data['description'] ?? null,
            ]
        );

        // ProductScrapeController.php

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

        return view('product', compact('product', 'otherProducts'));
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

        return redirect()->route('dashboard')->with('message', 'Images saved and cleaned!');
    }

    public function removeBackgroundFromUrl($url, $slug)
    {
        try {
            // Download the image
            $imageData = file_get_contents($url);
            $tempPath = storage_path("app/temp-{$slug}.png");
            file_put_contents($tempPath, $imageData);

            // Output location (inside public/products)
            $outputPath = public_path("products/{$slug}.png");

            // Ensure folder exists
            if (!file_exists(public_path('products'))) {
                mkdir(public_path('products'), 0755, true);
            }

            // Python + script
            $python = 'C:\\Python310\\python.exe';
            $script = base_path('resources/scraper/remove_bg.py');

            // ✅ ENV setup like the scraper logic
            putenv("PYTHONHASHSEED=random");
            putenv("NUMBA_CACHE_DIR=C:\\xampp\\tmp");

            // Build command
            $escapedInput = escapeshellarg($tempPath);
            $escapedOutput = escapeshellarg($outputPath);
            $command = "\"$python\" \"$script\" $escapedInput $escapedOutput 2>&1";

            // Run command
            $output = shell_exec($command);

            // Optional: check if output file exists
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
