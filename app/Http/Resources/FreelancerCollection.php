<?php

namespace App\Http\Resources;

use App\Http\Services\FreelancerLevelService;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FreelancerCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'success' => true,
            'message' => 'Freelancers retrieved successfully.',
            'data' => $this->collection->map(function ($freelancer) {
                $freelancerLevel = FreelancerLevelService::getFreelancerLevel($freelancer->id);

                return [
                    'id' => $freelancer->id,
                    'username' => $freelancer->username,
                    'name' => $freelancer->first_name . ' ' . $freelancer->last_name,
                    'image' => $this->getFreelancerImage($freelancer),
                    'title' => $freelancer->user_introduction?->title,
                    'is_promoted' => $freelancer->is_pro_freelancer,
                    'is_verified' => (bool) $freelancer->user_verified_status,
                    'is_online' => $this->isOnline($freelancer),

                    // Location
                    'location' => [
                        'country' => $freelancer->user_country?->country,
                        'state' => $freelancer->user_state?->state,
                    ],

                    // Professional Info
                    'experience_level' => $freelancer->experience_level,
                    'hourly_rate' => $freelancer->hourly_rate ? (float) $freelancer->hourly_rate : null,

                    // Freelancer Level
                    'freelancer_level' => $freelancerLevel['level_name'],
                    'level_image' => $freelancerLevel['level_image'],

                    // Statistics
                    'statistics' => [
                        'total_orders' => (int) ($freelancer->freelancer_orders_count ?? 0),
                        'total_reviews' => (int) ($freelancer->freelancer_ratings_count ?? 0),
                        'average_rating' => $freelancer->freelancer_ratings_avg_rating
                            ? round((float) $freelancer->freelancer_ratings_avg_rating, 2)
                            : null,
                    ],

                    // Skills
                    'skills' => $freelancer->freelancer_skill->map(function ($skill) {
                        return [
                            'name' => $skill->skill
                        ];
                    }),

                    // Timestamps
                    'member_since' => $freelancer->created_at?->format('Y-m-d'),
                ];
            }),

            'pagination' => [
                'current_page' => $this->currentPage(),
                'last_page' => $this->lastPage(),
                'per_page' => $this->perPage(),
                'total' => $this->total(),
                'from' => $this->firstItem(),
                'to' => $this->lastItem(),
                'links' => [
                    'first' => $this->url(1),
                    'last' => $this->url($this->lastPage()),
                    'prev' => $this->previousPageUrl(),
                    'next' => $this->nextPageUrl(),
                ],
            ],
        ];
    }

    /**
     * Get freelancer image URL.
     *
     * @param $freelancer
     * @return string
     */
    private function getFreelancerImage($freelancer): string
    {
        if (!$freelancer->image) {
            return asset('assets/static/img/default-profile.png');
        }

        // Check if cloud storage exists and is configured
        if (
            function_exists('cloudStorageExist')
            && cloudStorageExist()
            && in_array(\Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi'])
        ) {

            if (function_exists('render_frontend_cloud_image_if_module_exists')) {
                return render_frontend_cloud_image_if_module_exists(
                    'profile/' . $freelancer->image,
                    load_from: $freelancer->load_from
                );
            }
        }

        // Default local storage
        return asset('assets/uploads/profile/' . $freelancer->image);
    }

    /**
     * Check if freelancer is online.
     *
     * @param $freelancer
     * @return bool
     */
    private function isOnline($freelancer): bool
    {
        if (!$freelancer->check_online_status) {
            return false;
        }

        return $freelancer->check_online_status >= now()->subMinutes(15);
    }
}
