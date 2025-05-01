<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Avatar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http; // ✅ Needed for calling Flask server
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;

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

        $python = 'C:\Users\warre\AppData\Local\Programs\Python\Python313\python.exe';
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
        $filename = "avatars/avatar_" . Auth::id() . "_$timestamp.obj"; // No 'public/' prefix
        $fullOutputPath = storage_path("app/public/" . $filename);        

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
    public function tryon()
    {
        $avatar = Avatar::where('user_id', Auth::id())->first();

        if (!$avatar) {
            return redirect()->route('avatar.create')->with('error', 'No avatar found.');
        }

        return view('tryon', compact('avatar'));
    }


    public function upload(Request $request)
    {
        $user = Auth::user();
        $avatar = Avatar::where('user_id', $user->id)->first();
    
        if (!$avatar) {
            return response()->json(['error' => 'Avatar not found'], 404);
        }
    
        $data = $request->input('image');
        $view = $request->input('view');
    
        if (!$data || !$view) {
            return response()->json(['error' => 'Invalid data'], 400);
        }
    
        $data = explode(',', $data);
        $decoded = base64_decode($data[1]);
        $filename = $view . '_' . Str::random(10) . '.png';
    
        $path = 'avatars/' . $filename;
        $fullPath = public_path('storage/' . $path);
        
        // Ensure the folder exists
        if (!file_exists(public_path('storage/avatars'))) {
            mkdir(public_path('storage/avatars'), 0755, true);
        }
    
        file_put_contents($fullPath, $decoded);
    
        // 🔥 Update the database
        if ($view === 'front') {
            $avatar->avatar_front_path = 'storage/' . $path;
        } elseif ($view === 'back') {
            $avatar->avatar_back_path = 'storage/' . $path;
        }
    
        $avatar->save();
    
        return response()->json(['success' => true, 'path' => 'storage/' . $path]);
    }
}
