<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Process;
use App\Models\Product;

class ProductScrapeController extends Controller
{
    

    public function scrape(Request $request)
        {
            $url = $request->input('product_url');
            $scriptPath = base_path('resources/scraper/scrape_nckatmtc.py');

            $process = Process::run("python \"$scriptPath\" \"$url\"");

            if ($process->failed()) {
                return redirect()->route('dashboard')->withErrors(['error' => 'Scraping failed']);
            }

            $data = json_decode($process->output(), true);

            $product = Product::updateOrCreate(
                ['product_url' => $data['product_url']],
                [
                    'product_name' => $data['product_name'] ?? null,
                    'front_image' => $data['front_image'] ?? null,
                    'size_chart_image' => $data['size_chart_image'] ?? null,
                    'price' => $data['price'] ?? null,
                    'description' => $data['description'] ?? null,
                ]
            );

            return redirect()->route('tryon'); // <- use DB fallback in tryon view
        }



}
