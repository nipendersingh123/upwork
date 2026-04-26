<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\Storage;
use App\Http\Services\FreelancerLevelService;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProjectCollection extends ResourceCollection
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
            'message' => 'Projects retrieved successfully.',
            'data' => $this->collection->map(function ($project) {
                $freelancerLevel = FreelancerLevelService::getFreelancerLevel($project->project_creator?->id);

                return [
                    'id' => $project->id,
                    'title' => $project->title,
                    'slug' => $project->slug,
                    'description' => $project->description ? \Illuminate\Support\Str::limit(strip_tags($project->description), 150) : null,
                    'image' => $this->getProjectImage($project),
                    'is_promoted' => $project->is_pro_project,

                    // Pricing
                    'basic_price' => [
                        'regular'  => (float) $project->basic_regular_charge,
                        'discount' => $project->basic_discount_charge
                            ? (float) $project->basic_discount_charge
                            : null,
                        'final'    => (float) ($project->basic_discount_charge ?: $project->basic_regular_charge),
                    ],
                    'basic_delivery_days' => (int) $project->basic_delivery,

                    // Statistics
                    'statistics' => [
                        'total_orders' => (int) ($project->complete_orders_count ?? 0),
                        'total_reviews' => (int) ($project->ratings_count ?? 0),
                        'average_rating' => $project->ratings_avg_rating ? round((float) $project->ratings_avg_rating, 2) : null,
                    ],

                    // Creator Info
                    'freelancer' => $project->project_creator ? [
                        'id' => $project->project_creator->id,
                        'name' => $project->project_creator->first_name . ' ' . $project->project_creator->last_name,
                        'username' => $project->project_creator->username,
                        'image' => $project->project_creator->image
                            ? asset('assets/uploads/profile/' . $project->project_creator->image)
                            : asset('assets/static/img/default-profile.png'),
                        'experience_level' => $project->project_creator->experience_level,
                        'country' => $project->project_creator->user_country?->country,
                        'freelancer_level' => $freelancerLevel['level_name'],
                        'level_image' => $freelancerLevel['level_image'],
                    ] : null,

                    // Category
                    'category' => $project->project_category ? [
                        'id' => $project->project_category->id,
                        'name' => $project->project_category->category,
                        'slug' => $project->project_category->slug,
                    ] : null,
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
     * Get project image URL.
     *
     * @param $project
     * @return string|null
     */
    private function getProjectImage($project)
    {
        if (!$project->image) {
            return null;
        }

        // Check if cloud storage exists and is configured
        if (
            function_exists('cloudStorageExist')
            && cloudStorageExist()
            && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi'])
        ) {

            if (function_exists('render_frontend_cloud_image_if_module_exists')) {
                return render_frontend_cloud_image_if_module_exists(
                    'project/' . $project->image,
                    load_from: $project->load_from
                );
            }
        }

        // Default local storage
        return asset('assets/uploads/project/' . $project->image);
    }
}
