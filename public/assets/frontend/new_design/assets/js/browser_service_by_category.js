$(document).ready(function () {
    let browseCurrentIndex = 0;
    const $browseTrack = $('#browseCategoriesTrack');
    const $browseCards = $('.browse-service-card');
    const browseTotalCards = $browseCards.length;
    const browseCardMinWidth = 250; // Minimum card width
    const gap = 24;

    // Calculate how many cards can fit completely in the container
    function getBrowseVisibleCards() {
        const containerWidth = $('.browse-carousel-container').width();
        const cardWithGap = browseCardMinWidth + gap;

        // Calculate how many complete cards fit
        let visibleCards = Math.floor(containerWidth / cardWithGap);

        // Max 4 cards on large devices, min 1 card on mobile
        visibleCards = Math.max(1, Math.min(4, visibleCards));

        return visibleCards;
    }

    // Set card widths dynamically
    function setBrowseCardWidths() {
        const visibleCards = getBrowseVisibleCards();
        const containerWidth = $('.browse-carousel-container').width();

        // Calculate optimal card width to fit exactly
        const totalGaps = (visibleCards - 1) * gap;
        const availableWidth = containerWidth - totalGaps;
        const optimalCardWidth = Math.floor(availableWidth / visibleCards);

        $browseCards.css('width', optimalCardWidth + 'px');

        return optimalCardWidth;
    }

    function updateBrowseCarousel() {
        const actualCardWidth = setBrowseCardWidths();
        const visibleCards = getBrowseVisibleCards();
        const maxIndex = Math.max(0, browseTotalCards - visibleCards);

        // Keep browseCurrentIndex within bounds
        browseCurrentIndex = Math.max(0, Math.min(browseCurrentIndex, maxIndex));

        // Calculate translation
        const translateX = browseCurrentIndex * (actualCardWidth + gap) * -1;

        $browseTrack.css({
            transform: `translateX(${translateX}px)`,
            transition: 'transform 0.5s cubic-bezier(0.4, 0, 0.2, 1)'
        });

        // Update button visibility with smooth transitions
        if (visibleCards >= browseTotalCards) {
            $('#prevBrowseBtn, #nextBrowseBtn').removeClass('opacity-100 visible').addClass('opacity-0 invisible');
        } else {
            if (browseCurrentIndex === 0) {
                $('#prevBrowseBtn').removeClass('opacity-100 visible').addClass('opacity-0 invisible');
            } else {
                $('#prevBrowseBtn').removeClass('opacity-0 invisible').addClass('opacity-100 visible');
            }

            if (browseCurrentIndex >= maxIndex) {
                $('#nextBrowseBtn').removeClass('opacity-100 visible').addClass('opacity-0 invisible');
            } else {
                $('#nextBrowseBtn').removeClass('opacity-0 invisible').addClass('opacity-100 visible');
            }
        }
    }

    // Navigation button handlers
    $('#nextBrowseBtn').click(function () {
        const visibleCards = getBrowseVisibleCards();
        const maxIndex = Math.max(0, browseTotalCards - visibleCards);
        if (browseCurrentIndex < maxIndex) {
            browseCurrentIndex++;
            updateBrowseCarousel();
        }
    });

    $('#prevBrowseBtn').click(function () {
        if (browseCurrentIndex > 0) {
            browseCurrentIndex--;
            updateBrowseCarousel();
        }
    });

    // Keyboard navigation
    $(document).keydown(function (e) {
        if (e.key === 'ArrowRight') {
            $('#nextBrowseBtn').click();
        } else if (e.key === 'ArrowLeft') {
            $('#prevBrowseBtn').click();
        }
    });

    // Touch swipe support
    let touchStartX = 0;
    let touchEndX = 0;
    const minSwipeDistance = 50;

    $('.browse-carousel-container').on('touchstart', function (e) {
        touchStartX = e.originalEvent.touches[0].clientX;
    });

    $('.browse-carousel-container').on('touchmove', function (e) {
        touchEndX = e.originalEvent.touches[0].clientX;
    });

    $('.browse-carousel-container').on('touchend', function () {
        const swipeDistance = touchEndX - touchStartX;
        const visibleCards = getBrowseVisibleCards();
        const maxIndex = Math.max(0, browseTotalCards - visibleCards);

        if (Math.abs(swipeDistance) > minSwipeDistance) {
            if (swipeDistance > 0 && browseCurrentIndex > 0) {
                browseCurrentIndex--;
                updateBrowseCarousel();
            } else if (swipeDistance < 0 && browseCurrentIndex < maxIndex) {
                browseCurrentIndex++;
                updateBrowseCarousel();
            }
        }
    });

    // Debounced resize handler
    let resizeTimer;
    $(window).resize(function () {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function () {
            updateBrowseCarousel();
        }, 150);
    });

    // Initial setup
    updateBrowseCarousel();
});