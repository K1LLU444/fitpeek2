<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Avatar; // Import the model
use Illuminate\Support\Facades\Auth;

class AvatarController extends Controller
{
    public function create()
    {
        // Retrieve the logged-in user's avatar details
        $avatar = Avatar::where('user_id', Auth::id())->first();

        // Return view with saved avatar data (if available)
        return view('createavatar', compact('avatar'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sex' => 'required|string',
            'bust_size' => 'required|numeric',
            'shoulder_width' => 'required|numeric',
            'waist_size' => 'required|numeric',
            'hip_size' => 'required|numeric',
            'height' => 'required|numeric',
            'weight' => 'required|numeric',
            'arm_length' => 'required|numeric',
            'leg_length' => 'required|numeric',
            'thigh_circumference' => 'required|numeric',
        ]);
    
        // Store the avatar and associate it with the logged-in user
        Avatar::create(array_merge($validated, ['user_id' => auth()->id()]));
    
        return redirect()->route('avatar.success')->with('success', 'Avatar created successfully!');
    }    

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sex' => 'required|string',
            'bust_size' => 'required|numeric',
            'shoulder_width' => 'required|numeric',
            'waist_size' => 'required|numeric',
            'hip_size' => 'required|numeric',
            'height' => 'required|numeric',
            'weight' => 'required|numeric',
            'arm_length' => 'required|numeric',
            'leg_length' => 'required|numeric',
            'thigh_circumference' => 'required|numeric',
        ]);
    
        // Find the avatar belonging to the logged-in user
        $avatar = Avatar::where('user_id', auth()->id())->first();
    
        if ($avatar) {
            $avatar->update($validated);
            return redirect()->route('avatar.success')->with('success', 'Avatar created successfully!');
        }
    
        return redirect()->route('avatar.create')->with('error', 'No avatar found to update.');
    }
    
    public function tryon()
    {
        $avatar = Avatar::where('user_id', Auth::id())->first();

        if (!$avatar) {
            return redirect()->route('avatar.create')->with('error', 'No avatar found.');
        }

        // Choose base model based on sex
        $avatarUrl = $avatar->sex === 'Male'
            ? asset('avatars/base_model_male.glb')
            : asset('avatars/base_model_female.glb');

        // Prepare morph data
        $bodyData = [
            'bust_size' => $avatar->bust_size,
            'shoulder_width' => $avatar->shoulder_width,
            'waist_size' => $avatar->waist_size,
            'hip_size' => $avatar->hip_size,
            'height' => $avatar->height,
            'weight' => $avatar->weight,
            'arm_length' => $avatar->arm_length,
            'leg_length' => $avatar->leg_length,
            'thigh_circumference' => $avatar->thigh_circumference,
        ];

        return view('tryon', [
            'avatarUrl' => asset('avatars/base_model_female.glb'),
            'topImageUrl' => asset('images/top.png'),
            'bottomImageUrl' => asset('images/bottom.png'),
            'topSizes' => [
                ["size" => "XS", "width" => 20.0, "length" => 24.5, "sleeve_length" => 8.0],
                ["size" => "S", "width" => 21.0, "length" => 25.5, "sleeve_length" => 8.5],
                ["size" => "M", "width" => 22.0, "length" => 26.5, "sleeve_length" => 9.0],
                ["size" => "L", "width" => 23.0, "length" => 27.5, "sleeve_length" => 9.5],
                ["size" => "XL", "width" => 24.0, "length" => 28.5, "sleeve_length" => 10.0],
                ["size" => "2XL", "width" => 25.0, "length" => 29.5, "sleeve_length" => 10.5],
                ["size" => "3XL", "width" => 26.0, "length" => 30.5, "sleeve_length" => 11.0],
            ],
            'bottomSizes' => [
                ["size" => "S", "waistline_min" => 27.0, "waistline_max" => 29.0, "length" => 36.0],
                ["size" => "M", "waistline_min" => 29.0, "waistline_max" => 31.0, "length" => 36.5],
                ["size" => "L", "waistline_min" => 31.0, "waistline_max" => 32.0, "length" => 37.0],
                ["size" => "XL", "waistline_min" => 32.0, "waistline_max" => 34.0, "length" => 39.0],
                ["size" => "2XL", "waistline_min" => 34.0, "waistline_max" => 36.0, "length" => 40.0],
            ],
        ]);
        
    }
}
