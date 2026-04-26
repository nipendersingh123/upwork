<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function all_banners()
    {
        $banners = Banner::with(['category', 'project'])
            ->where('status', 1)
            ->latest()
            ->get()
            ->map(function ($banner) {
                return [
                    'id' => $banner->id,
                    'image' => get_attachment_url_only($banner->image),
                    'type' => $banner->type,
                    'category_id' => $banner->category_id,
                    'category_name' => optional($banner->category)->category,
                    'project_id' => $banner->project_id,
                    'project_title' => optional($banner->project)->title,
                ];
            });

        return response()->json([
            'banners' => $banners
        ]);
    }
}
