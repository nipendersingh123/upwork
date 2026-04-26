<!-- Breadcrumb-->
<section class="pt-[82px] pb-6 border-b border-base-300/10">
    <div class="container mx-auto max-w-8xl px-6">
        <nav class="text-gray-600 font-medium" aria-label="Breadcrumb">
            <ol class="list-none p-0 inline-flex space-x-2">
                <!-- First level (Home/Dashboard) -->
                <li class="flex items-center">
                    @auth
                        @if (auth()->user()->user_type == 1)
                            <a href="{{ route('client.dashboard') }}" class="hover:text-primary transition">
                                {{ __('Dashboard') }}
                            </a>
                        @else
                            <a href="{{ route('freelancer.dashboard') }}" class="hover:text-primary transition">
                                {{ __('Dashboard') }}
                            </a>
                        @endif
                    @else
                        <a href="{{ route('homepage') }}" class="hover:text-primary transition">
                            {{ __('Home') }}
                        </a>
                    @endauth
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mx-2 text-gray-400" fill="none"
                         viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </li>

                <!-- Middle levels (optional) -->
                @if(isset($middleLinks) && is_array($middleLinks))
                    @foreach($middleLinks as $link)
                        <li class="flex items-center">
                            <a href="{{ $link['url'] }}" class="hover:text-primary transition">
                                {{ $link['title'] }}
                            </a>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mx-2 text-gray-400" fill="none"
                                 viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </li>
                    @endforeach
                @endif

                <!-- Current page -->
                <li class="flex items-center">
                    <span class="text-gray-500">{{ $innerTitle }}</span>
                </li>
            </ol>
        </nav>
    </div>
</section>