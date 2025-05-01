<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Avatar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
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

        $python = 'C:\\Users\\jungc\\AppData\\Local\\Programs\\Python\\Python313\\python.exe';
        $generateAvatarScript = base_path('generate_dual_avatars.py');
        $mirrorScript = base_path('mirror_half_to_full.py');

        $sex = strtolower($validated['sex']);
        $measurements = [
            $validated['height'],
            $validated['weight'],
            $validated['chest'],
            $validated['waist'],
            $validated['hips'],
            $validated['inseam'],
        ];

        $response = Http::post('http://127.0.0.1:5000/predict', [
            'measurements' => $measurements,
            'sex' => $sex,
        ]);

        if (!$response->successful()) {
            return back()->with('error', '❌ Beta prediction server error.');
        }

        $betasJson = json_encode($response->json()['betas']);
        \Log::info('🧠 Received Betas: ' . $betasJson);

        $encodedBetas = base64_encode($betasJson);
        $timestamp = time();
        $baseName = "avatar_" . Auth::id() . "_{$timestamp}";

        $tposeFilename = "avatars/{$baseName}_tpose.obj";
        $aposeFilename = "avatars/{$baseName}_apose.obj";

        $tposePath = storage_path("app/public/" . $tposeFilename);
        $aposePath = storage_path("app/public/" . $aposeFilename);

        $command = "\"$python\" \"$generateAvatarScript\" \"$encodedBetas\" \"$baseName\" \"$sex\" 2>&1";
        \Log::info('⚙ Running Avatar Command: ' . $command);
        $result = shell_exec($command);
        \Log::info('📤 Python Output: ' . $result);

        if (!file_exists($tposePath) || !file_exists($aposePath)) {
            return back()->with('error', '❌ One or both .obj files failed to generate.');
        }

        $avatar = Avatar::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'sex' => $validated['sex'],
                'height' => $validated['height'],
                'weight' => $validated['weight'],
                'chest' => $validated['chest'],
                'waist' => $validated['waist'],
                'hips' => $validated['hips'],
                'inseam' => $validated['inseam'],
                'avatar_path' => 'storage/' . $tposeFilename,
                'avatar_apose_path' => 'storage/' . $aposeFilename,
            ]
        );

        return redirect()->route('avatar.create')->with('success', '✅ Avatar created successfully!');
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

        if (!file_exists(public_path('storage/avatars'))) {
            mkdir(public_path('storage/avatars'), 0755, true);
        }

        file_put_contents($fullPath, $decoded);

        if ($view === 'front') {
            $avatar->avatar_front_path = 'storage/' . $path;

            $python = 'C:\\Users\\jungc\\AppData\\Local\\Programs\\Python\\Python313\\python.exe';
            $mirrorScript = base_path('mirror_half_to_full.py');

            $frontPath = str_replace('/', DIRECTORY_SEPARATOR, public_path($avatar->avatar_front_path));
            $cleanedFilename = 'front_cleaned_user' . $avatar->user_id . '.png';
            $outputPath = str_replace('/', DIRECTORY_SEPARATOR, public_path('storage/avatars/' . $cleanedFilename));

            $mirrorCommand = "\"$python\" \"$mirrorScript\" \"$frontPath\" \"$outputPath\"";
            \Log::info("🪞 Running mirror command: $mirrorCommand");
            shell_exec($mirrorCommand);

            if (file_exists($outputPath)) {
                $avatar->avatar_cleaned_front_path = 'storage/avatars/' . $cleanedFilename;
            }
        } elseif ($view === 'back') {
            $avatar->avatar_back_path = 'storage/' . $path;

            $python = 'C:\\Users\\jungc\\AppData\\Local\\Programs\\Python\\Python313\\python.exe';
            $mirrorScript = base_path('mirror_back_half.py');

            $backPath = str_replace('/', DIRECTORY_SEPARATOR, public_path($avatar->avatar_back_path));
            $cleanedFilename = 'back_cleaned_user' . $avatar->user_id . '.png';
            $outputPath = str_replace('/', DIRECTORY_SEPARATOR, public_path('storage/avatars/' . $cleanedFilename));

            $mirrorCommand = "\"$python\" \"$mirrorScript\" \"$backPath\" \"$outputPath\"";
            \Log::info("🪞 Running mirror command for BACK: $mirrorCommand");
            shell_exec($mirrorCommand);

            if (file_exists($outputPath)) {
                $avatar->avatar_cleaned_back_path = 'storage/avatars/' . $cleanedFilename;
            }
        }

        $avatar->save();

        return response()->json(['success' => true, 'path' => 'storage/' . $path]);
    }
}
