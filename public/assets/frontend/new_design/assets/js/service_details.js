$(document).ready(function () {
    let currentIndex = 0;

    // Get screens from existing HTML (not from mock data)
    const screens = document.querySelectorAll('.screen-card');

    function updateCarousel() {
        const $track = $('#carouselTrack');
        if (screens.length === 0) return;

        const offset = -currentIndex * 100;
        $track.css('transform', `translateX(${offset}%)`);

        // Update active thumbnail
        $('.thumbnail').removeClass('active');
        $(`.thumbnail[data-index="${currentIndex}"]`).addClass('active');
    }

    $('#servicePrevButton').on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();

        if (screens.length === 0) return;

        if (currentIndex === 0) {
            currentIndex = screens.length - 1;
        } else {
            currentIndex--;
        }

        updateCarousel();
    });

    $('#serviceNextButton').on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();

        if (screens.length === 0) return;

        if (currentIndex === screens.length - 1) {
            currentIndex = 0;
        } else {
            currentIndex++;
        }

        updateCarousel();
    });

    // Thumbnail click handler
    $(document).on('click', '.thumbnail', function (e) {
        e.preventDefault();
        e.stopPropagation();

        const index = parseInt($(this).data('index'), 10) || 0;
        if (index >= 0 && index < screens.length) {
            currentIndex = index;
            updateCarousel();
        }
    });

    // Keyboard navigation
    $(document).on('keydown', function (e) {
        if (e.key === 'ArrowLeft') {
            $('#servicePrevButton').click();
        } else if (e.key === 'ArrowRight') {
            $('#serviceNextButton').click();
        }
    });

    // Touch swipe support
    let touchStartX = 0;
    let touchEndX = 0;

    $('.carousel-container-details').on('touchstart', function (e) {
        touchStartX = e.changedTouches[0].screenX;
    });

    $('.carousel-container-details').on('touchend', function (e) {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    });

    function handleSwipe() {
        if (touchEndX < touchStartX - 50) {
            // Swipe left
            $('#serviceNextButton').click();
        }
        if (touchEndX > touchStartX + 50) {
            // Swipe right
            $('#servicePrevButton').click();
        }
    }

    // Initialize carousel with existing images
    updateCarousel();

    // for Payment package option
    $('.package-tab').click(function (e) {
        if (e && typeof e.preventDefault === 'function') e.preventDefault();
        const packageName = $(this).data('package');

        $('.package-tab').removeClass('active');
        $(this).addClass('active');

        $('.package-content').addClass('hidden');
        $(`#${packageName}Package`).removeClass('hidden');
    });
});