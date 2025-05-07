<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ChatSupportController extends Controller
{
    public function suggestOutfitForm(Request $request)
    {
        $validated = $request->validate([
            'event' => 'required|string',
            'weather' => 'required|string',
            'style' => 'required|string',
            'event_other' => 'nullable|string',
            'weather_other' => 'nullable|string',
            'style_other' => 'nullable|string',
            'notes' => 'nullable|string',

        ]);
    
        $event = strtolower($validated['event']) === 'other'
            ? strtolower($validated['event_other'])
            : strtolower($validated['event']);

        $weather = strtolower($validated['weather']) === 'other'
            ? strtolower($validated['weather_other'])
            : strtolower($validated['weather']);

        $style = strtolower($validated['style']) === 'other'
            ? strtolower($validated['style_other'])
            : strtolower($validated['style']);
    
        $notes = $validated['notes'] ?? '';
    
        // 🧠 Build message to send to GPT
        $userMessage = "I'm attending a $event event. The weather is $weather, and my preferred style is $style.";
        if ($notes) {
            $userMessage .= " Additional notes: $notes.";
        }
    
        // 🧠 System prompt with NO product/image logic
        $systemPrompt = "You are a smart virtual fashion assistant for a website called FitPeek.
    Your job is to suggest complete outfits for both men and women, based on:
    - Event
    - Weather
    - Style
    
    Rules:
    - Summarize the user's input first before the suggestions
    - Suggest 1–2 full outfits (top, bottom, shoes, and optional accessories) for men and women.
    - Use friendly, short, and practical tone.
    - Do NOT include image links or image references.
    - Keep results readable and nicely formatted using markdown (e.g., headings, bold).
    - You may reference real products.
    
    Example:
    User: 
    I'm attending a wedding event. The weather is hot, and my preferred style is formal.
    Assistant: 
    You are attending a wedding event in a hot weather and you'd like to wear formal attire. Here's what i recommend:
    For Men:
    - I recommend a wool coat over a turtleneck and slack
    For Women:
    - a long-sleeve midi dress with boots for women.";
    
        // 🔑 GPT API Call
        $apiKey = env('OPENAI_API_KEY');
        $model = env('OPENAI_MODEL', 'gpt-4o');
    
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => $model,
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $userMessage],
            ],
        ]);
    
        $json = $response->json();
    
        if (!isset($json['choices'][0]['message']['content'])) {
            \Log::error('GPT API Error', ['response' => $json]);
            return back()->with('suggestion', [
                'message' => '⚠️ Something went wrong generating your outfit. Please try again.',
                'image' => null,
                'product_url' => null,
            ]);
        }
    
        $reply = $json['choices'][0]['message']['content'];
        $responseHash = md5($reply);
    
        // Rating data
        $ratingCounts = DB::table('outfit_recommendation_ratings')
            ->selectRaw('rating, COUNT(*) as count')
            ->where('gpt_response_hash', $responseHash)
            ->groupBy('rating')
            ->orderByDesc('rating')
            ->pluck('count', 'rating')
            ->all();
    
        $totalRatings = array_sum($ratingCounts);
        $averageRating = round(DB::table('outfit_recommendation_ratings')
            ->where('gpt_response_hash', $responseHash)
            ->avg('rating'), 1);
    
        // Store result in session
        session()->put('suggestion', [
            'message' => $reply,
            'hash' => $responseHash,
            'ratings' => [
                'average' => $averageRating,
                'total' => $totalRatings,
                'counts' => $ratingCounts,
            ],
        ]);
        session()->forget('has_rated'); // allow rating UI to show again
        
    
        return back();
    }
    public function rateSuggestion(Request $request)
    {
        $validated = $request->validate([
            'hash' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);
    
        // ✅ Save this user's rating (still tagged with hash)
        DB::table('outfit_recommendation_ratings')->insert([
            'user_id' => auth()->id(),
            'gpt_response_hash' => $validated['hash'],
            'rating' => $validated['rating'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    
        // ✅ Load ALL ratings (no WHERE condition)
        $all = DB::table('outfit_recommendation_ratings')->pluck('rating');
        $average = round($all->avg(), 1);
        $total = $all->count();
    
        $counts = [];
        foreach (range(1, 5) as $star) {
            $counts[$star] = $all->filter(fn($r) => $r == $star)->count();
        }
    
        // ✅ Keep existing GPT suggestion in session
        $existing = session('suggestion');
        session()->put('suggestion', [
            'message' => $existing['message'] ?? '',
            'hash' => $validated['hash'],
            'ratings' => [
                'average' => $average,
                'total' => $total,
                'counts' => $counts,
            ],
        ]);
    
        session()->put('has_rated', true); // to hide the form
        return back();
    }
    
}