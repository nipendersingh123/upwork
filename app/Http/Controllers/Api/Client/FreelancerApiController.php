<?php

namespace App\Http\Controllers\Api\Client;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\FreelancerCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class FreelancerApiController extends Controller
{
    private $current_date;

    public function __construct()
    {
        $this->current_date = now()->toDateTimeString();
    }

    /**
     * Display a listing of freelancers with filtering.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = $this->buildFreelancerQuery($request);

        $this->applyFilters($query, $request);

        $perPage = min($request->input('per_page', 15), 50);
        $freelancers = $query->paginate($perPage)->withQueryString();

        if ($freelancers->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No freelancers found.',
                'data' => []
            ], Response::HTTP_OK);
        }

        return response()->json(
            new FreelancerCollection($freelancers),
            Response::HTTP_OK
        );
    }

    /**
     * Build base freelancer query.
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function buildFreelancerQuery(Request $request)
    {
        $query = User::query()
            ->select([
                'id',
                'username',
                'first_name',
                'last_name',
                'image',
                'country_id',
                'state_id',
                'experience_level',
                'hourly_rate',
                'is_pro',
                'pro_expire_date',
                'user_verified_status',
                'load_from',
                'created_at',
                'check_online_status'
            ])
            ->with([
                'user_introduction:id,user_id,title',
                'user_country:id,country',
                'user_state:id,state',
                'freelancer_skill:id,user_id,skill'
            ])
            ->where('user_type', 2)
            ->where('is_email_verified', 1)
            ->where('is_suspend', 0)
            ->where('user_active_inactive_status', 1)
            ->where('check_work_availability', 1)
            ->withCount([
                'freelancer_orders' => function ($q) {
                    $q->where('status', 3);
                }
            ])
            ->withSum([
                'freelancer_orders' => function ($q) {
                    $q->where('status', 3);
                }
            ], 'payable_amount')
            ->withAvg([
                'freelancer_ratings' => function ($q) {
                    $q->where('sender_type', 1);
                }
            ], 'rating')
            ->withCount([
                'freelancer_ratings' => function ($q) {
                    $q->where('sender_type', 1);
                }
            ]);

        // Pro freelancers filter
        if ($request->boolean('is_pro')) {
            $query->where('is_pro', 'yes')
                ->where('pro_expire_date', '>', $this->current_date);
        }

        return $query->orderBy('freelancer_orders_count', 'DESC')
            ->orderBy('check_online_status', 'DESC');
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
        // Search by name or username
        if ($request->filled('search')) {
            $search = strip_tags($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('username', 'LIKE', "%{$search}%")
                    ->orWhere('first_name', 'LIKE', "%{$search}%")
                    ->orWhere('last_name', 'LIKE', "%{$search}%")
                    ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
            });
        }

        // Filter by country
        if ($request->filled('country_id')) {
            $query->where('country_id', $request->country_id);
        }

        // Filter by state
        if ($request->filled('state_id')) {
            $query->where('state_id', $request->state_id);
        }

        // Filter by experience level
        if ($request->filled('experience_level')) {
            $query->where('experience_level', $request->experience_level);
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->whereHas('freelancer_category', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        // Filter by subcategories
        if ($request->filled('subcategory_ids')) {
            $subcategoryIds = is_array($request->subcategory_ids)
                ? $request->subcategory_ids
                : explode(',', $request->subcategory_ids);

            $query->whereHas('freelancer_subcategory', function ($q) use ($subcategoryIds) {
                $q->whereIn('sub_category_id', $subcategoryIds);
            });
        }

        // Filter by skills
        if ($request->filled('skills')) {
            $skills = is_array($request->skills)
                ? $request->skills
                : explode(',', $request->skills);

            $skills = array_map('trim', $skills);

            $query->whereHas('freelancer_skill', function ($q) use ($skills) {
                foreach ($skills as $index => $skill) {
                    if ($index === 0) {
                        $q->where('skill', 'LIKE', "%{$skill}%");
                    } else {
                        $q->orWhere('skill', 'LIKE', "%{$skill}%");
                    }
                }
            });
        }

        // Filter by hourly rate range
        if ($request->filled('min_hourly_rate')) {
            $query->where('hourly_rate', '>=', $request->min_hourly_rate);
        }

        if ($request->filled('max_hourly_rate')) {
            $query->where('hourly_rate', '<=', $request->max_hourly_rate);
        }

        // Filter by minimum rating
        if ($request->filled('min_rating')) {
            $minRating = (float) $request->min_rating;
            $query->having('freelancer_ratings_avg_rating', '>=', $minRating);
        }

        // Filter by minimum completed orders
        if ($request->filled('min_orders')) {
            $query->having('freelancer_orders_count', '>=', $request->min_orders);
        }

        // Filter by talent badge (FreelancerLevel module)
        if ($request->filled('talent_badge') && moduleExists('FreelancerLevel')) {
            $this->applyTalentBadgeFilter($query, $request->talent_badge);
        }

        // Filter by verified status
        if ($request->boolean('verified_only')) {
            $query->where('user_verified_status', 1);
        }

        // Filter by online status
        if ($request->boolean('online_only')) {
            $query->where('check_online_status', '>=', now()->subMinutes(15));
        }

        // Sort options
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'rating':
                    $query->orderBy('freelancer_ratings_avg_rating', 'desc');
                    break;
                case 'rate_low':
                    $query->orderBy('hourly_rate', 'asc');
                    break;
                case 'rate_high':
                    $query->orderBy('hourly_rate', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'orders':
                    $query->orderBy('freelancer_orders_count', 'desc');
                    break;
                case 'earnings':
                    $query->orderBy('freelancer_orders_sum_payable_amount', 'desc');
                    break;
            }
        }
    }

    /**
     * Apply talent badge filter based on FreelancerLevel module.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $talentBadge
     * @return void
     */
    private function applyTalentBadgeFilter($query, int $talentBadge): void
    {
        $rule = \Modules\FreelancerLevel\Entities\FreelancerLevelRules::where('period', $talentBadge)->first();

        if (!$rule) {
            return;
        }

        $minDays = null;
        $maxDays = null;

        if ($talentBadge >= 1 && $talentBadge < 3) {
            $minDays = 30;
            $maxDays = 90;
        } elseif ($talentBadge >= 3 && $talentBadge < 6) {
            $minDays = 90;
            $maxDays = 180;
        } elseif ($talentBadge >= 6 && $talentBadge < 9) {
            $minDays = 180;
            $maxDays = 270;
        } elseif ($talentBadge >= 9 && $talentBadge < 12) {
            $minDays = 270;
            $maxDays = 360;
        } elseif ($talentBadge >= 12) {
            $minDays = 360;
            $maxDays = null;
        }

        if ($minDays !== null) {
            $maxDate = now()->subDays($minDays);
            $minDate = $maxDays ? now()->subDays($maxDays) : null;

            if ($minDate) {
                $query->whereDate('created_at', '>=', $minDate)
                    ->whereDate('created_at', '<=', $maxDate);
            } else {
                $query->whereDate('created_at', '<=', $maxDate);
            }

            // Filter by orders and earnings
            $query->whereExists(function ($q) use ($rule) {
                $q->select(DB::raw(1))
                    ->from('orders')
                    ->whereColumn('orders.freelancer_id', 'users.id')
                    ->where('orders.status', 3)
                    ->groupBy('orders.freelancer_id')
                    ->havingRaw('COUNT(*) >= ?', [$rule->complete_order])
                    ->havingRaw('SUM(orders.payable_amount) >= ?', [$rule->earning]);
            });

            // Filter by average rating
            $query->whereExists(function ($q) use ($rule) {
                $q->select(DB::raw(1))
                    ->from('ratings')
                    ->join('orders', 'orders.id', '=', 'ratings.order_id')
                    ->whereColumn('orders.freelancer_id', 'users.id')
                    ->where('orders.status', 3)
                    ->where('ratings.sender_type', 1)
                    ->groupBy('orders.freelancer_id')
                    ->havingRaw('AVG(ratings.rating) >= ?', [$rule->avg_rating]);
            });
        }
    }
}
