<?php

namespace App\Http\Services;

use App\Models\User;
use App\Models\Order;
use Carbon\Carbon;

class FreelancerLevelService
{
    /**
     * Get freelancer level based on their performance
     *
     * @param int $freelancerId
     * @return array
     */
    public static function getFreelancerLevel(int $freelancerId): array
    {
        if (!moduleExists('FreelancerLevel')) {
            return [
                'level_name' => null,
                'level_image' => null,
            ];
        }

        // Get levels with rules
        $levels = \Modules\FreelancerLevel\Entities\FreelancerLevel::with('level_rule')
            ->whereHas('level_rule')
            ->where('status', 1)
            ->orderBy('id', 'asc')
            ->get();

        if ($levels->isEmpty()) {
            return [
                'level_name' => null,
                'level_image' => null,
            ];
        }

        // Get freelancer details
        $freelancer = User::select('id', 'created_at')
            ->where('id', $freelancerId)
            ->first();

        if (!$freelancer) {
            return [
                'level_name' => null,
                'level_image' => null,
            ];
        }

        // Calculate days since registration
        $diffInDays = $freelancer->created_at->diffInDays(Carbon::now());

        // Get freelancer statistics
        $totalOrders = Order::where('freelancer_id', $freelancerId)
            ->where('status', 3)
            ->count();

        $totalEarnings = Order::where('freelancer_id', $freelancerId)
            ->where('status', 3)
            ->sum('payable_amount');

        $avgRating = self::getFreelancerAvgRating($freelancerId);

        // Initialize result
        $result = [
            'level_name' => null,
            'level_image' => null,
        ];

        // Check each level (from lowest to highest)
        foreach ($levels as $level) {
            $period = $level->level_rule->period;
            $requiredOrders = $level->level_rule->complete_order;
            $requiredEarnings = $level->level_rule->earning;
            $requiredRating = $level->level_rule->avg_rating;

            $meetsRequirements = $totalOrders >= $requiredOrders
                && $totalEarnings >= $requiredEarnings
                && $avgRating >= $requiredRating;

            // Check period and requirements
            if ($period >= 1 && $period < 3) {
                // 1-3 months (30-90 days)
                if ($diffInDays >= 30 && $diffInDays < 90 && $meetsRequirements) {
                    $result = self::formatLevelResult($level);
                }
            } elseif ($period >= 3 && $period < 6) {
                // 3-6 months (90-180 days)
                if ($diffInDays >= 90 && $diffInDays < 180 && $meetsRequirements) {
                    $result = self::formatLevelResult($level);
                }
            } elseif ($period >= 6 && $period < 9) {
                // 6-9 months (180-270 days)
                if ($diffInDays >= 180 && $diffInDays < 270 && $meetsRequirements) {
                    $result = self::formatLevelResult($level);
                }
            } elseif ($period >= 9 && $period < 12) {
                // 9-12 months (270-360 days)
                if ($diffInDays >= 270 && $diffInDays < 360 && $meetsRequirements) {
                    $result = self::formatLevelResult($level);
                }
            } elseif ($period >= 12) {
                // 12+ months (360+ days)
                if ($diffInDays >= 360 && $meetsRequirements) {
                    $result = self::formatLevelResult($level);
                }
            }
        }

        return $result;
    }

    /**
     * Format level result
     *
     * @param $level
     * @return array
     */
    private static function formatLevelResult($level): array
    {
        return [
            'level_name' => ucfirst($level->level),
            'level_image' => function_exists('get_attachment_url_only')
                ? get_attachment_url_only($level->image)
                : asset('assets/uploads/levels/' . $level->image),
        ];
    }

    /**
     * Get freelancer average rating
     *
     * @param int $freelancerId
     * @return float
     */
    private static function getFreelancerAvgRating(int $freelancerId): float
    {
        if (function_exists('freelancer_rating_for_level')) {
            return (float) freelancer_rating_for_level($freelancerId);
        }

        // Fallback calculation
        return (float) (
            User::where('id', $freelancerId)
            ->withAvg('freelancer_ratings', 'rating')
            ->first()
            ?->freelancer_ratings_avg_rating ?? 0
        );
    }
}
