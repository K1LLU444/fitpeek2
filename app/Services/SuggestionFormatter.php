<?php

namespace App\Services;

use Illuminate\Support\Str;

class SuggestionFormatter
{
    public static function format(string $text): string
    {
        $lines = preg_split("/\r\n|\n|\r/", $text);
        $formatted = [];
        $currentSection = null;

        foreach ($lines as $line) {
            $trimmed = trim($line);

            if (Str::startsWith($trimmed, 'For Men:')) {
                if ($currentSection) $formatted[] = "</ul>";
                $currentSection = 'men';
                $formatted[] = "<p class='font-semibold text-gray-800 mt-4'>👔 For Men</p><ul class='list-disc list-inside space-y-1'>";
            } elseif (Str::startsWith($trimmed, 'For Women:')) {
                if ($currentSection) $formatted[] = "</ul>";
                $currentSection = 'women';
                $formatted[] = "<p class='font-semibold text-pink-700 mt-4'>👗 For Women</p><ul class='list-disc list-inside space-y-1'>";
            } elseif ($currentSection && Str::contains($trimmed, ':')) {
                $formatted[] = '<li><strong>' . preg_replace('/^([^:]+):/', '$1:</strong>', e($trimmed)) . '</li>';
            } elseif ($trimmed !== '') {
                if ($currentSection) {
                    $formatted[] = "</ul>";
                    $currentSection = null;
                }
                $formatted[] = "<p>" . e($trimmed) . "</p>";
            }
        }

        if ($currentSection) {
            $formatted[] = "</ul>";
        }

        return implode("\n", $formatted);
    }
}
