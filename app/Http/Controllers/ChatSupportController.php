<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Product;

class ChatSupportController extends Controller
{
    public function suggestOutfitForm(Request $request)
    {
        $validated = $request->validate([
            'event' => 'required|string',
            'weather' => 'required|string',
            'style' => 'required|string',
            'event_other' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
    
        $event = strtolower($validated['event']) === 'other'
            ? strtolower($validated['event_other'])
            : strtolower($validated['event']);
    
        $weather = strtolower($validated['weather']);
        $style = strtolower($validated['style']);
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
    - Suggest 1–2 full outfits (top, bottom, shoes, and optional accessories) for men and women.
    - Use friendly, short, and practical tone.
    - Do NOT include image links or image references.
    - Keep results readable and nicely formatted using markdown (e.g., headings, bold).
    - You may reference real products.
    
    Example:
    User: I'm attending a cold wedding. I prefer a minimalist style.
    Assistant: I recommend a wool coat over a turtleneck and slacks for men, and a long-sleeve midi dress with boots for women.";
    
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
    
        return back()->with('suggestion', [
            'message' => $reply,
            'image' => null, // Completely unused
            'product_url' => null,
        ]);
    }
    
    


}
