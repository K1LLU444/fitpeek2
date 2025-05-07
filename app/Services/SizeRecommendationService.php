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
        $productType = strtolower($product->product_type ?? 'top');

        if (!$sizeChart || !is_array($sizeChart)) {
            return null;
        }

        $firstEntry = array_change_key_case($sizeChart[0], CASE_LOWER);

        // 👗 DRESSES: bust, waist, hips (cm)
        if ($productType === 'dress') {
            $bustCm = $avatar->chest;
            $waistCm = $avatar->waist;
            $hipsCm = $avatar->hips;

            $bestFit = null;
            $minTotalDiff = PHP_INT_MAX;

            foreach ($sizeChart as $entryRaw) {
                $entry = array_change_key_case($entryRaw, CASE_LOWER);

                if (isset($entry['bust'], $entry['waist'], $entry['hip'])) {
                    $bustVal = floatval($this->cleanNumericString($entry['bust']));
                    $waistVal = floatval($this->cleanNumericString($entry['waist']));
                    $hipVal = floatval($this->cleanNumericString($entry['hip']));

                    $bustDiff = abs($bustCm - $bustVal);
                    $waistDiff = abs($waistCm - $waistVal);
                    $hipDiff = abs($hipsCm - $hipVal);

                    $withinTolerance = $bustDiff <= 4.0 && $waistDiff <= 5.0 && $hipDiff <= 7.0;

                    if ($withinTolerance) {
                        $totalDiff = $bustDiff + $waistDiff + $hipDiff;
                        if ($totalDiff < $minTotalDiff) {
                            $minTotalDiff = $totalDiff;
                            $bestFit = $entry['size'];
                        }
                    }
                }
            }

            if ($bestFit !== null) {
                return $bestFit;
            }

            return null;
        }

        // 👕 TOPS: Shirt width (inches)
        if ($productType === 'top') {
            $bustCm = $avatar->chest;
            $bustInches = $this->cmToInches($bustCm);
            $easeAllowance = 4.0;

            $matchingSizes = [];

            foreach ($sizeChart as $entryRaw) {
                $entry = array_change_key_case($entryRaw, CASE_LOWER);

                if (isset($entry['width'])) {
                    $width = $this->cleanNumericString($entry['width']);
                    $shirtBust = floatval($width) * 2;

                    if ($shirtBust >= ($bustInches + $easeAllowance)) {
                        $matchingSizes[] = [
                            'size' => $entry['size'],
                            'bust' => $shirtBust
                        ];
                    }
                }
            }

            if (!empty($matchingSizes)) {
                usort($matchingSizes, fn($a, $b) => $a['bust'] <=> $b['bust']);
                return $matchingSizes[0]['size'];
            }

            // fallback: return largest size
            $fallback = null;
            $maxWidth = 0;
            foreach ($sizeChart as $entryRaw) {
                $entry = array_change_key_case($entryRaw, CASE_LOWER);
                if (isset($entry['width'])) {
                    $width = floatval($this->cleanNumericString($entry['width']));
                    if ($width > $maxWidth) {
                        $maxWidth = $width;
                        $fallback = $entry['size'];
                    }
                }
            }

            return $fallback;
        }

        // 👗 SKIRTS or BOTTOMS without inseam: waist + hips only
        if (
            $productType === 'skirt' ||
            ($productType === 'bottom' &&
                isset($firstEntry['waist'], $firstEntry['hip']) &&
                !isset($firstEntry['inseam']))
        ) {
            $waistCm = $avatar->waist;
            $hipsCm = $avatar->hips;

            foreach ($sizeChart as $entryRaw) {
                $entry = array_change_key_case($entryRaw, CASE_LOWER);

                $waistVal = isset($entry['waist']) ? floatval($this->cleanNumericString($entry['waist'])) : null;
                $hipVal = isset($entry['hip']) ? floatval($this->cleanNumericString($entry['hip'])) : null;

                if ($waistVal === null || $hipVal === null) continue;

                $waistMatch = abs($waistCm - $waistVal) <= 5.0;
                $hipMatch = abs($hipsCm - $hipVal) <= 7.0;

                if ($waistMatch && $hipMatch) {
                    return $entry['size'];
                }
            }

            return null;
        }

        // 👖 STANDARD BOTTOMS: pants, shorts
        if ($productType === 'bottom') {
            $waistCm = $avatar->waist;
            $hipsCm = $avatar->hips;
            $inseamCm = $avatar->inseam;

            $waistInches = $this->cmToInches($waistCm);
            $hipsInches = $this->cmToInches($hipsCm);
            $inseamInches = $this->cmToInches($inseamCm);

            foreach ($sizeChart as $entryRaw) {
                $entry = array_change_key_case($entryRaw, CASE_LOWER);
                $waistMatch = false;

                if (isset($entry['waistline_min'], $entry['waistline_max'])) {
                    $min = floatval($this->cleanNumericString($entry['waistline_min']));
                    $max = floatval($this->cleanNumericString($entry['waistline_max']));
                    if ($waistInches >= $min && $waistInches <= $max) {
                        $waistMatch = true;
                    }
                } else {
                    $waistField = null;
                    foreach ($entry as $key => $value) {
                        if (in_array($key, ['waist', 'waistline', 'width'])) {
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

                $hipRaw = $entry['hip'] ?? $entry['hips'] ?? null;
                $hipVal = is_numeric($hipRaw) ? floatval($this->cleanNumericString($hipRaw)) : null;
                if ($hipVal !== null && abs($hipsInches - $hipVal) > 7.0) continue;

                $inseamField = isset($entry['inseam']) ? floatval($this->cleanNumericString($entry['inseam'])) : null;
                if ($inseamField && abs($inseamInches - $inseamField) > 2.0) continue;

                return $entry['size'];
            }

            return null;
        }

        return null;
    }
}
