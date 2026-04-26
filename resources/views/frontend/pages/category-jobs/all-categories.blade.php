@extends('frontend.new_design.layout.new_master')
@section('site_title', __('All Categories'))
@section('meta_title'){{ __('All Categories') }}@endsection

@section('content')
    <main>
        <!-- Breadcrumb-->
        <x-breadcrumb.user-profile-breadcrumb-02 :innerTitle="__('All Categories')" />

        <section class="">
            <!-- All Categories -->
            <div class="max-w-7xl mx-auto px-6 py-10">
                <!-- Header -->
                <div class="mb-12">
                    <h1 class="text-3xl font-medium mb-2">{{ __('All Categories') }}</h1>
                    <p class="text-gray-600 text-base">{{ __('Browse through all available service categories') }}</p>
                </div>

                <!-- Categories Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 mb-12" id="categoriesGrid">
                    @forelse($categories as $category)
                        <div class="category-item bg-white border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-shadow duration-300 cursor-pointer group">
                            <a href="{{ route('category.jobs', $category->slug) }}" class="block">
                                <!-- Icon -->
                                <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mb-4 group-hover:bg-primary/20 transition-colors duration-300 overflow-hidden">
                                    <div class="w-12 h-12 rounded-full overflow-hidden">
                                        <div class="w-full h-full [&>img]:w-full [&>img]:h-full [&>img]:object-cover">
                                            {!! render_image_markup_by_attachment_id($category->image) !!}
                                        </div>
                                    </div>
                                </div>

                                <!-- Category Name -->
                                <h3 class="text-lg font-medium text-gray-900 mb-2 group-hover:text-primary transition-colors duration-300">
                                    {{ $category->category }}
                                </h3>

                                <!-- Stats -->
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <span>{{ $category->jobs_count }} {{ __('Jobs') }}</span>
                                </div>
                            </a>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12">
                            <h4 class="text-gray-500 text-xl">{{ __('No Categories Found') }}</h4>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="flex justify-center items-center flex-wrap gap-2" id="paginationContainer">
                    <!-- Pagination will be populated by JavaScript if needed -->
                </div>
            </div>
        </section>

        <!-- Get Started -->
        <section class="container mx-auto max-w-7xl px-6 py-10 md:py-16 lg:py-28">
            <div class="container overflow-hidden mx-auto max-w-7xl px-10 py-10 md:py-20 lg:py-28 bg-[#051D17] rounded-lg relative">
                <!-- Reduce z-index of decorative background -->
                <div class="absolute inset-0 z-0"> <!-- Changed from z-20 to z-0 -->
                    <img class="absolute -left-10 bottom-5" src="{{ asset('assets/images/get-started/arc-2.svg') }}" alt="">
                </div>

                <!-- Keep content at normal stacking level -->
                <div class="text-center relative flex items-center justify-center flex-col gap-6">
                    <h3 class="text-[36px] text-white font-medium animate-on-scroll">{{ __('Get Started with Xilancer') }}</h3>
                    <p class="text-white max-w-[600px] text-center">
                        {{ __('Connect with top freelancers or showcase your skills to clients worldwide. Start your journey today and turn ideas into successful projects.') }}
                    </p>
                    <a href="{{ route('homepage') }}" class="text-white flex font-medium hover:text-white bg-secondary hover:bg-primary transition-all duration-300 px-4 py-2 rounded-lg border-primary/50 items-center gap-2">
                        {{ __('Join Free') }}
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="m4.5 19.5 15-15m0 0H8.25m11.25 0v11.25" />
                        </svg>
                    </a>
                </div>
            </div>
        </section>
    </main>
@endsection

@section('script')
    <script>
        (function ($) {
            "use strict";
            $(document).ready(function () {
                // Client-side pagination for categories
                const itemsPerPage = 16;
                let currentPage = 1;
                let allCategoryItems = [];
                let totalPages = 0;

                function initializePagination() {
                    allCategoryItems = $('.category-item').toArray();
                    totalPages = Math.ceil(allCategoryItems.length / itemsPerPage);

                    if (allCategoryItems.length > itemsPerPage) {
                        renderCategories(1);
                        renderPagination();
                    }
                }

                function renderCategories(page) {
                    $('.category-item').hide();

                    const startIndex = (page - 1) * itemsPerPage;
                    const endIndex = startIndex + itemsPerPage;

                    for (let i = startIndex; i < endIndex && i < allCategoryItems.length; i++) {
                        $(allCategoryItems[i]).show();
                    }
                }

                function renderPagination() {
                    const $pagination = $('#paginationContainer');
                    $pagination.empty();

                    const $prevBtn = $(`
                <button class="flex items-center gap-1 px-3 py-1 border border-gray-300 rounded-full text-gray-700 hover:border-gray-400 transition-colors duration-300 ${currentPage === 1 ? 'opacity-50 cursor-not-allowed' : ''}"
                        id="prevBtn" ${currentPage === 1 ? 'disabled' : ''}>
                    <span>&lt;</span>
                    <span>Previous</span>
                </button>
            `);
                    $pagination.append($prevBtn);

                    const maxVisible = 5;
                    let startPage = Math.max(1, currentPage - Math.floor(maxVisible / 2));
                    let endPage = Math.min(totalPages, startPage + maxVisible - 1);

                    if (endPage - startPage + 1 < maxVisible) {
                        startPage = Math.max(1, endPage - maxVisible + 1);
                    }

                    if (startPage > 1) {
                        const $btn1 = $(`<button class="pagination-btn w-8 h-8 border border-gray-300 rounded-full text-gray-700 hover:border-gray-400 transition-colors duration-300 font-medium" data-page="1">1</button>`);
                        $pagination.append($btn1);
                        if (startPage > 2) {
                            $pagination.append(`<span class="text-gray-500">...</span>`);
                        }
                    }

                    for (let i = startPage; i <= endPage; i++) {
                        const isActive = i === currentPage;
                        const $btn = $(`
                    <button class="pagination-btn w-8 h-8 rounded-full font-medium transition-colors duration-300 ${isActive ? 'bg-secondary text-white border border-secondary' : 'border border-gray-300 text-gray-700 hover:border-gray-400'}" data-page="${i}">${i}</button>
                `);
                        $pagination.append($btn);
                    }

                    if (endPage < totalPages) {
                        if (endPage < totalPages - 1) {
                            $pagination.append(`<span class="text-gray-500">...</span>`);
                        }
                        const $btnLast = $(`<button class="pagination-btn w-8 h-8 border border-gray-300 rounded-full text-gray-700 hover:border-gray-400 transition-colors duration-300 font-medium" data-page="${totalPages}">${totalPages}</button>`);
                        $pagination.append($btnLast);
                    }

                    const $nextBtn = $(`
                <button class="flex items-center gap-1 px-3 py-1 border border-gray-300 rounded-full text-gray-700 hover:border-gray-400 transition-colors duration-300 ${currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : ''}"
                        id="nextBtn" ${currentPage === totalPages ? 'disabled' : ''}>
                    <span>Next</span>
                    <span>&gt;</span>
                </button>
            `);
                    $pagination.append($nextBtn);

                    $pagination.find('.pagination-btn[data-page]').on('click', function () {
                        const page = parseInt($(this).data('page'));
                        goToPage(page);
                    });

                    $('#prevBtn').on('click', function () {
                        if (currentPage > 1) goToPage(currentPage - 1);
                    });

                    $('#nextBtn').on('click', function () {
                        if (currentPage < totalPages) goToPage(currentPage + 1);
                    });
                }

                function goToPage(page) {
                    currentPage = page;
                    renderCategories(page);
                    renderPagination();
                    $('html, body').animate({ scrollTop: 0 }, 'smooth');
                }

                initializePagination();
            });
        }(jQuery));
    </script>
@endsection
