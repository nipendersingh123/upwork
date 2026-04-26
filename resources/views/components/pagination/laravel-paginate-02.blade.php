<!-- Pagination -->
@if($allData->hasPages())
    <div class="flex justify-center items-center flex-wrap gap-2">
        {{-- Previous Button --}}
        @if ($allData->onFirstPage())
            <button disabled class="flex items-center gap-1 px-3 py-1 border border-gray-300 rounded-full text-base-400 opacity-50 cursor-not-allowed">
                <i class="fa-solid fa-angle-left"></i>
                <span>{{ __('Prev') }}</span>
            </button>
        @else
            <a href="javascript:void(0)" data-url="{{ $allData->previousPageUrl() }}" class="pagination-link flex items-center gap-1 px-3 py-1 border border-gray-300 rounded-full text-base-400 hover:border-gray-400 transition-colors duration-300">
                <i class="fa-solid fa-angle-left"></i>
                <span>{{ __('Prev') }}</span>
            </a>
        @endif

        {{-- Pagination Elements --}}
        @php
            $currentPage = $allData->currentPage();
            $lastPage = $allData->lastPage();
            $start = max(1, $currentPage - 2);
            $end = min($lastPage, $currentPage + 2);
        @endphp

        {{-- First page --}}
        @if ($start > 1)
            <a href="javascript:void(0)" data-url="{{ $allData->url(1) }}" class="pagination-link w-8 h-8 border border-gray-300 rounded-full text-base-400 hover:border-gray-400 transition-colors duration-300 font-medium flex items-center justify-center text-sm">1</a>
            @if ($start > 2)
                <span class="px-2 text-gray-500">...</span>
            @endif
        @endif

        {{-- Page numbers around current page --}}
        @for ($page = $start; $page <= $end; $page++)
            @if ($page == $currentPage)
                <button class="w-8 h-8 border border-gray-300 rounded-full text-white bg-secondary hover:border-gray-400 transition-colors duration-300 font-medium flex items-center justify-center text-sm">{{ $page }}</button>
            @else
                <a href="javascript:void(0)" data-url="{{ $allData->url($page) }}" class="pagination-link w-8 h-8 border border-gray-300 rounded-full text-base-400 hover:border-gray-400 transition-colors duration-300 font-medium flex items-center justify-center text-sm">{{ $page }}</a>
            @endif
        @endfor

        {{-- Last page --}}
        @if ($end < $lastPage)
            @if ($end < $lastPage - 1)
                <span class="px-2 text-gray-500">...</span>
            @endif
            <a href="javascript:void(0)" data-url="{{ $allData->url($lastPage) }}" class="pagination-link w-8 h-8 border border-gray-300 rounded-full text-base-400 hover:border-gray-400 transition-colors duration-300 font-medium flex items-center justify-center text-sm">{{ $lastPage }}</a>
        @endif

        {{-- Next Button --}}
        @if ($allData->hasMorePages())
            <a href="javascript:void(0)" data-url="{{ $allData->nextPageUrl() }}" class="pagination-link flex items-center gap-1 px-3 py-1 border border-gray-300 rounded-full text-base-400 hover:border-gray-400 transition-colors duration-300">
                <span>{{ __('Next') }}</span>
                <i class="fa-solid fa-angle-right"></i>
            </a>
        @else
            <button disabled class="flex items-center gap-1 px-3 py-1 border border-gray-300 rounded-full text-base-400 opacity-50 cursor-not-allowed">
                <span>{{ __('Next') }}</span>
                <i class="fa-solid fa-angle-right"></i>
            </button>
        @endif
    </div>
@endif