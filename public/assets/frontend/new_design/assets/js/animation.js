// Banner animation using GSAP
$(document).ready(function () {

    // Banner animation
    // Animate Left Content
    gsap.from(".left-content", {
        x: -50,       // slide from left
        opacity: 0,
        duration: 1,
        ease: "power2.out",
        scrollTrigger: {
            trigger: ".left-content",
            start: "top 80%",
        }
    });

    // Animate Right Image
    gsap.from(".right-image", {
        x: 50,        // slide from right
        opacity: 0,
        duration: 1,
        ease: "power2.out",
        scrollTrigger: {
            trigger: ".right-image",
            start: "top 80%",
        }
    });

    // Animate small info cards
    gsap.from(".info-card", {
        y: 20,
        opacity: 0,
        duration: 0.8,
        stagger: 0.2,
        ease: "power2.out",
        scrollTrigger: {
            trigger: ".info-card",
            start: "top 90%",
        }
    });


});



// For all heading and Buttons

$(document).ready(function () {
    gsap.registerPlugin(ScrollTrigger);

    // Animate all elements with the class .animate-on-scroll
    gsap.utils.toArray(".animate-on-scroll").forEach(function (elem) {
        gsap.from(elem, {
            opacity: 0,
            y: 50,            // move up from 50px below
            duration: 0.8,
            ease: "power2.out",
            scrollTrigger: {
                trigger: elem,
                start: "top 85%",   // trigger when element is 85% from top of viewport
                toggleActions: "play none none none"
            }
        });
    });


    // Animate all cards with .card-animate class
    gsap.utils.toArray(".card-animate").forEach(function (card) {
        gsap.from(card, {
            opacity: 0,
            y: 30,            // move up from 30px below
            scale: 0.95,      // slight pop effect
            duration: 0.8,
            ease: "power2.out",
            scrollTrigger: {
                trigger: card,
                start: "top 90%",    // trigger when element is near viewport
                toggleActions: "play none none none"
            }
        });
    });
});