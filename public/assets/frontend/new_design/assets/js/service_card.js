$(document).ready(function () {
    // Initialize carousel for each card
    $('.card-animate').each(function () {
        const $card = $(this);
        const $track = $card.find('.carousel-track');
        const $slides = $card.find('.carousel-slide');
        const $leftArrow = $card.find('.left-arrow');
        const $rightArrow = $card.find('.right-arrow');
        const $dots = $card.find('.pagination-dot');

        let currentSlide = 0;
        const totalSlides = $slides.length;

        function updateCarousel() {
            // Slide by full percentage (each slide is 100% width)
            const translateX = -currentSlide * 100;
            $track.css('transform', `translateX(${translateX}%)`);

            // Update pagination dots
            $dots.removeClass('active');
            $dots.eq(currentSlide).addClass('active');

            // Update arrow visibility
            if (currentSlide === 0) {
                $leftArrow.addClass('hidden');
            } else {
                $leftArrow.removeClass('hidden');
            }

            if (currentSlide === totalSlides - 1) {
                $rightArrow.addClass('hidden');
            } else {
                $rightArrow.removeClass('hidden');
            }
        }

        // Right arrow click
        $rightArrow.on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            if (currentSlide < totalSlides - 1) {
                currentSlide++;
                updateCarousel();
            }
        });

        // Left arrow click
        $leftArrow.on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            if (currentSlide > 0) {
                currentSlide--;
                updateCarousel();
            }
        });

        // Pagination dot click
        $dots.on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            currentSlide = $(this).data('index');
            updateCarousel();
        });

        // Touch swipe support
        let touchStartX = 0;
        let touchEndX = 0;
        const minSwipeDistance = 50;

        $card.find('.carousel-container').on('touchstart', function (e) {
            touchStartX = e.originalEvent.touches[0].clientX;
        });

        $card.find('.carousel-container').on('touchmove', function (e) {
            touchEndX = e.originalEvent.touches[0].clientX;
        });

        $card.find('.carousel-container').on('touchend', function () {
            const swipeDistance = touchEndX - touchStartX;

            if (Math.abs(swipeDistance) > minSwipeDistance) {
                if (swipeDistance > 0 && currentSlide > 0) {
                    currentSlide--;
                    updateCarousel();
                } else if (swipeDistance < 0 && currentSlide < totalSlides - 1) {
                    currentSlide++;
                    updateCarousel();
                }
            }
        });

        // Initialize
        updateCarousel();
    });

    // Favorite button interaction
    $('.card-animate').on('click', 'button[aria-label="Add to favorites"]', function (e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).toggleClass('text-red-500');
        const $svg = $(this).find('svg');
        if ($(this).hasClass('text-red-500')) {
            $svg.attr('fill', 'currentColor');
        } else {
            $svg.attr('fill', 'none');
        }
    });
});