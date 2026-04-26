$(document).ready(function () {

    // ============================================
    // OUTER CAROUSEL (Sliding between cards)
    // ============================================

    let currentIndex = 0;
    const $outerTrack = $('#trendingCards');
    const $cards = $outerTrack.find('.service-card');
    const totalCards = $cards.length;

    function getVisibleCards() {
        const containerWidth = $outerTrack.parent().width();
        let visibleCards = Math.floor(containerWidth / 424);
        visibleCards = Math.max(1, Math.min(3, visibleCards));
        return visibleCards;
    }

    function updateOuterCarousel() {
        const visibleCards = getVisibleCards();
        const maxIndex = Math.max(0, totalCards - visibleCards);
        currentIndex = Math.max(0, Math.min(currentIndex, maxIndex));

        const cardWidth = $cards.first().outerWidth(true);
        const translateX = currentIndex * cardWidth * -1;

        $outerTrack.css({
            transform: `translateX(${translateX}px)`,
            transition: 'transform 0.5s ease-in-out'
        });

        // Update button visibility - CHANGED IDs
        if (visibleCards >= totalCards) {
            $('#projectPrevBtn, #projectNextBtn').removeClass('opacity-100 visible').addClass('opacity-0 invisible');
        } else {
            if (currentIndex === 0) {
                $('#projectPrevBtn').removeClass('opacity-100 visible').addClass('opacity-0 invisible');
            } else {
                $('#projectPrevBtn').removeClass('opacity-0 invisible').addClass('opacity-100 visible');
            }

            if (currentIndex >= maxIndex) {
                $('#projectNextBtn').removeClass('opacity-100 visible').addClass('opacity-0 invisible');
            } else {
                $('#projectNextBtn').removeClass('opacity-0 invisible').addClass('opacity-100 visible');
            }
        }
    }

    // Next button - CHANGED ID
    $('#projectNextBtn').on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        const visibleCards = getVisibleCards();
        const maxIndex = Math.max(0, totalCards - visibleCards);
        if (currentIndex < maxIndex) {
            currentIndex++;
            updateOuterCarousel();
        }
        return false;
    });

    // Previous button - CHANGED ID
    $('#projectPrevBtn').on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        if (currentIndex > 0) {
            currentIndex--;
            updateOuterCarousel();
        }
        return false;
    });

    // Handle window resize
    let resizeTimer;
    $(window).resize(function () {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function () {
            updateOuterCarousel();
        }, 150);
    });

    // Initialize
    updateOuterCarousel();


    // ============================================
    // INNER IMAGE CAROUSEL (Inside each card)
    // ============================================

    $('.service-card').each(function () {
        const $card = $(this);
        const $imageCarousel = $card.find('.card-image-carousel');
        const $imageTrack = $card.find('.card-image-track');
        const $slides = $card.find('.card-image-slide');
        const $leftArrow = $card.find('.card-left-arrow');
        const $rightArrow = $card.find('.card-right-arrow');
        const $dots = $card.find('.card-pagination-dot');

        let currentSlide = 0;
        const totalSlides = $slides.length;

        function updateImageCarousel() {
            const translateX = -currentSlide * 100;
            $imageTrack.css('transform', `translateX(${translateX}%)`);

            $dots.removeClass('active');
            $dots.eq(currentSlide).addClass('active');

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

        $rightArrow.on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            if (currentSlide < totalSlides - 1) {
                currentSlide++;
                updateImageCarousel();
            }
            return false;
        });

        $leftArrow.on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            if (currentSlide > 0) {
                currentSlide--;
                updateImageCarousel();
            }
            return false;
        });

        $dots.on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            currentSlide = $(this).data('index');
            updateImageCarousel();
            return false;
        });

        // Touch swipe support
        let touchStartX = 0;
        let touchEndX = 0;
        const minSwipeDistance = 50;

        $imageCarousel.on('touchstart', function (e) {
            touchStartX = e.originalEvent.touches[0].clientX;
        });

        $imageCarousel.on('touchmove', function (e) {
            touchEndX = e.originalEvent.touches[0].clientX;
        });

        $imageCarousel.on('touchend', function () {
            const swipeDistance = touchEndX - touchStartX;

            if (Math.abs(swipeDistance) > minSwipeDistance) {
                if (swipeDistance > 0 && currentSlide > 0) {
                    currentSlide--;
                    updateImageCarousel();
                } else if (swipeDistance < 0 && currentSlide < totalSlides - 1) {
                    currentSlide++;
                    updateImageCarousel();
                }
            }
        });

        updateImageCarousel();
    });


    // ============================================
    // FAVORITE BUTTON
    // ============================================

    $(document).on('click', 'button[aria-label="Add to favorites"]', function (e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).toggleClass('text-red-500');
        const $svg = $(this).find('svg');
        if ($(this).hasClass('text-red-500')) {
            $svg.attr('fill', 'currentColor');
        } else {
            $svg.attr('fill', 'none');
        }
        return false;
    });

});