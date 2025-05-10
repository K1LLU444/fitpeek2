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
        session(['last_unit' => $avatar->unit ?? 'cm']);
        return view('createavatar', compact('avatar'));
    }

    public function getAvatarPath(Request $request)
    {
        $pose = $request->input('pose'); // 'high' or 'low'
        $avatar = Avatar::where('user_id', auth()->id())->firstOrFail();

        return response()->json([
            'front' => $pose === 'high' 
                ? asset($avatar->avatar_cleaned_front_path)
                : asset($avatar->avatar_cleaned_front_lower_path),
            'back' => $pose === 'high'
                ? asset($avatar->avatar_cleaned_back_path)
                : asset($avatar->avatar_cleaned_back_lower_path),
        ]);
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
            'unit' => 'required|string|in:cm,in',
        ]);

        $unit = $validated['unit'];
        session(['last_unit' => $unit]);

        function toCm($value, $unit) {
            return $unit === 'in' ? $value * 2.54 : $value;
        }

        $converted = [
            'height' => toCm($validated['height'], $unit),
            'weight' => $validated['weight'],
            'chest'  => toCm($validated['chest'], $unit),
            'waist'  => toCm($validated['waist'], $unit),
            'hips'   => toCm($validated['hips'], $unit),
            'inseam' => toCm($validated['inseam'], $unit),
        ];

        \Log::info('Unit: ' . $unit);
        \Log::info('Original input: ' . json_encode($validated));
        \Log::info('Converted to cm: ' . json_encode($converted));

        $python = '/var/www/fitpeek2/flaskenv/bin/python';
        $generateAvatarScript = base_path('generate_dual_avatars.py');

        $sex = strtolower($validated['sex']);
        $measurements = [
            $converted['height'],
            $converted['weight'],
            $converted['chest'],
            $converted['waist'],
            $converted['hips'],
            $converted['inseam'],
        ];

        $response = Http::post('http://127.0.0.1:5000/predict', [
            'measurements' => $measurements,
            'sex' => $sex,
        ]);

        if (!$response->successful()) {
            \Log::error('Beta prediction server error.');
            return back()->with('error', 'Beta prediction server error.');
        }

        $betasJson = json_encode($response->json()['betas']);
        \Log::info('Received Betas: ' . $betasJson);

        $encodedBetas = base64_encode($betasJson);
        $timestamp = time();
        $baseName = "avatar_" . Auth::id() . "_{$timestamp}";

        $tposeFilename = "avatars/{$baseName}_tpose.obj";
        $aposeFilename = "avatars/{$baseName}_apose.obj";
        $aposeLowerFilename = "avatars/{$baseName}_apose_lower.obj";

        $tposePath = storage_path("app/public/" . $tposeFilename);
        $aposePath = storage_path("app/public/" . $aposeFilename);
        $aposeLowerPathFull = storage_path("app/public/" . $aposeLowerFilename);

        $command = "\"$python\" \"$generateAvatarScript\" \"$encodedBetas\" \"$baseName\" \"$sex\" 2>&1";
        \Log::info('Running Avatar Command: ' . $command);
        $result = shell_exec($command);
        \Log::info('Python Output: ' . $result);

        if (!file_exists($tposePath) || !file_exists($aposePath) || !file_exists($aposeLowerPathFull)) {
            \Log::error('One or more .obj files failed to generate.');
            return back()->with('error', 'One or more .obj files failed to generate.');
        }

        $avatar = Avatar::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'sex' => $validated['sex'],
                'height' => $validated['height'],
                'weight' => $validated['weight'],
                'chest'  => $validated['chest'],
                'waist'  => $validated['waist'],
                'hips'   => $validated['hips'],
                'inseam' => $validated['inseam'],
                'unit' => $unit,
                'avatar_path' => 'storage/' . $tposeFilename,
                'avatar_apose_path' => 'storage/' . $aposeFilename,
                'avatar_apose_lower_path' => 'storage/' . $aposeLowerFilename,
            ]
        );

        session(['shouldCapture' => true]);

        return redirect()
        ->route('avatar.create')
        ->with('success', 'Avatar created successfully.')
        ->with('shouldCapture', true);
    
        
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
        $pose = $request->input('pose', 'apose');

        if (!$data || !$view) {
            return response()->json(['error' => 'Invalid data'], 400);
        }

        $data = explode(',', $data);
        $decoded = base64_decode($data[1]);
        $filename = $pose . '_' . $view . '_' . Str::random(10) . '.png';

        $path = 'avatars/' . $filename;
        $fullPath = public_path('storage/' . $path);

        if (!file_exists(public_path('storage/avatars'))) {
            mkdir(public_path('storage/avatars'), 0755, true);
        }

        file_put_contents($fullPath, $decoded);

        $python = '/var/www/fitpeek2/flaskenv/bin/python';

        $mirrorScript = match ("{$pose}_{$view}") {
            'apose_front'        => base_path('mirror_half_to_full.py'),
            'apose_back'         => base_path('mirror_back_half.py'),
            'apose_lower_front'  => base_path('mirror_half_lower.py'),
            'apose_lower_back'   => base_path('mirror_back_lower.py'),
            default              => null,
        };
        
        if (!$mirrorScript || !file_exists($mirrorScript)) {
            \Log::error("❌ Mirror script not found for pose/view: {$pose}_{$view}");
            return response()->json(['error' => 'Invalid pose or view'], 400);
        }
        
        $inputPath = str_replace('/', DIRECTORY_SEPARATOR, public_path('storage/' . $path));
        $cleanedFilename = $pose . '_cleaned_' . $view . '_user' . $avatar->user_id . '.png';
        $outputPath = str_replace('/', DIRECTORY_SEPARATOR, public_path('storage/avatars/' . $cleanedFilename));

        if (file_exists($outputPath)) {
            unlink($outputPath);
        }

        $mirrorCommand = "\"$python\" \"$mirrorScript\" \"$inputPath\" \"$outputPath\"";
        \Log::info("Running mirror command: $mirrorCommand");
        shell_exec($mirrorCommand);

        $publicCleanedPath = 'storage/avatars/' . $cleanedFilename;

        switch ("{$pose}_{$view}") {
            case 'apose_front':
                $avatar->avatar_front_path = 'storage/' . $path;
                $avatar->avatar_cleaned_front_path = $publicCleanedPath;
                break;
            case 'apose_back':
                $avatar->avatar_back_path = 'storage/' . $path;
                $avatar->avatar_cleaned_back_path = $publicCleanedPath;
                break;
            case 'apose_lower_front':
                $avatar->avatar_front_lower_path = 'storage/' . $path;
                $avatar->avatar_cleaned_front_lower_path = $publicCleanedPath;
                break;
            case 'apose_lower_back':
                $avatar->avatar_back_lower_path = 'storage/' . $path;
                $avatar->avatar_cleaned_back_lower_path = $publicCleanedPath;
                break;
        }

        $avatar->save();

        return response()->json(['success' => true, 'path' => 'storage/' . $path]);
    }
}
