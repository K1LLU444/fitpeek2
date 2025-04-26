<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Avatar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http; // ✅ Needed for calling Flask server

class AvatarController extends Controller
{
    public function create()
    {
        $avatar = Avatar::where('user_id', Auth::id())->first();
        return view('createavatar', compact('avatar'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sex' => 'required|string',
            'height' => 'required|numeric',
            'weight' => 'required|numeric',
            'chest' => 'required|numeric',
            'waist' => 'required|numeric',
            'hips' => 'required|numeric',
            'inseam' => 'required|numeric',
        ]);

        $python = 'C:\\Users\\jungc\\AppData\\Local\\Programs\\Python\\Python313\\python.exe';
        $generate_avatar_path = base_path('generate_avatar.py');

        $sex = strtolower($validated['sex']);
        $measurements = [
            $validated['height'],
            $validated['weight'],
            $validated['chest'],
            $validated['waist'],
            $validated['hips'],
            $validated['inseam'],
        ];

        // 🔵 Step 1: Predict betas via Flask server
        $response = Http::post('http://127.0.0.1:5000/predict', [
            'measurements' => $measurements,
            'sex' => $sex,
        ]);

        if (!$response->successful()) {
            return back()->with('error', '❌ Beta prediction server error.');
        }

        $betasJson = json_encode($response->json()['betas']);
        \Log::info('🧠 Received Betas: ' . $betasJson);

        // 🔵 Step 2: Encode betas safely (base64)
        $encodedBetas = base64_encode($betasJson);

        $timestamp = time();
        $filename = "public/avatars/avatar_" . Auth::id() . "_$timestamp.obj";
        $fullOutputPath = base_path($filename);

        // 🔵 Step 3: Generate avatar using generate_avatar.py
        $command = "\"$python\" \"$generate_avatar_path\" \"$encodedBetas\" \"$fullOutputPath\" \"$sex\" 2>&1";
        \Log::info('🔵 Generate Avatar Command: ' . $command);

        $result = shell_exec($command);
        \Log::info('🟢 Generate Output: ' . $result);

        // 🔵 Step 4: Check if avatar file was created
        if (!file_exists($fullOutputPath)) {
            return back()->with('error', '❌ Avatar generation failed: ' . $result);
        }

        // 🔵 Step 5: Save avatar and measurements into database
        Avatar::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'sex' => $validated['sex'],
                'height' => $validated['height'],
                'weight' => $validated['weight'],
                'chest' => $validated['chest'],
                'waist' => $validated['waist'],
                'hips' => $validated['hips'],
                'inseam' => $validated['inseam'],
                'avatar_path' => 'storage/avatars/avatar_' . Auth::id() . "_$timestamp.obj",
            ]
        );

        return redirect()->route('avatar.create')->with('success', '✅ Avatar created and saved successfully!');
    }
}
