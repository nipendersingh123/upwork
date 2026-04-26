<?php

namespace App\Http\Controllers\Api\Client;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectCollection;
use App\Http\Resources\ProjectDetailResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ProjectController extends Controller
{
    /**
     * Display a listing of projects with filtering.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function project_list(Request $request): JsonResponse
    {
        $query = $this->buildProjectQuery($request);

        $this->applyFilters($query, $request);

        $perPage = min($request->input('per_page', 15), 50);
        $projects = $query->paginate($perPage)->withQueryString();

        if ($projects->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No projects found.',
                'data' => []
            ], Response::HTTP_OK);
        }

        return response()->json(
            new ProjectCollection($projects),
            Response::HTTP_OK
        );
    }

    /**
     * Display top projects by order count.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function top_projects(Request $request): JsonResponse
    {
        $query = $this->buildProjectQuery($request);

        $query->getQuery()->orders = null;

        $query->withCount(['complete_orders as completed_count'])
            ->having('completed_count', '>', 0)
            ->orderByDesc('completed_count');

        $perPage = min($request->input('per_page', 15), 50);
        $projects = $query->paginate($perPage)->withQueryString();

        if ($projects->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No top projects found.',
                'data' => []
            ], Response::HTTP_OK);
        }

        return response()->json(
            new ProjectCollection($projects),
            Response::HTTP_OK
        );
    }

    /**
     * Display the specified project.
     *
     * @param Project $project
     * @return JsonResponse
     */
    public function project_details(string $slug) : JsonResponse
    {
        // Find project by slug
        $project = Project::where('slug', $slug)
            ->where('project_on_off', 1)
            ->where('status', 1)
            ->first();

        // Check if project exists and is active
        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'Project not found or inactive.'
            ], Response::HTTP_NOT_FOUND);
        }

        // Load relationships
        $project->load([
            'project_creator:id,first_name,last_name,username,image,country_id,experience_level',
            'project_creator.user_country:id,country',
            'project_category:id,category,slug',
            'project_sub_categories:id,sub_category,slug',
            'project_attributes'
        ])
            ->loadCount([
                'complete_orders',
                'ratings'
            ])
            ->loadAvg('ratings', 'rating');

        // Check if creator is active
        if (!$project->project_creator || $project->project_creator->is_suspend) {
            return response()->json([
                'success' => false,
                'message' => 'Project creator is not available.'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json(
            new ProjectDetailResource($project),
            Response::HTTP_OK
        );
    }

    /**
     * Build base project query.
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function buildProjectQuery(Request $request)
    {
        $query = Project::query()
            ->with([
                'project_creator:id,first_name,last_name,username,image,country_id,experience_level',
                'project_creator.user_country:id,country',
                'project_category:id,category,slug'
            ])
            ->whereHas('project_creator', function ($q) {
                $q->where('is_suspend', 0);
            })
            ->where('project_on_off', 1)
            ->where('status', 1);

        // Pro projects filter
        if ($request->boolean('is_pro')) {
            $query->where('is_pro', 'yes')
                ->where('pro_expire_date', '>', now());
        }

        // Load rating statistics
        $query->withCount([
            'ratings',
            'complete_orders'
        ])->withAvg('ratings', 'rating');

        return $query->latest();
    }

    /**
     * Apply filters to the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param Request $request
     * @return void
     */
    private function applyFilters($query, Request $request): void
    {
        // Search by title
        if ($request->filled('search')) {
            $search = strip_tags($request->search);
            $query->where('title', 'LIKE', "%{$search}%");
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by subcategory
        if ($request->filled('subcategory_id')) {
            $query->whereHas('project_sub_categories', function ($q) use ($request) {
                $q->where('sub_categories.id', $request->subcategory_id);
            });
        }

        // Filter by country
        if ($request->filled('country_id')) {
            $query->whereHas('project_creator', function ($q) use ($request) {
                $q->where('country_id', $request->country_id);
            });
        }

        // Filter by experience level
        if ($request->filled('experience_level')) {
            $query->whereHas('project_creator', function ($q) use ($request) {
                $q->where('experience_level', $request->experience_level);
            });
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where('basic_regular_charge', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('basic_regular_charge', '<=', $request->max_price);
        }

        // Filter by delivery time
        if ($request->filled('delivery_days')) {
            $query->where('basic_delivery', '<=', $request->delivery_days);
        }

        // Filter by rating
        if ($request->filled('min_rating')) {
            $minRating = (float) $request->min_rating;
            $query->having('ratings_avg_rating', '>=', $minRating);
        }

        // Sort options
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_low':
                    $query->orderBy('basic_regular_charge', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('basic_regular_charge', 'desc');
                    break;
                case 'rating':
                    $query->orderBy('ratings_avg_rating', 'desc');
                    break;
                case 'popular':
                    $query->orderBy('complete_orders_count', 'desc');
                    break;
                default:
                    $query->latest();
            }
        }
    }
}
