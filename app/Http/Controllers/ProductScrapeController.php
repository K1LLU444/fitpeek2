<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\output;
use App\Models\Product;

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


        if (!$output || !str_starts_with(trim($output), '{')) {
            return redirect()->route('dashboard')->withErrors(['error' => 'Scraping failed or returned no output.']);
        }
        
        $data = json_decode($output, true);

        if (!$data || !isset($data['product_url'])) {
            return redirect()->route('dashboard')->withErrors(['error' => 'Invalid scrape result']);
        }

        $product = Product::updateOrCreate(
            ['product_url' => $data['product_url']],
            [
                'product_name' => $data['product_name'] ?? null,
                'front_image' => $data['front_image'] ?? null,
                'size_chart_image' => $data['size_chart_image'] ?? null,
                'size_chart_data' => isset($data['size_chart_data']) ? json_encode($data['size_chart_data']) : null,
                'price' => $data['price'] ?? null,
                'description' => $data['description'] ?? null,
            ]
        );

        return redirect()->route('product.show', $product->id);
    }

    public function show(Product $product)
    {
        $otherProducts = Product::where('id', '!=', $product->id)
            ->latest()
            ->take(10)
            ->get();

        return view('product', compact('product', 'otherProducts'));
    }
}
