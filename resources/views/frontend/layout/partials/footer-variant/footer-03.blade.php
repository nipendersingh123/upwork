<!-- footer area start -->
<footer id="footer" class="bg-[url('{{ asset('assets/frontend/new_design/assets/images/footer/footer.png') }}')] text-white relative bg-cover bg-center bg-no-repeat">
    <div class="absolute inset-0 bg-black/95 z-0"></div>

    <!-- Main Footer Content -->
    <div class="max-w-7xl mx-auto px-6 py-16 relative">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">

            {!! render_frontend_sidebar('footer_three') !!}

        </div>
    </div>

    <!-- Footer Bottom -->
    <div class="border-t border-gray-800 relative">
        <div class="max-w-7xl mx-auto px-6 py-6">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">

                <div class="flex items-center">
                    <img src="{{ asset('assets/frontend/new_design/assets/images/white-logo.svg') }}" alt="Logo">
                </div>

                <span class="text-gray-400 text-sm">{!! render_footer_copyright_text() !!}</span>

                <!-- Social Icons -->
                <div class="flex space-x-3">
                    <a href="#" class="w-6 h-6 bg-base-100 hover:bg-gray-400 hover:text-base-100 transition-colors rounded flex items-center justify-center">
                        <img src="{{ asset('assets/frontend/new_design/assets/images/footer/facebook.svg') }}" alt="Facebook">
                    </a>
                    <a href="#" class="w-6 h-6 bg-base-100 hover:bg-gray-400 hover:text-base-100 transition-colors rounded flex items-center justify-center">
                        <img src="{{ asset('assets/frontend/new_design/assets/images/footer/dribble.svg') }}" alt="Dribbble">
                    </a>
                    <a href="#" class="w-6 h-6 bg-base-100 hover:bg-gray-400 hover:text-base-100 transition-colors rounded flex items-center justify-center">
                        <img src="{{ asset('assets/frontend/new_design/assets/images/footer/twitter.svg') }}" alt="Twitter">
                    </a>
                    <a href="#" class="w-6 h-6 bg-base-100 hover:bg-gray-400 hover:text-base-100 transition-colors rounded flex items-center justify-center">
                        <img src="{{ asset('assets/frontend/new_design/assets/images/footer/linkedin.svg') }}" alt="LinkedIn">
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- footer area end -->