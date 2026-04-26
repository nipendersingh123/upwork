// Top rated freelancer badge wrap check

function checkWrap() {
    $('[data-wrap-check]').each(function () {
        const $container = $(this);
        const $name = $container.find('.name');
        const $badge = $container.find('.badge');
        const $divider = $container.find('.divider');

        if (!$name.length || !$badge.length || !$divider.length) return;

        const nameTop = $name[0].getBoundingClientRect().top;
        const badgeTop = $badge[0].getBoundingClientRect().top;

        $divider.toggle(Math.abs(nameTop - badgeTop) <= 1);
    });
}

// Run at start + on resize
$(window).on('load resize', checkWrap);




// Select2 Related functions

$(document).ready(function () {
    $('.my-select').each(function () {
        const $select = $(this);

        if ($select.closest('#sidebar').length) {
            // Select2 INSIDE sidebar
            $select.select2({
                width: '100%',
                dropdownParent: $('#sidebar'),


            });

            // Apply CSS to THIS select only
            const select2Data = $select.data('select2');
            // select2Data.$dropdown.find('.select2-results__options').css('padding', '0 16px');
            select2Data.$container.find('.select2-selection--single').css('height', '48px');
        } else {
            // Select2 OUTSIDE sidebar
            $select.select2({
                width: '',
                minimumResultsForSearch: Infinity,
                dropdownCss: {
                    marginTop: '8px', // space when dropdown opens below
                    marginBottom: '8px' // space when dropdown opens above
                }
            });
        }
    });


    // Close dropdown when scrolling the sidebar
    $('#sidebar .w-80').on('scroll', function () {
        $('.my-select, .custom-select').select2('close');
    });
});

$('.my-select').on('select2:select', function (e) {
    const selectedData = e.params.data;
    console.log('Selected:', selectedData);
});

$('.my-select').on('select2:unselect', function (e) {
    const removedData = e.params.data;
    console.log('Removed:', removedData);
});







// Service Card Carousel and Trending Services Carousel

$(document).ready(function () {

    // ============================================
    // FIRST CAROUSEL - SERVICE DETAILS (card-animate)
    // ============================================


    // ============================================
    // SECOND CAROUSEL - TRENDING SERVICES
    // ============================================
    let trendingCurrentIndex = 0;
    const $trendingOuterTrack = $('#trendingCards');
    const $trendingCards = $trendingOuterTrack.find('.service-card');
    const trendingTotalCards = $trendingCards.length;

    function getTrendingVisibleCards() {
        const trendingContainerWidth = $trendingOuterTrack.parent().width();
        let trendingVisibleCards = Math.floor(trendingContainerWidth / 424);
        trendingVisibleCards = Math.max(1, Math.min(3, trendingVisibleCards));
        return trendingVisibleCards;
    }

    function updateTrendingOuterCarousel() {
        const trendingVisibleCards = getTrendingVisibleCards();
        const trendingMaxIndex = Math.max(0, trendingTotalCards - trendingVisibleCards);

        if (trendingCurrentIndex < 0) {
            trendingCurrentIndex = trendingMaxIndex;
        } else if (trendingCurrentIndex > trendingMaxIndex) {
            trendingCurrentIndex = 0;
        }

        const trendingCardWidth = $trendingCards.first().outerWidth(true);
        const trendingTranslateX = trendingCurrentIndex * trendingCardWidth * -1;

        $trendingOuterTrack.css({
            transform: `translateX(${trendingTranslateX}px)`,
            transition: 'transform 0.5s ease-in-out'
        });

        if (trendingVisibleCards >= trendingTotalCards) {
            $('#prevBtn, #nextBtn').removeClass('opacity-100 visible').addClass('opacity-0 invisible');
        } else {
            $('#prevBtn, #nextBtn').removeClass('opacity-0 invisible').addClass('opacity-100 visible');
        }
    }

    // FIXED: Complete isolation with unique event handlers
    $('#nextBtn').off('click mousedown touchstart').on('click mousedown touchstart', function (e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();

        if (e.type !== 'click') {
            const trendingVisibleCards = getTrendingVisibleCards();
            const trendingMaxIndex = Math.max(0, trendingTotalCards - trendingVisibleCards);

            trendingCurrentIndex++;
            if (trendingCurrentIndex > trendingMaxIndex) {
                trendingCurrentIndex = 0;
            }

            updateTrendingOuterCarousel();
        }
        return false;
    });

    $('#prevBtn').off('click mousedown touchstart').on('click mousedown touchstart', function (e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();

        if (e.type !== 'click') {
            const trendingVisibleCards = getTrendingVisibleCards();
            const trendingMaxIndex = Math.max(0, trendingTotalCards - trendingVisibleCards);

            trendingCurrentIndex--;
            if (trendingCurrentIndex < 0) {
                trendingCurrentIndex = trendingMaxIndex;
            }

            updateTrendingOuterCarousel();
        }
        return false;
    });

    let trendingResizeTimer;
    $(window).resize(function () {
        clearTimeout(trendingResizeTimer);
        trendingResizeTimer = setTimeout(function () {
            updateTrendingOuterCarousel();
        }, 150);
    });

    updateTrendingOuterCarousel();

    // ============================================
    // INNER IMAGE CAROUSEL (Inside trending cards)
    // ============================================
    $('.service-card').each(function () {
        const $innerCard = $(this);
        const $innerImageCarousel = $innerCard.find('.card-image-carousel');
        const $innerImageTrack = $innerCard.find('.card-image-track');
        const $innerSlides = $innerCard.find('.card-image-slide');
        const $innerLeftArrow = $innerCard.find('.card-left-arrow');
        const $innerRightArrow = $innerCard.find('.card-right-arrow');
        const $innerDots = $innerCard.find('.card-pagination-dot');

        let innerCurrentSlide = 0;
        const innerTotalSlides = $innerSlides.length;

        function updateInnerImageCarousel() {
            if (innerCurrentSlide < 0) {
                innerCurrentSlide = innerTotalSlides - 1;
            } else if (innerCurrentSlide >= innerTotalSlides) {
                innerCurrentSlide = 0;
            }

            const innerTranslateX = -innerCurrentSlide * 100;
            $innerImageTrack.css('transform', `translateX(${innerTranslateX}%)`);

            $innerDots.removeClass('active');
            $innerDots.eq(innerCurrentSlide).addClass('active');

            $innerLeftArrow.removeClass('hidden');
            $innerRightArrow.removeClass('hidden');
        }

        $innerRightArrow.off('click mousedown touchstart').on('click mousedown touchstart', function (e) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();

            if (e.type !== 'click') {
                innerCurrentSlide++;
                if (innerCurrentSlide >= innerTotalSlides) {
                    innerCurrentSlide = 0;
                }
                updateInnerImageCarousel();
            }
            return false;
        });

        $innerLeftArrow.off('click mousedown touchstart').on('click mousedown touchstart', function (e) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();

            if (e.type !== 'click') {
                innerCurrentSlide--;
                if (innerCurrentSlide < 0) {
                    innerCurrentSlide = innerTotalSlides - 1;
                }
                updateInnerImageCarousel();
            }
            return false;
        });

        $innerDots.off('click').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            innerCurrentSlide = $(this).data('index');
            updateInnerImageCarousel();
            return false;
        });

        let innerTouchStartX = 0;
        let innerTouchEndX = 0;
        const innerMinSwipeDistance = 50;

        $innerImageCarousel.on('touchstart', function (e) {
            innerTouchStartX = e.originalEvent.touches[0].clientX;
        });

        $innerImageCarousel.on('touchmove', function (e) {
            innerTouchEndX = e.originalEvent.touches[0].clientX;
        });

        $innerImageCarousel.on('touchend', function (e) {
            e.preventDefault();
            const innerSwipeDistance = innerTouchEndX - innerTouchStartX;

            if (Math.abs(innerSwipeDistance) > innerMinSwipeDistance) {
                if (innerSwipeDistance > 0) {
                    innerCurrentSlide--;
                    if (innerCurrentSlide < 0) {
                        innerCurrentSlide = innerTotalSlides - 1;
                    }
                    updateInnerImageCarousel();
                } else if (innerSwipeDistance < 0) {
                    innerCurrentSlide++;
                    if (innerCurrentSlide >= innerTotalSlides) {
                        innerCurrentSlide = 0;
                    }
                    updateInnerImageCarousel();
                }
            }
        });

        updateInnerImageCarousel();
    });

    // ============================================
    // FAVORITE BUTTON
    // ============================================
    $(document).off('click', 'button[aria-label="Add to favorites"]').on('click', 'button[aria-label="Add to favorites"]', function (e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).toggleClass('text-red-500');
        const $favoriteSvg = $(this).find('svg');
        if ($(this).hasClass('text-red-500')) {
            $favoriteSvg.attr('fill', 'currentColor');
        } else {
            $favoriteSvg.attr('fill', 'none');
        }
        return false;
    });

});




// code for sidebar



// Sidebar toggle
$('#filter').on('click', function () {
    $('#sidebar').removeClass('hidden transition duration-500');
});

$('#closeSidebar').on('click', function () {
    $('#sidebar').addClass('hidden');
});

// Close sidebar when clicking outside
$('#sidebar').on('click', function (e) {
    if (e.target.id === 'sidebar') {
        $('#sidebar').addClass('hidden');
    }
});




// Price range slider functionality
const priceMin = $('#priceMin');
const priceMax = $('#priceMax');
const priceRangeStart = $('#priceRangeStart');
const priceRangeEnd = $('#priceRangeEnd');

priceRangeStart.on('input', function () {
    let minVal = parseInt($(this).val());
    let maxVal = parseInt(priceRangeEnd.val());

    if (minVal > maxVal) {
        $(this).val(maxVal);
        minVal = maxVal;
    }

    priceMin.val('$' + minVal);
});

priceRangeEnd.on('input', function () {
    let minVal = parseInt(priceRangeStart.val());
    let maxVal = parseInt($(this).val());

    if (maxVal < minVal) {
        $(this).val(minVal);
        maxVal = minVal;
    }

    priceMax.val('$' + maxVal);
});



// Update sliders when input values change
priceMin.on('change', function () {
    let value = parseInt($(this).val().replace('$', ''));
    if (!isNaN(value)) {
        priceRangeStart.val(Math.max(0, Math.min(value, parseInt(priceRangeEnd.val()))));
    }
});

priceMax.on('change', function () {
    let value = parseInt($(this).val().replace('$', ''));
    if (!isNaN(value)) {
        priceRangeEnd.val(Math.min(1000, Math.max(value, parseInt(priceRangeStart.val()))));
    }
});



// Show More / Show Less functionality
let isExpanded = false;

$('#showMoreBtn').on('click', function (e) {
    e.preventDefault();
    isExpanded = !isExpanded;

    if (isExpanded) {
        $('#extraOptions').removeClass('hidden');
        $(this).html('<span>−</span> Show Less');
    } else {
        $('#extraOptions').addClass('hidden');
        $(this).html('<span>+</span> Show More');
    }
});




