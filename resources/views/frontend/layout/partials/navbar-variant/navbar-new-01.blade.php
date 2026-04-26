<header id="home"
        class="bg-gradient-to-br from-[#E2FFF6] via-base-100 to-[#fff5e66e] min-h-screen overflow-visible flex items-center justify-center pt-10">
    <!-- Navigation -->
    <nav id="mainNav"
         class=" border-b border-gray-200 shadow-sm fixed top-0 left-0 right-0 z-[1000] overflow-visible mb-20">
        <div class="container mx-auto max-w-8xl flex items-center justify-between px-6 py-4">
            <!-- Logo -->
            <div>
                <a href="{{ route('homepage') }}" class="logo">
                    @if(!empty(get_static_option('site_logo')))
                        {!! render_image_markup_by_attachment_id(get_static_option('site_logo')) !!}
                    @else
                        <img src="{{ asset('assets/static/img/logo/logo.png') }}" alt="site-logo">
                    @endif
                </a>
            </div>

            <!-- Navigation (Desktop) -->
            <ul class="hidden lg:flex space-x-8 text-gray-700 font-medium items-center">
                {!! render_frontend_menu($primary_menu) !!}
            </ul>

            <!-- Buttons (Desktop) -->
            <div class="hidden lg:flex items-center space-x-3">
                <button
                        class="border border-primary text-base-300 px-4 py-1 rounded-full hover:bg-primary/10 font-medium transition-all duration-300">
                    Community
                </button>
                <button
                        class="bg-primary text-white text-base-100 px-5 py-1 rounded-full hover:bg-secondary transition-all duration-300 font-medium">
                    Sign Up
                </button>
            </div>

            <!-- Mobile Menu Button -->
            <button id="mobileMenuBtn" title="mobile menu"
                    class="lg:hidden block text-gray-600 hover:text-green-600 text-2xl">
                <i class="fa-solid fa-bars"></i>
            </button>

            <!-- Mobile Menu -->
            <div id="mobileMenuArea" class="hidden absolute inset-0 z-[9999]">
                <!-- Overlay -->
                <div id="mobileMenuOverlay"
                     class="fixed inset-0 bg-black/40 backdrop-blur-xs z-[99999] min-h-screen"></div>

                <!-- Dropdown -->
                <div id="mobileMenuDropdown"
                     class="lg:hidden fixed top-0 bottom-0 left-0 w-72 bg-white shadow-xl px-8 py-6 overflow-y-auto z-[999999] transform -translate-x-full  transition-transform duration-300 min-h-screen">
                    <div class="flex items-center justify-end">
                        <!-- Close Button -->
                        <button title="close mobile menu" id="closeMobileMenu"
                                class="text-gray-600 hover:text-red-600 text-2xl mb-6">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <!-- Links -->
                    <ul class="flex flex-col items-start space-y-4 text-gray-700 font-medium">
                        {!! render_frontend_menu($primary_menu) !!}
                    </ul>

                    <!-- Buttons -->
                    <div class="flex flex-col gap-4 items-start mt-6 w-full">
                        <button
                                class="border border-primary text-primary px-4 py-2 rounded-full hover:bg-primary/20 w-full transition">
                            Community
                        </button>
                        <button
                                class="bg-primary text-white text-base px-5 py-2 rounded-full hover:bg-primary/80 w-full transition">
                            Sign Up
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>

