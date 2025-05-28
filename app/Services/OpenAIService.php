<?php

namespace App\Services;

use OpenAI;
use Illuminate\Support\Facades\Log;

class OpenAIService
{
    public function recommendSize($avatar, $productName, $sizeChart)
    {
        $client = OpenAI::client(env('OPENAI_API_KEY'));

        $prompt = "User body measurements:\n"
            . "- Chest: {$avatar->chest} cm\n"
            . "- Waist: {$avatar->waist} cm\n"
            . "- Hips: {$avatar->hips} cm\n"
            . "- Height: {$avatar->height} cm\n\n"
            . "Here is the size chart for {$productName}:\n"
            . json_encode($sizeChart, JSON_PRETTY_PRINT) . "\n\n"
            . "Your task:\n"
            . "1. Based on the user’s measurements and the size chart, recommend the **best-fitting size**.\n"
            . "2. Also recommend a **second-best size** in case the best one is unavailable.\n"
            . "3. Provide a short reason why you selected each.\n\n"
            . "Return ONLY a valid JSON object in the following format (no extra text):\n"
            . "{\n"
            . "  \"best\": \"M\",\n"
            . "  \"second\": \"L\",\n"
            . "  \"reason\": \"M fits chest and waist best; L is slightly looser.\"\n"
            . "}";

        $model = env('OPENAI_MODEL', 'gpt-4o');

        try {
            $response = $client->chat()->create([
                'model' => $model,
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ],
                'temperature' => 0.7,
            ]);

            $content = $response['choices'][0]['message']['content'] ?? null;

            if ($content) {
                // 🧽 Remove Markdown-style ```json blocks if present
                $clean = preg_replace('/^```json|```$/mi', '', trim($content));

                // Decode cleaned content
                $json = json_decode(trim($clean), true);

                if (json_last_error() === JSON_ERROR_NONE && isset($json['best'], $json['second'])) {
                    return $json;
                } else {
                    Log::warning("⚠️ AI returned non-JSON or malformed content after cleaning", ['cleaned' => $clean]);
                }
            }

        } catch (\Throwable $e) {
            Log::error("❌ OpenAI error: " . $e->getMessage());
        }

        return [
            'best' => null,
            'second' => null,
            'reason' => 'No valid AI recommendation available.'
        ];
    }
}
