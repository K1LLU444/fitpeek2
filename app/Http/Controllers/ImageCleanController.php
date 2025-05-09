<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;

class ImageCleanController extends Controller
{
    public function index()
    {
        return view('clean-image');
    }

    public function process(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:5120',
        ]);

        $filename = Str::random(10) . '.' . $request->image->getClientOriginalExtension();
        $path = $request->file('image')->storeAs('public/uploads', $filename);
        $inputPath = storage_path('app/' . $path);

        $outputFilename = 'cleaned_' . $filename;
        $outputPath = storage_path("app/public/processed/{$outputFilename}");

        // Run Python script
        $pythonScript = base_path('resources/python/remove_bg_crop.py');
        $process = new Process(['python3', $pythonScript, $inputPath, $outputPath]);
        $process->run();

        if (!$process->isSuccessful()) {
            return back()->with('error', 'Processing failed.');
        }

        return view('clean-image', [
            'cleanedImage' => asset("storage/processed/{$outputFilename}"),
        ]);
    }
}
