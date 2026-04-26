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
            <!-- Logged In User Mobile -->
    <a href="{{ $dashboardRoute }}"
       class="border flex items-center justify-center gap-2 border-primary text-primary px-4 py-2 rounded-full hover:bg-primary/20 w-full transition">
        <i class="fa-solid fa-gauge"></i>
        {{ __('Dashboard') }}
    </a>
    @if(moduleExists('Community'))
        <a href="{{ route('community.all') }}"
        class="bg-primary text-white text-base px-5 py-2 rounded-full hover:bg-primary/80 w-full transition flex items-center justify-center gap-2">
            <i class="fa-solid fa-people-group"></i>
            {{ __('Community') }}
        </a>
    @endif
@else
    <!-- Guest User Mobile -->
    @if(moduleExists('Community'))
        <a href="{{ route('community.all') }}"
        class="border flex items-center justify-center gap-2 border-primary text-primary px-4 py-2 rounded-full hover:bg-primary/20 w-full transition">
            <i class="fa-solid fa-people-group"></i>
            {{ __('Community') }}
        </a>
    @endif
    <a href="#" id="openLoginModalMobile"
       class="bg-primary text-white text-base px-5 py-2 rounded-full hover:bg-primary/80 w-full transition flex items-center justify-center gap-2">
        <i class="fa-solid fa-user-plus"></i>
        {{ __('Sign In') }}
    </a>
@endif