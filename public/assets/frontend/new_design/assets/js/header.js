$(document).ready(function () {
    // Open mobile menu
    $("#mobileMenuBtn").click(function () {
        $("#mobileMenuArea").removeClass("hidden");
        $("#mobileMenuDropdown").removeClass("-translate-x-full").addClass("-translate-x-0 t ");
    });

    // Close menu on overlay or close button
    $("#mobileMenuOverlay, #closeMobileMenu").click(function () {
        $("#mobileMenuDropdown").removeClass("-translate-x-0").addClass("-translate-x-full");
        setTimeout(() => { $("#mobileMenuArea").addClass("hidden"); }, 500);
    });

    // Add background on scroll
    $(window).scroll(function () {
        if ($(this).scrollTop() > 10) {
            $("#mainNav").addClass("bg-white/70 backdrop-blur-md shadow-md");
            $(".nav-link").removeClass("text-white hover:text-secondary").addClass("text-gray-700 hover:text-primary");
            $(".white-logo").addClass("hidden");
            $(".black-logo").removeClass("hidden");
            $(".mobileMenuBtn").removeClass("text-white");
            $(".community").removeClass("text-white border-[#C4C8CE]").addClass("text-base-300 border-primary");
            $(".signup").removeClass("bg-white text-base-300").addClass("bg-primary text-white");


        } else {
            $("#mainNav").removeClass("bg-white/70 backdrop-blur-md shadow-md");
            $(".nav-link").removeClass("text-gray-700 hover:text-primary").addClass("text-white hover:text-secondary");
            $(".white-logo").removeClass("hidden");
            $(".black-logo").addClass("hidden");
            $(".mobileMenuBtn").addClass("text-white");
            $(".community").removeClass("text-base-300 border-primary").addClass("text-white border-[#C4C8CE]");
            $(".signup").removeClass("bg-primary text-white").addClass("bg-white text-base-300");
        }
    });
});