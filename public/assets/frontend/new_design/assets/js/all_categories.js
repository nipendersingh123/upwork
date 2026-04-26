const itemsPerPage = 12;
let currentPage = 1;
const totalPages = Math.ceil(categories.length / itemsPerPage);

function renderCategories(page) {
    const $grid = $('#categoriesGrid');
    $grid.empty();

    const startIndex = (page - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const pageCategories = categories.slice(startIndex, endIndex);

    pageCategories.forEach(category => {
        const $card = $(`
        <div class="bg-white border border-gray-200 rounded-lg p-6 hover:border-primary hover:shadow-md transition-all duration-300 cursor-pointer">
            <div class="w-10 h-10 bg-green-50 rounded-full flex items-center justify-center mb-4">
                <img src="${category.icon}" alt="${category.name}" class="w-6 h-6"/>
            </div>
            <h4 class="font-semibold text-gray-900 mb-2 text-base hover:underline ">${category.name}</h4>
            <p class="text-gray-600 text-sm mb-4">${category.description}</p>
            <span class="text-gray-500 text-sm">${category.jobs} Jobs</span>
        </div>
    `);
        $grid.append($card);
    });
}


// Pagination rendering
function renderPagination() {
    const $pagination = $('#paginationContainer');
    $pagination.empty();

    // Previous button
    const $prevBtn = $(`
                <button class="flex items-center gap-1 px-3 py-1 border border-gray-300 rounded-full text-gray-700 hover:border-gray-400 transition-colors duration-300 ${currentPage === 1 ? 'opacity-50 cursor-not-allowed' : ''}" id="prevBtn" ${currentPage === 1 ? 'disabled' : ''}>
                    <span>&lt;</span>
                    <span>Previous</span>
                </button>
            `);
    $pagination.append($prevBtn);

    // Page numbers
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

    // Next button
    const $nextBtn = $(`
                <button class="flex items-center gap-1 px-3 py-1 border border-gray-300 rounded-full text-gray-700 hover:border-gray-400 transition-colors duration-300 ${currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : ''}" id="nextBtn" ${currentPage === totalPages ? 'disabled' : ''}>
                    <span>Next</span>
                    <span>&gt;</span>
                </button>
            `);
    $pagination.append($nextBtn);

    // Attach event listeners
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
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Initial render
renderCategories(1);
renderPagination();
