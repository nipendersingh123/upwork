// Video Play Button Functionality
$(document).ready(function () {
    const $videoPlayer = $('#videoPlayer');
    const $playButton = $('#playButton');

    // Play button click event
    $playButton.on('click', function (e) {
        e.preventDefault();
        $videoPlayer[0].play();
        $playButton.hide();
    });

    // Hide play button when video plays
    $videoPlayer.on('play', function () {
        $playButton.hide();
    });

    // Show play button when video pauses
    $videoPlayer.on('pause', function () {
        $playButton.show();
    });

    // Show play button when video ends
    $videoPlayer.on('ended', function () {
        $playButton.show();
    });
});

// Team Carousel Functionality
$(document).ready(function () {
    let currentIndex = 0;
    const $carouselTrack = $('#carouselTrack');
    const $items = $carouselTrack.find('> div');
    const itemCount = $items.length;
    let itemsToShow = 4;

    // Function to update items to show based on screen size
    function updateItemsToShow() {
        if ($(window).width() < 768) {
            itemsToShow = 1;
        } else if ($(window).width() < 1024) {
            itemsToShow = 2;
        } else {
            itemsToShow = 4;
        }
    }

    // Function to move carousel
    function moveCarousel() {
        const offset = -(currentIndex * 100 / itemsToShow);
        $carouselTrack.css('transform', `translateX(${offset}%)`);
    }

    // Next button click
    $('#teamNextBtn').on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();

        if (currentIndex < itemCount - itemsToShow) {
            currentIndex++;
        } else {
            currentIndex = 0;
        }
        moveCarousel();
        return false;
    });

    // Previous button click
    $('#teamPrevBtn').on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();

        if (currentIndex > 0) {
            currentIndex--;
        } else {
            currentIndex = itemCount - itemsToShow;
        }
        moveCarousel();
        return false;
    });

    // Handle window resize
    $(window).on('resize', function () {
        updateItemsToShow();
        moveCarousel();
    });

    // Initialize
    updateItemsToShow();
    moveCarousel();
});