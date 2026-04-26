<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\Storage;
use App\Http\Services\FreelancerLevelService;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $freelancerLevel = FreelancerLevelService::getFreelancerLevel($this->project_creator?->id);

        return [
            'success' => true,
            'message' => 'Project details retrieved successfully.',
            'data' => [
                'id' => $this->id,
                'title' => $this->title,
                'slug' => $this->slug,
                'description' => $this->description,
                'image' => $this->getProjectImage(),
                'is_promoted' => $this->is_pro_project,
                'pro_expire_date' => $this->is_pro === 'yes' ? $this->pro_expire_date : null,

                // Package Details
                'packages' => [
                    'basic' => [
                        'title' => $this->basic_title,
                        'revision' => (int) $this->basic_revision,
                        'delivery_days' => (int) $this->basic_delivery,
                        'price' => [
                            'regular'  => (float) $this->basic_regular_charge,
                            'discount' => $this->basic_discount_charge
                                ? (float) $this->basic_discount_charge
                                : null,
                            'final'    => (float) ($this->basic_discount_charge ?: $this->basic_regular_charge),
                        ],
                    ],
                    'standard' => $this->standard_title ? [
                        'title' => $this->standard_title,
                        'revision' => (int) $this->standard_revision,
                        'delivery_days' => (int) $this->standard_delivery,
                        'price' => [
                            'regular'  => (float) $this->standard_regular_charge,
                            'discount' => $this->standard_discount_charge
                                ? (float) $this->standard_discount_charge
                                : null,
                            'final'    => (float) ($this->standard_discount_charge ?: $this->standard_regular_charge),
                        ],
                    ] : null,
                    'premium' => $this->premium_title ? [
                        'title' => $this->premium_title,
                        'revision' => (int) $this->premium_revision,
                        'delivery_days' => (int) $this->premium_delivery,
                        'price' => [
                            'regular'  => (float) $this->premium_regular_charge,
                            'discount' => $this->premium_discount_charge
                                ? (float) $this->premium_discount_charge
                                : null,
                            'final'    => (float) ($this->premium_discount_charge ?: $this->premium_regular_charge),
                        ],
                    ] : null,
                ],

                // Attributes/Features
                'attributes' => $this->project_attributes->map(function ($attribute) {
                    return [
                        'id' => $attribute->id,
                        'type' => $attribute->type,
                        'basic_value' => $attribute->basic_check_numeric,
                        'standard_value' => $attribute->standard_check_numeric,
                        'premium_value' => $attribute->premium_check_numeric,
                    ];
                }),

                // Statistics
                'statistics' => [
                    'total_orders' => (int) $this->complete_orders_count,
                    'total_reviews' => (int) $this->ratings_count,
                    'average_rating' => $this->ratings_avg_rating ? round((float) $this->ratings_avg_rating, 2) : null,
                ],

                // Creator Information
                'freelancer' => [
                    'id' => $this->project_creator->id,
                    'name' => $this->project_creator->first_name . ' ' . $this->project_creator->last_name,
                    'username' => $this->project_creator->username,
                    'image' => $this->project_creator->image
                        ? asset('assets/uploads/profile/' . $this->project_creator->image)
                        : asset('assets/static/img/default-profile.png'),
                    'experience_level' => $this->project_creator->experience_level,
                    'country' => $this->project_creator->user_country?->country,
                    'freelancer_level' => $freelancerLevel['level_name'],
                    'level_image' => $freelancerLevel['level_image'],
                ],

                // Category & Subcategories
                'category' => [
                    'id' => $this->project_category->id,
                    'name' => $this->project_category->category,
                    'slug' => $this->project_category->slug,
                ],

                'subcategories' => $this->project_sub_categories->map(function ($subcategory) {
                    return [
                        'id' => $subcategory->id,
                        'name' => $subcategory->sub_category,
                        'slug' => $subcategory->slug,
                    ];
                }),

                // SEO Meta (public info only)
                'meta' => [
                    'title' => $this->meta_title,
                    'description' => $this->meta_description,
                ],

                // Timestamps
                'created_at' => $this->created_at?->toIso8601String(),
                'updated_at' => $this->updated_at?->toIso8601String(),
            ],
        ];
    }

    /**
     * Get project image URL.
     *
     * @return string|null
     */
    private function getProjectImage()
    {
        if (!$this->image) {
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
                    'project/' . $this->image,
                    load_from: $this->load_from
                );
            }
        }

        // Default local storage
        return asset('assets/uploads/project/' . $this->image);
    }
}
