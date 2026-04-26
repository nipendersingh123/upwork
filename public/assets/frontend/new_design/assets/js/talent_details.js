$(document).ready(function () {
            $('#more-about-btn').click(function () {
                $('#hidden-sections').removeClass('hidden');
                $('#more-about-btn').addClass('hidden');
                $('#hide-about-btn').removeClass('hidden');
                // Optional: Scroll to the revealed section more-about-btn
                $('html, body').animate({
                    scrollTop: $("#hidden-sections").offset().top - 100
                }, 500);
            });          



            // Portfolio Image Change
            $('.portfolio-thumb').on('click', function () {
                let img = $(this).find('img').attr('src');
                $('#portfolio-main-img').attr('src', img);

                // Update active state
                $('.portfolio-thumb').removeClass('border-2 border-primary').addClass('border border-gray-200 hover:border-primary');
                $(this).removeClass('border border-gray-200 hover:border-primary').addClass('border-2 border-primary');
            });
        });

// Initialize project image carousels for profile page
function initializeProfileProjectCarousels() {
    const projectCards = document.querySelectorAll('.project_wrapper_area .card-animate');

    projectCards.forEach((card) => {
        const track = card.querySelector('.carousel-track');
        const slides = card.querySelectorAll('.carousel-slide');
        const leftArrow = card.querySelector('.left-arrow');
        const rightArrow = card.querySelector('.right-arrow');
        const dots = card.querySelectorAll('.pagination-dot');

        if (!track || slides.length === 0) return;

        let currentIndex = 0;
        const totalSlides = slides.length;

        // Only enable carousel if multiple images
        if (totalSlides === 1) {
            return; // Single image, no carousel needed
        }

        function updateCarousel() {
            const offset = -currentIndex * 100;
            track.style.transform = `translateX(${offset}%)`;

            // Update dots
            if (dots) {
                dots.forEach((dot, index) => {
                    if (index === currentIndex) {
                        dot.classList.add('active', 'bg-white', 'w-3');
                        dot.classList.remove('bg-white/60');
                    } else {
                        dot.classList.remove('active', 'bg-white', 'w-3');
                        dot.classList.add('bg-white/60');
                    }
                });
            }

            // Update arrow visibility
            if (leftArrow) {
                if (currentIndex === 0) {
                    leftArrow.classList.add('hidden');
                } else {
                    leftArrow.classList.remove('hidden');
                }
            }
            if (rightArrow) {
                if (currentIndex === totalSlides - 1) {
                    rightArrow.classList.add('hidden');
                } else {
                    rightArrow.classList.remove('hidden');
                }
            }
        }

        // Left arrow click
        if (leftArrow) {
            leftArrow.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                if (currentIndex > 0) {
                    currentIndex--;
                    updateCarousel();
                }
            });
        }

        // Right arrow click
        if (rightArrow) {
            rightArrow.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                if (currentIndex < totalSlides - 1) {
                    currentIndex++;
                    updateCarousel();
                }
            });
        }

        // Dot click
        if (dots) {
            dots.forEach((dot) => {
                dot.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    currentIndex = parseInt(dot.getAttribute('data-index'));
                    updateCarousel();
                });
            });
        }

        // Initialize
        updateCarousel();
    });
}

// Call this function on page load
$(document).ready(function() {
    // ... your existing code ...

    // Initialize carousels
    initializeProfileProjectCarousels();

    // Also initialize when portfolio thumbs change (in case of AJAX)
    $(document).on('click', '.portfolio-thumb', function() {
        setTimeout(initializeProfileProjectCarousels, 100);
    });
});