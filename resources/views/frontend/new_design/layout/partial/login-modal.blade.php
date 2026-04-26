<!-- Login Modal -->
<div id="loginModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[1001] hidden items-center justify-center p-4">
    <div class="w-full max-w-7xl bg-white rounded-2xl shadow-xl overflow-hidden max-h-[90vh] overflow-y-auto">
        <div class="grid grid-cols-1 lg:grid-cols-2">

            <!-- Left Side - Image with Overlay -->
            <div class="relative min-h-[300px] hidden md:block lg:min-h-[700px] bg-cover bg-center overflow-hidden"
                 style="background-image: url('{{ asset('assets/frontend/new_design/assets/images/login-page/right-side.png') }}');">
                <div class="relative z-10 h-full flex flex-col justify-end p-8 text-white">
                    <div class="space-y-4 bg-[#767272]/40 backdrop-blur-xl p-6 rounded-2xl">
                        <h2 class="text-xl lg:text-[1.75rem] font-medium mb-6 leading-[36px]">
                            {{ get_static_option('login_page_sidebar_title') ?? __('Find the perfect freelance services for your business.') }}
                        </h2>
                        <div class="flex items-center gap-3">
                            <i class="fa-regular fa-circle-check text-secondary text-xl"></i>
                            <p class="text-gray-200 text-lg">{{ __('Discover talented freelancers from around the globe.') }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fa-regular fa-circle-check text-secondary text-xl"></i>
                            <p class="text-gray-200">{{ __('Get quality work done quickly and budget.') }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fa-regular fa-circle-check text-secondary text-xl"></i>
                            <p class="text-gray-200">{{ __('Manage your projects with our easy-to-use platform.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Login Form -->
            <div class="p-8 lg:p-12 flex flex-col justify-center relative">
                <!-- Close Button -->
                <button type="button" id="closeLoginModal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-2xl">
                    <i class="fas fa-times"></i>
                </button>

                <div class="max-w-lg mx-auto w-full">
                    <h1 class="text-xl lg:text-[1.75rem] font-semibold mb-3">
                        {{ get_static_option('login_page_title') ?? __('Login to Your Account') }}
                    </h1>
                    <p class="text-base-300 text-base mb-8">
                        {{ __("Don't have an account?") }}
                        <a href="#" id="openRegisterModal" class="text-primary text-base font-medium hover:text-teal-700">
                            {{ __('SignUp Now') }}
                        </a>
                    </p>

                    <x-validation.error />
                    <div class="error-message"></div>

                    <!-- Social Login Buttons -->
                    @if (get_static_option('login_page_social_login_enable_disable') == 'on')
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
                            <button type="button" data-provider="google" class="social-login-btn flex items-center justify-start gap-2 px-4 py-[0.625rem] border-2 border-[#C4C8CE] rounded-lg hover:bg-gray-50 transition font-medium text-base-300">
                                <img src="{{ asset('assets/frontend/new_design/assets/images/login-page/google.svg') }}" alt="google" class="w-5 h-5">
                                {{ __('Google') }}
                            </button>
                            @if (!moduleExists('CurrencySwitcher'))
                                <button type="button" data-provider="facebook" class="social-login-btn flex items-center justify-start gap-2 px-4 py-[0.625rem] border-2 border-[#C4C8CE] rounded-lg hover:bg-gray-50 transition text-base-300 font-medium">
                                    <i class="fa-brands fa-facebook text-[#1877F2] text-xl"></i>
                                    {{ __('Facebook') }}
                                </button>
                            @endif
                        </div>

                        <!-- Divider -->
                        <div class="relative mb-6">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-300"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-4 bg-white text-base-400">{{ __('or continue with email') }}</span>
                            </div>
                        </div>
                    @endif

                    <!-- Login Form -->
                    <form id="modalLoginForm" class="space-y-5">
                        @csrf
                        <div>
                            <label for="modal_username" class="block text-base font-medium text-base-300 mb-2">
                                {{ __('Email Or User Name') }}
                            </label>
                            <input type="text" id="modal_username" name="username" placeholder="{{ __('Email Or User Name') }}"
                                   class="w-full px-4 py-3 border border-[#C4C8CE] rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none transition">
                        </div>

                        <div>
                            <label for="modal_password" class="block text-base font-medium text-base-300 mb-2">
                                {{ __('Password') }}
                            </label>
                            <div class="relative">
                                <input type="password" id="modal_password" name="password" placeholder="{{ __('Type password') }}"
                                       class="w-full px-4 py-3 border border-[#C4C8CE] rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none transition pr-12">
                                <button type="button" id="modalTogglePassword"
                                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                    <i class="fas fa-eye-slash"></i>
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center justify-between flex-wrap gap-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="modal_remember" name="remember"
                                       class="w-4 h-4 accent-primary border-gray-300 rounded focus:ring-green-600">
                                <span class="text-sm font-normal text-[#414E62]">{{ __('Remember Me') }}</span>
                            </label>
                            <a href="{{ route('user.forgot.password') }}" class="text-sm text-primary font-medium hover:text-teal-700 whitespace-nowrap">
                                {{ __('Forgot Password?') }}
                            </a>
                        </div>

                        <button type="submit" id="modalLoginBtn"
                                class="w-full bg-primary hover:bg-teal-700 text-white font-semibold py-3 rounded-lg transition">
                            {{ get_static_option('login_page_button_title') ?? __('Log In') }}
                        </button>
                    </form>

                    <!-- Demo Credentials Table -->
                    @if (is_test_domain())
                        <div class="mt-8 border border-gray-200 rounded-lg overflow-hidden">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">{{ __('Username') }}</th>
                                    <th class="px-4 py-3 font-semibold text-gray-700 text-center">{{ __('Password') }}</th>
                                    <th class="px-4 py-3 text-end font-semibold text-gray-700">{{ __('Action') }}</th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                <tr>
                                    <td class="px-4 py-3 text-base-300" data-username="freelancer">freelancer</td>
                                    <td class="px-4 py-3 text-base-300 text-center" data-password="12345678">12345678</td>
                                    <td class="px-4 py-3 text-end">
                                        <button type="button" class="demo-login-btn bg-primary hover:bg-teal-700 text-white text-xs font-medium px-4 py-2 rounded transition"
                                                data-username="freelancer" data-password="12345678">
                                            {{ __('Freelancer Login') }}
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3 text-base-300" data-username="client">client</td>
                                    <td class="px-4 py-3 text-base-300 text-center" data-password="12345678">12345678</td>
                                    <td class="px-4 py-3 text-end">
                                        <button type="button" class="demo-login-btn bg-primary hover:bg-teal-700 text-white text-xs font-medium px-4 py-2 rounded transition"
                                                data-username="client" data-password="12345678">
                                            {{ __('Client Login') }}
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Social Login Role Selection Modal (Updated to match your design) -->
<div id="socialRoleModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[1001] hidden items-center justify-center p-4">
    <div class="relative w-full max-w-5xl z-30 bg-white rounded-3xl overflow-hidden max-h-[90vh] overflow-y-auto">
        <!-- Close Button -->
        <button type="button" id="closeSocialRoleModal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-2xl z-40">
            <i class="fas fa-times"></i>
        </button>

        <div class="bg-[url('{{ asset('assets/frontend/new_design/assets/images/login-page/loginpage.png') }}')] bg-cover bg-center bg-no-repeat min-h-[400px] flex items-center justify-center p-4 relative">
            <!-- overlay (disable pointer events) -->
            <div class="absolute inset-0 bg-black/30 backdrop-blur-md pointer-events-none"></div>
            <!-- card -->
            <div class="relative w-full z-30 bg-white rounded-3xl">
                <div class="max-w-4xl m-auto p-8 md:p-12 lg:p-[4.5rem]">
                    <h1 class="text-2xl sm:text-3xl md:text-[28px] font-medium text-base-300 text-center mb-8 sm:mb-12">
                        {{ get_static_option('register_page_choose_role_title') ?? __('Join as a freelancer or client') }}
                    </h1>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 mb-8">
                        <div id="freelancerCard"
                             class="border-2 border-gray-300 bg-white rounded-2xl p-6 sm:p-8 md:p-10 cursor-pointer transition-all hover:shadow-lg select-social-role"
                             tabindex="0" role="button" aria-pressed="false" data-role="2">
                            <div class="flex flex-col items-center text-center">
                                <img src="{{ asset('assets/frontend/new_design/assets/images/login-page/user.svg') }}" alt="Freelancer" class="w-16 h-16 md:w-20 md:h-20" />
                                <h2 class="text-lg sm:text-xl md:text-xl font-medium text-base-300 mt-4 md:mt-6">
                                    {{ __('Join as a Freelancer') }}
                                </h2>
                            </div>
                        </div>
                        <div id="clientCard"
                             class="border-2 border-gray-300 bg-white rounded-2xl p-6 sm:p-8 md:p-10 cursor-pointer transition-all hover:shadow-lg select-social-role"
                             tabindex="0" role="button" aria-pressed="false" data-role="1">
                            <div class="flex flex-col items-center text-center">
                                <img src="{{ asset('assets/frontend/new_design/assets/images/login-page/client.svg') }}" alt="Client" class="w-16 h-16 md:w-20 md:h-20" />
                                <h2 class="text-lg sm:text-xl md:text-xl font-medium text-base-300 mt-4 md:mt-6">
                                    {{ __('Join as a Client') }}
                                </h2>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col items-center">
                        <button id="confirmSocialRole"
                                class="bg-primary hover:bg-green-700 text-white font-medium px-8 py-3 rounded-lg transition-all w-full md:w-auto">
                            {{ __('Continue') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function($) {
        "use strict";
        $(document).ready(function() {
            let selectedSocialProvider = null;
            let selectedUserRole = null;

            // Open login modal when Sign Up button is clicked (Desktop)
            $('#openLoginModal').on('click', function(e) {
                e.preventDefault();
                $('#loginModal').removeClass('hidden').addClass('flex');
                $('body').addClass('overflow-hidden');
            });

         // Open login modal when Sign Up button is clicked (Mobile)
            $('#openLoginModalMobile').on('click', function(e) {
                e.preventDefault();
                $('#loginModal').removeClass('hidden').addClass('flex');
                $('body').addClass('overflow-hidden');
            });

             // Also keep the existing handler for any other login links (as fallback)
            $('a[href="{{ route('user.login') }}"]').not('#openLoginModal, #openLoginModalMobile').on('click', function(e) {
                e.preventDefault();
                $('#loginModal').removeClass('hidden').addClass('flex');
                $('body').addClass('overflow-hidden');
            });

            // Close login modal
            $('#closeLoginModal').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $('#loginModal').addClass('hidden').removeClass('flex');
                $('body').removeClass('overflow-hidden');
            });

            // Close login modal when clicking on backdrop
            $('#loginModal').on('click', function(e) {
                if (e.target === this) {
                    $('#loginModal').addClass('hidden').removeClass('flex');
                    $('body').removeClass('overflow-hidden');
                }
            });

            // Close social role modal and go back to login modal
            $('#closeSocialRoleModal').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                // Hide social role modal
                $('#socialRoleModal').addClass('hidden').removeClass('flex');
                // Show login modal again
                $('#loginModal').removeClass('hidden').addClass('flex');
                // Keep body overflow-hidden (login modal is still open)
            });

               // Close social role modal when clicking on backdrop and go back to login modal
            $('#socialRoleModal').on('click', function(e) {
                if (e.target === this) {
                    // Hide social role modal
                    $('#socialRoleModal').addClass('hidden').removeClass('flex');
                    // Show login modal again
                    $('#loginModal').removeClass('hidden').addClass('flex');
                    // Keep body overflow-hidden (login modal is still open)
                }
            });

            // Show login modal from social modal
            $('#showLoginFromSocialModal').on('click', function(e) {
                e.preventDefault();
                $('#socialRoleModal').addClass('hidden').removeClass('flex');
                $('#loginModal').removeClass('hidden').addClass('flex');
            });

            // Toggle password visibility
            $('#modalTogglePassword').on('click', function() {
                const passwordInput = $('#modal_password');
                const icon = $(this).find('i');

                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                } else {
                    passwordInput.attr('type', 'password');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                }
            });

            // Demo login
            $('.demo-login-btn').on('click', function() {
                const username = $(this).data('username');
                const password = $(this).data('password');
                $('#modal_username').val(username);
                $('#modal_password').val(password);
                $('#modalLoginBtn').trigger('click');
            });

            // Modal login form submission
            $('#modalLoginForm').on('submit', function(e) {
                e.preventDefault();
                let btn = $('#modalLoginBtn');
                let erContainer = $(".error-message");
                erContainer.html('');
                btn.text('{{ __('Please Wait..') }}');

                const urlParams = new URLSearchParams(window.location.search);
                const redirectParam = urlParams.get('redirect');

                $.ajax({
                    url: "{{ route('user.login') }}",
                    type: "POST",
                    data: {
                        _token: '{{ csrf_token() }}',
                        username: $('#modal_username').val(),
                        password: $('#modal_password').val(),
                        remember: $('#modal_remember').is(':checked') ? 1 : 0,
                        redirect: redirectParam,
                    },
                    // In error handler:
                    error: function(data) {
                        var errors = data.responseJSON;
                        erContainer.html('<div class="alert alert-danger bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-4"></div>');
                        $.each(errors.errors, function(index, value) {
                            erContainer.find('.alert.alert-danger').append('<p class="text-sm mt-1">' + value + '</p>');
                        });
                        btn.text('{{ __('Log In') }}');
                    },

                   // In success handler:
                    success: function(data) {
                        $('.alert.alert-danger').remove();
                        if (data.status == 'client-login' || data.status == 'freelancer-login') {
                            btn.text('{{ __('Success!') }}');
                            erContainer.html('<div class="alert alert-success bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-4">' + data.msg + '</div>');

                            // Close the modal and reload the page after a short delay
                            setTimeout(function() {
                                $('#loginModal').addClass('hidden').removeClass('flex');
                                $('body').removeClass('overflow-hidden');
                                window.location.reload(); // Reload to show logged-in state
                            }, 1500);

                        } else {
                            // For other non-success messages (like account not verified, etc.)
                            erContainer.html('<div class="alert alert-danger bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-4">' + data.msg + '</div>');
                            btn.text('{{ __('Log In') }}');
                        }
                    }
                });
            });

            // Social login button click
            $('.social-login-btn').on('click', function(e) {
                e.preventDefault();
                selectedSocialProvider = $(this).data('provider');
                selectedUserRole = null;

                // Reset card selection
                $('#freelancerCard, #clientCard').removeClass('border-primary border-2').addClass('border-gray-300 border-2');
                $('#freelancerCard, #clientCard').attr('aria-pressed', 'false');

                // Show social role modal
                $('#loginModal').addClass('hidden').removeClass('flex');
                $('#socialRoleModal').removeClass('hidden').addClass('flex');
                $('body').addClass('overflow-hidden');
            });

            // Role selection - card click
            $(document).on('click', '.select-social-role', function() {
                $('.select-social-role').removeClass('border-primary border-2').addClass('border-gray-300 border-2');
                $('.select-social-role').attr('aria-pressed', 'false');

                $(this).removeClass('border-gray-300 border-2').addClass('border-primary border-2');
                $(this).attr('aria-pressed', 'true');

                selectedUserRole = $(this).data('role');

                // Update button text based on selection
                if (selectedUserRole == 2) {
                    $('#confirmSocialRole').text('{{ __('Join as a Freelancer') }}');
                } else {
                    $('#confirmSocialRole').text('{{ __('Join as a Client') }}');
                }
            });

            // Role selection - Enter key on cards
            $(document).on('keypress', '.select-social-role', function(e) {
                if (e.which === 13) { // Enter key
                    $(this).trigger('click');
                }
            });

            // Confirm social role and redirect
            $('#confirmSocialRole').on('click', function() {
                if (!selectedUserRole) {
                    alert("{{ __('Please select account type to continue') }}");
                    return;
                }

                let redirectUrl = '';
                if (selectedSocialProvider === 'google') {
                    redirectUrl = '{{ route('login.google.redirect') }}' + '?user_type=' + selectedUserRole;
                } else if (selectedSocialProvider === 'facebook') {
                    redirectUrl = '{{ route('login.facebook.redirect') }}' + '?user_type=' + selectedUserRole;
                }

                if (redirectUrl) {
                    $('#socialRoleModal').addClass('hidden').removeClass('flex');
                    $('body').removeClass('overflow-hidden');
                    window.location.href = redirectUrl;
                }
            });

            // Initialize card selection on page load
            $('.select-social-role').on('click', function() {
                // Remove active class from all cards
                $('.select-social-role').removeClass('active border-primary').addClass('border-gray-300');
                // Add active class to clicked card
                $(this).addClass('active border-primary').removeClass('border-gray-300');
                selectedUserRole = $(this).data('role');
            });
        });
    }(jQuery));
</script>