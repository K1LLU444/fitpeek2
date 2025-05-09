<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Avatar; // make sure you have an Avatar model

class TryOnController extends Controller
{
    public function show()
    {
        // Assuming you're using Auth and avatars are linked to the logged-in user
        $userId = Auth::id();

        // Fetch avatar where user_id matches (you can adjust this logic as needed)
        $avatar = Avatar::where('user_id', $userId)->first();

        // Pass avatar data to Blade view
        return view('weather', [
            'avatar' => $avatar
        ]);
    }
}
