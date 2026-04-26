(function ($) {
    'use strict';

    $(document).ready(function () {
        // Client-side pagination for initial load
        const itemsPerPage = 12;
        let currentPage = 1;
        let allBlogItems = [];
        let totalPages = 0;

        function initializePagination() {
            allBlogItems = $('.blog-item').toArray();
            totalPages = Math.ceil(allBlogItems.length / itemsPerPage);

            // If no blogs at all, don't show pagination
            if (allBlogItems.length === 0) {
                $('#paginationContainer').empty().hide();
                return;
            }

            // Always render articles and pagination if there are blogs
            renderArticles(1);
            renderPagination();
        }

        function renderArticles(page) {
            // Hide all items first
            $('.blog-item').hide();

            // Calculate range
            const startIndex = (page - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;

            // Show only items for current page
            for (let i = startIndex; i < endIndex && i < allBlogItems.length; i++) {
                $(allBlogItems[i]).show();
            }
        }

        function renderPagination() {
            const $pagination = $('#paginationContainer');
            $pagination.empty().show();

            // Previous button (disabled if on page 1 or only 1 page)
            const isPrevDisabled = currentPage === 1;
            const $prevBtn = $(`
                <button class="flex items-center gap-1 px-3 py-1 border border-gray-300 rounded-full text-gray-700 transition-colors duration-300 ${isPrevDisabled ? 'opacity-50 cursor-not-allowed' : 'hover:border-gray-400'}" 
                        id="prevBtn" ${isPrevDisabled ? 'disabled' : ''}>
                    <span>&lt;</span>
                    <span>Previous</span>
                </button>
            `);
            $pagination.append($prevBtn);

            // Page numbers
            if (totalPages === 1) {
                // Only show page 1
                const $btn = $(`
                    <button class="pagination-btn w-8 h-8 rounded-full font-medium transition-colors duration-300 bg-secondary text-white border border-secondary" 
                            data-page="1">1</button>
                `);
                $pagination.append($btn);
            } else {
                // Multiple pages logic
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
                        <button class="pagination-btn w-8 h-8 rounded-full font-medium transition-colors duration-300 ${isActive ? 'bg-secondary text-white border border-secondary' : 'border border-gray-300 text-gray-700 hover:border-gray-400'}" 
                                data-page="${i}">${i}</button>
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
            }

            // Next button (disabled if on last page or only 1 page)
            const isNextDisabled = currentPage === totalPages;
            const $nextBtn = $(`
                <button class="flex items-center gap-1 px-3 py-1 border border-gray-300 rounded-full text-gray-700 transition-colors duration-300 ${isNextDisabled ? 'opacity-50 cursor-not-allowed' : 'hover:border-gray-400'}" 
                        id="nextBtn" ${isNextDisabled ? 'disabled' : ''}>
                    <span>Next</span>
                    <span>&gt;</span>
                </button>
            `);
            $pagination.append($nextBtn);

            // Attach event listeners
            $pagination.find('.pagination-btn[data-page]').off('click').on('click', function () {
                const page = parseInt($(this).data('page'));
                goToPage(page);
            });

            $('#prevBtn').off('click').on('click', function () {
                if (currentPage > 1) goToPage(currentPage - 1);
            });

            $('#nextBtn').off('click').on('click', function () {
                if (currentPage < totalPages) goToPage(currentPage + 1);
            });
        }

        function goToPage(page) {
            currentPage = page;
            renderArticles(page);
            renderPagination();
            $('html, body').animate({ scrollTop: 0 }, 'smooth');
        }

        // Initialize pagination on page load
        initializePagination();

        // Category filter (if you have category filter buttons)
        $(document).on('click', '.filter_blog', function (e) {
            e.preventDefault();
            $(this).siblings().removeClass('active');
            $(this).addClass('active');
            let category = $(this).data('blog-category');

            $.ajax({
                url: "/blog/filter", // Adjust route as needed
                method: 'GET',
                data: { category: category },
                success: function (res) {
                    if (res.status === 'nothing') {
                        $('#articlesGrid').parent().html(
                            `<div class="col-span-full text-center py-12">
                                <div class="inline-block p-6 bg-red-50 rounded-lg">
                                    <i class="fas fa-times text-red-500 text-4xl mb-4"></i>
                                    <h4 class="text-xl font-semibold text-red-600 mb-2">OPPS!</h4>
                                    <p class="text-gray-600">Nothing Found</p>
                                </div>
                            </div>`
                        );
                    } else {
                        $('#articlesGrid').parent().html(res);
                        initializePagination(); // Reinitialize pagination after filter
                    }
                }
            });
        });

        // Server-side pagination (for filtered results)
        $(document).on('click', '.pagination a', function (e) {
            e.preventDefault();
            let page = $(this).attr('href').split('page=')[1];
            let category = $('.filter_blog.active').data('blog-category') || 'all';
            loadBlogs(page, category);
        });

        function loadBlogs(page, category) {
            $.ajax({
                url: "/blog/pagination?page=" + page, // Adjust route as needed
                method: 'GET',
                data: { category: category },
                success: function (res) {
                    if (res.status === 'nothing') {
                        $('#articlesGrid').parent().html(
                            `<div class="col-span-full text-center py-12">
                                <h4 class="text-danger text-xl">Nothing Found</h4>
                            </div>`
                        );
                    } else {
                        $('#articlesGrid').parent().html(res);
                        initializePagination();
                    }
                    $('html, body').animate({ scrollTop: 0 }, 'smooth');
                }
            });
        }
    });

}(jQuery));