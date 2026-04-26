@if(Auth::check())
    @php
        $user = Auth::user();
        // Determine dashboard and logout routes based on user type
        if($user->user_type == 1) {
            $dashboardRoute = route('client.dashboard');
            $logoutRoute = route('client.logout');
        } elseif($user->user_type == 2) {
            $dashboardRoute = route('freelancer.dashboard');
            $logoutRoute = route('freelancer.logout');
        } else {
            $dashboardRoute = route('homepage'); // Fallback
            $logoutRoute = route('homepage'); // Fallback
        }
    @endphp
            <!-- Logged In User -->
    <a href="{{ $dashboardRoute }}" class="border border-primary text-base-300 px-4 py-1 rounded-full hover:bg-primary/10 font-medium transition-all duration-300">
        {{ __('Dashboard') }}
    </a>
    @if(moduleExists('Community'))
        <a href="{{ route('community.all') }}" class="bg-primary text-white text-base-100 px-5 py-1 rounded-full hover:bg-secondary transition-all duration-300 font-medium">
            {{ __('Community') }}
        </a>
    @endif
@else
    <!-- Guest User -->
    @if(moduleExists('Community'))
        <a href="{{ route('community.all') }}" class="border border-primary text-base-300 px-4 py-1 rounded-full hover:bg-primary/10 font-medium transition-all duration-300">
            {{ __('Community') }}
        </a>
    @endif
    <a href="#" id="openLoginModal" class="bg-primary text-white text-base-100 px-5 py-1 rounded-full hover:bg-secondary transition-all duration-300 font-medium">
        {{ __('Sign Up') }}
    </a>
@endif