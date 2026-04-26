<!-- footer area start -->
<footer id="footer" class="text-base-300 relative bg-cover bg-center bg-no-repeat">
    <!-- Main Footer Content -->
    <div class="max-w-7xl mx-auto px-6 py-16 relative">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
            {!! render_frontend_sidebar('footer_four') !!}
        </div>
    </div>
    <!-- Footer Bottom -->
    <div class="border-t border-gray-200  relative">
        <div class="max-w-7xl mx-auto px-6 py-6">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex items-center">
                    <img src="{{ asset('assets/frontend/new_design/assets/images/logo.svg') }}" alt="Logo">
                </div>
                <span class="text-gray-400 text-sm">{!! render_footer_copyright_text() !!}</span>
                <!-- Social Icons -->
                <div class="flex space-x-3">
                    <a href="#" class="w-6 h-6 bg-primary hover:bg-secondary hover:text-base-100 transition-colors duration-300 rounded flex items-center justify-center">
                        <img class="text-white" src="{{ asset('assets/frontend/new_design/assets/images/footer/white-footer/facebook.svg') }}" alt="Facebook">
                    </a>
                    <a href="#" class="w-6 h-6 bg-primary hover:bg-secondary hover:text-base-100 transition-colors duration-300 rounded flex items-center justify-center">
                        <img class="text-white" src="{{ asset('assets/frontend/new_design/assets/images/footer/white-footer/dribble.svg') }}" alt="Dribbble">
                    </a>
                    <a href="#" class="w-6 h-6 bg-primary hover:bg-secondary hover:text-base-100 transition-colors duration-300 rounded flex items-center justify-center">
                        <img class="text-white" src="{{ asset('assets/frontend/new_design/assets/images/footer/white-footer/twitter.svg') }}" alt="Twitter">
                    </a>
                    <a href="#" class="w-6 h-6 bg-primary hover:bg-secondary hover:text-base-100 transition-colors duration-300 rounded flex items-center justify-center">
                        <img class="text-white" src="{{ asset('assets/frontend/new_design/assets/images/footer/white-footer/linkedin.svg') }}" alt="LinkedIn">
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
    /* Make footer li text visible */
    #footer li,
    #footer li a {
        color: #414E62 !important;
    }

    /* Make footer li links visible on hover */
    #footer li a:hover {
        color: var(--color-primary, #10b981) !important;
    }
</style>
<!-- footer area end -->