<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Avatar; // Import the model

class AvatarController extends Controller
{
    public function store(Request $request)
    {
        // Validate incoming request
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

        // Store validated data in the database
        $avatar = Avatar::create($validated);

        // Redirect with success message
        return redirect()->route('avatar.success')->with('success', 'Avatar created successfully!');
    }
}
