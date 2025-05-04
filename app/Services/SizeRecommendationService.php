<?php

namespace App\Services;

use App\Models\Avatar;
use Illuminate\Support\Facades\Auth;

class SizeRecommendationService
{
    private function cmToInches($cm)
    {
        return round($cm / 2.54, 2);
    }

    private function cleanNumericString($value)
    {
        return trim(str_replace(['–', '—', 'to', ' ', '"', "'"], ['-', '-', '-', '', '', ''], strval($value)));
    }

    public function recommendSize($product)
    {
        $user = Auth::user();
        $avatar = Avatar::where('user_id', $user->id)->first();

        if (!$avatar || !$product || !$product->size_chart_data) {
            return null;
        }

        $sizeChart = json_decode($product->size_chart_data, true);
        $productType = $product->product_type ?? 'top';

        if (!$sizeChart || !is_array($sizeChart)) {
            return null;
        }

        if ($productType === 'top') {
            $chestCm = $avatar->chest;
            $chestInches = $this->cmToInches($chestCm);

            foreach ($sizeChart as $entry) {
                if (!isset($entry['width'])) continue;

                $width = $this->cleanNumericString($entry['width']);
                $shirtBust = floatval($width) * 2;

                if ($shirtBust >= $chestInches && ($shirtBust - $chestInches) <= 8.0) {
                    return $entry['size'];
                }
            }

            return null;

        } elseif ($productType === 'bottom') {
            $waistCm = $avatar->waist;
            $hipsCm = $avatar->hips;
            $inseamCm = $avatar->inseam;

            $waistInches = $this->cmToInches($waistCm);
            $hipsInches = $this->cmToInches($hipsCm);
            $inseamInches = $this->cmToInches($inseamCm);

            foreach ($sizeChart as $entry) {
                $waistMatch = false;

                // support waistline_min + waistline_max
                if (isset($entry['waistline_min']) && isset($entry['waistline_max'])) {
                    $min = floatval($this->cleanNumericString($entry['waistline_min']));
                    $max = floatval($this->cleanNumericString($entry['waistline_max']));
                    if ($waistInches >= $min && $waistInches <= $max) {
                        $waistMatch = true;
                    }
                } else {
                    // Fallback: detect 'waist' or 'waistline' or 'width'
                    $waistField = null;
                    foreach ($entry as $key => $value) {
                        $lowerKey = strtolower($key);
                        if (in_array($lowerKey, ['waist', 'waistline', 'width'])) {
                            $waistField = $value;
                            break;
                        }
                    }

                    if ($waistField !== null) {
                        $waistStr = $this->cleanNumericString($waistField);

                        if (preg_match('/^(\d+)-(\d+)$/', $waistStr, $matches)) {
                            $min = floatval($matches[1]);
                            $max = floatval($matches[2]);
                            if ($waistInches >= $min && $waistInches <= $max) {
                                $waistMatch = true;
                            }
                        } elseif (is_numeric($waistStr)) {
                            if (abs($waistInches - floatval($waistStr)) <= 1.0) {
                                $waistMatch = true;
                            }
                        }
                    }
                }

                if (!$waistMatch) continue;

                // Optional hips and inseam check
                $hipsField = isset($entry['hips']) ? floatval($this->cleanNumericString($entry['hips'])) : null;
                $inseamField = isset($entry['inseam']) ? floatval($this->cleanNumericString($entry['inseam'])) : null;

                if ($hipsField && abs($hipsInches - $hipsField) > 3.0) continue;
                if ($inseamField && abs($inseamInches - $inseamField) > 2.0) continue;

                return $entry['size'];
            }

            return null;
        }

        return null;
    }
}
