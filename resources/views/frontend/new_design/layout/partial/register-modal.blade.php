<!-- Registration Modal -->
<div id="registerModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[1001] hidden items-center justify-center p-4">
    <div class="relative w-full max-w-5xl z-30 bg-white rounded-3xl overflow-hidden max-h-[90vh] overflow-y-auto">
        <!-- Close Button -->
        <button type="button" id="closeRegisterModal" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600 text-2xl z-40">
            <i class="fas fa-times"></i>
        </button>

        <!-- STEP 1: Role Selection -->
        <div id="registerStep1" class="bg-[url('{{ asset('assets/frontend/new_design/assets/images/login-page/loginpage.png') }}')] bg-cover bg-center bg-no-repeat min-h-[400px] flex items-center justify-center p-4 relative">
            <div class="absolute inset-0 bg-black/30 backdrop-blur-md pointer-events-none"></div>
            <div class="relative w-full z-30 bg-white rounded-3xl">
                <div class="max-w-4xl m-auto p-8 md:p-12 lg:p-[4.5rem]">
                    <h1 class="text-2xl sm:text-3xl md:text-[28px] font-medium text-base-300 text-center mb-8 sm:mb-12">
                        {{ get_static_option('register_page_choose_role_title') ?? __('Join as a freelancer or client') }}
                    </h1>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 mb-8">
                        <div id="registerFreelancerCard"
                             class="border-2 border-gray-300 bg-white rounded-2xl p-6 sm:p-8 md:p-10 cursor-pointer transition-all hover:shadow-lg select-register-role"
                             tabindex="0" role="button" aria-pressed="false" data-role="2">
                            <div class="flex flex-col items-center text-center">
                                <img src="{{ asset('assets/frontend/new_design/assets/images/login-page/user.svg') }}" alt="Freelancer" class="w-16 h-16 md:w-20 md:h-20" />
                                <h2 class="text-lg sm:text-xl md:text-xl font-medium text-base-300 mt-4 md:mt-6">
                                    {{ get_static_option('register_page_choose_join_freelancer_title') ?? __('Join as a Freelancer') }}
                                </h2>
                            </div>
                        </div>
                        <div id="registerClientCard"
                             class="border-2 border-gray-300 bg-white rounded-2xl p-6 sm:p-8 md:p-10 cursor-pointer transition-all hover:shadow-lg select-register-role"
                             tabindex="0" role="button" aria-pressed="false" data-role="1">
                            <div class="flex flex-col items-center text-center">
                                <img src="{{ asset('assets/frontend/new_design/assets/images/login-page/client.svg') }}" alt="Client" class="w-16 h-16 md:w-20 md:h-20" />
                                <h2 class="text-lg sm:text-xl md:text-xl font-medium text-base-300 mt-4 md:mt-6">
                                    {{ get_static_option('register_page_choose_join_client_title') ?? __('Join as a Client') }}
                                </h2>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col items-center">
                        <button id="registerContinueBtn"
                                class="bg-primary hover:bg-green-700 text-white font-medium px-8 py-3 rounded-lg transition-all w-full md:w-auto disabled:opacity-50 disabled:cursor-not-allowed"
                                disabled>
                            {{ __('Continue') }}
                        </button>

                        @if(get_static_option('login_page_social_login_enable_disable') == 'on')
                            <div class="w-full mt-6">
                                <div class="relative mb-4">
                                    <div class="absolute inset-0 flex items-center">
                                        <div class="w-full border-t border-gray-300"></div>
                                    </div>
                                    <div class="relative flex justify-center text-sm">
                                        <span class="px-4 bg-white text-base-400">{{ __('or continue with') }}</span>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                    <button type="button" data-provider="google" class="social-register-btn flex items-center justify-start gap-2 px-4 py-[0.625rem] border-2 border-[#C4C8CE] rounded-lg hover:bg-gray-50 transition font-medium text-base-300">
                                        <img src="{{ asset('assets/frontend/new_design/assets/images/login-page/google.svg') }}" alt="google" class="w-5 h-5">
                                        {{ __('Google') }}
                                    </button>
                                    @if(!moduleExists('CurrencySwitcher'))
                                        <button type="button" data-provider="facebook" class="social-register-btn flex items-center justify-start gap-2 px-4 py-[0.625rem] border-2 border-[#C4C8CE] rounded-lg hover:bg-gray-50 transition text-base-300 font-medium">
                                            <i class="fa-brands fa-facebook text-[#1877F2] text-xl"></i>
                                            {{ __('Facebook') }}
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <p class="text-base-300 text-sm mt-4">
                            {{ __('Already have an account?') }}
                            <a href="#" id="showLoginFromRegisterModal" class="text-primary underline hover:text-teal-700">
                                {{ __('Log In') }}
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- STEP 2: Registration Form -->
        <div id="registerStep2" class="p-8 md:p-12 hidden">
            <div class="max-w-4xl mx-auto">
                <!-- Back Button -->
                <button type="button" id="backToStep1" class="flex items-center gap-2 text-base-300 hover:text-base-400 mb-6">
                    <i class="fas fa-arrow-left"></i>
                    {{ __('Back') }}
                </button>

                <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900 mb-6">{{ __('Sign Up') }}</h1>

                @if(get_static_option('login_page_social_login_enable_disable') == 'on')
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
                        <button type="button" data-provider="google" class="social-register-btn flex items-center justify-start gap-2 px-4 py-3 border-2 border-[#C4C8CE] rounded-lg hover:bg-gray-50 transition font-medium text-base-300">
                            <img src="{{ asset('assets/frontend/new_design/assets/images/login-page/google.svg') }}" alt="google" class="w-5 h-5">
                            {{ __('Google') }}
                        </button>
                        @if(!moduleExists('CurrencySwitcher'))
                            <button type="button" data-provider="facebook" class="social-register-btn flex items-center justify-start gap-2 px-4 py-3 border-2 border-[#C4C8CE] rounded-lg hover:bg-gray-50 transition text-base-300 font-medium">
                                <i class="fa-brands fa-facebook text-[#1877F2] text-xl"></i>
                                {{ __('Facebook') }}
                            </button>
                        @endif
                    </div>

                    <div class="relative mb-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-white text-base-400">{{ __('or continue with email') }}</span>
                        </div>
                    </div>
                @endif

                <div class="error-message mb-4"></div>

                <form id="modalRegisterForm" class="space-y-5">
                    @csrf
                    <input type="hidden" name="user_type" id="modalUserType" value="2">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="register_first_name" class="block text-base font-medium text-base-300 mb-2">
                                {{ __('First Name') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="register_first_name" name="first_name" placeholder="{{ __('Type First Name') }}"
                                   class="w-full px-4 py-3 border border-[#C4C8CE] rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none transition"
                                   required>
                        </div>
                        <div>
                            <label for="register_last_name" class="block text-base font-medium text-base-300 mb-2">
                                {{ __('Last Name') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="register_last_name" name="last_name" placeholder="{{ __('Type Last Name') }}"
                                   class="w-full px-4 py-3 border border-[#C4C8CE] rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none transition"
                                   required>
                        </div>
                    </div>

                    <div>
                        <label for="register_username" class="block text-base font-medium text-base-300 mb-2">
                            {{ __('Username') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="register_username" name="username" placeholder="{{ __('Type Username') }}"
                               class="w-full px-4 py-3 border border-[#C4C8CE] rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none transition"
                               required>
                        <span id="register_user_name_availability" class="text-sm mt-1 block"></span>
                    </div>

                    <div>
                        <label for="register_email" class="block text-base font-medium text-base-300 mb-2">
                            {{ __('Email Address') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="email" id="register_email" name="email" placeholder="{{ __('Type Email') }}"
                               class="w-full px-4 py-3 border border-[#C4C8CE] rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none transition"
                               required>
                        <span id="register_email_availability" class="text-sm mt-1 block"></span>
                    </div>

                    <div>
                        <label for="register_phone" class="block text-base font-medium text-base-300 mb-2">
                            {{ __('Phone Number') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" id="register_phone" name="phone"
                               class="w-full px-4 py-3 border border-[#C4C8CE] rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none transition"
                               required>
                        <span id="register_phone_availability" class="text-sm mt-1 block"></span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="register_password" class="block text-base font-medium text-base-300 mb-2">
                                {{ __('Password') }} <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" id="register_password" name="password" placeholder="{{ __('Type Password') }}"
                                       class="w-full px-4 py-3 border border-[#C4C8CE] rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none transition pr-12"
                                       required minlength="6" autocomplete="new-password">
                                <button type="button" class="modal-toggle-password absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none"
                                        data-target="#register_password">
                                    <i class="fas fa-eye-slash"></i>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label for="register_confirm_password" class="block text-base font-medium text-base-300 mb-2">
                                {{ __('Confirm Password') }} <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" id="register_confirm_password" name="confirm_password" placeholder="{{ __('Confirm Password') }}"
                                       class="w-full px-4 py-3 border border-[#C4C8CE] rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none transition pr-12"
                                       required minlength="6" autocomplete="new-password">
                                <button type="button" class="modal-toggle-password absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none"
                                        data-target="#register_confirm_password">
                                    <i class="fas fa-eye-slash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <span id="register_check_password_match" class="text-sm block"></span>

                    <div class="flex items-start">
                        <input type="checkbox" id="register_terms_condition" name="terms_condition"
                               class="w-4 h-4 mt-0.5 accent-primary border-gray-300 rounded focus:ring-green-600"
                               required>
                        <label for="register_terms_condition" class="ml-2 text-sm text-base-300">
                            {{ __('Accept all') }}
                            <a target="_blank" href="{{ url(get_static_option('toc_page_link') ?? '') }}"
                               class="text-primary hover:text-teal-700">{{ __('Terms and Conditions') }}</a> &amp;
                            <a target="_blank" href="{{ url(get_static_option('privacy_policy_link') ?? '') }}"
                               class="text-primary hover:text-teal-700">{{ __('Privacy Policy') }}</a>
                        </label>
                    </div>

                    @if(!empty(get_static_option('site_google_captcha_enable')))
                        <div class="my-3">
                            <div class="g-recaptcha" id="recaptcha_element_modal" data-sitekey="{{ get_static_option('recaptcha_site_key') ?? '' }}"></div>
                        </div>
                    @endif

                    <button type="submit" id="modalRegisterBtn"
                            class="w-full bg-primary hover:bg-teal-700 text-white font-semibold py-3 rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed pointer-events-auto">
                        {{ get_static_option('register_page_continue_button_title') ?? __('Sign Up Now') }}
                        <span id="modal_user_register_load_spinner"></span>
                    </button>
                </form>
            </div>
        </div>

        <!-- STEP 3: Email Verification -->
        <div id="registerStep3" class="p-8 md:p-12 hidden">
            <div class="max-w-2xl mx-auto">
                <div class="border border-[#C4C8CE] p-6 lg:p-8 rounded-lg">
                    <h1 class="text-2xl sm:text-3xl font-medium text-gray-900 mb-6">{{ __('Verify Your Account') }}</h1>

                    <!-- Alert Banner -->
                    <div id="modalAlertBanner" class="bg-amber-50 rounded-lg p-4 mb-6 flex items-start justify-between">
                        <p class="text-gray-700 text-sm flex-1">
                            {{ __('Please Check email inbox or spam box for verification code') }}
                        </p>
                        <button id="modalCloseAlert"
                                class="ml-4 text-gray-600 rounded-full bg-black p-1 hover:text-gray-900 transition-colors flex-shrink-0"
                                aria-label="{{ __('Close alert') }}">
                            <i class="fas fa-times text-[#ffb14a] text-xs"></i>
                        </button>
                    </div>

                    <!-- Success Message (Hidden by default) -->
                    <div id="modalSuccessMessage" class="bg-green-50 rounded-lg p-4 mb-6 hidden">
                        <p class="text-green-800 text-sm">
                            {{ __('Verification code has been resent to your email') }}
                        </p>
                    </div>

                    <!-- Verification Form -->
                    <form id="modalVerifyForm" novalidate>
                        <label for="modalVerificationCode" class="block text-gray-900 text-sm font-medium mb-2">
                            {{ __('Enter Verification code') }}<span class="text-red-500">*</span>
                        </label>

                        <input type="text"
                               id="modalVerificationCode"
                               name="email_verify_token"
                               placeholder="{{ __('Enter code') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all text-gray-900 mb-6"
                               inputmode="numeric"
                               pattern="[0-9]*"
                               required
                               maxlength="6">

                        <p id="modalCodeError" class="text-red-500 text-sm mt-1 mb-4 hidden" role="alert"></p>

                        <button type="submit"
                                id="modalVerifyBtn"
                                class="bg-emerald-700 text-white font-medium px-6 py-3 rounded-lg hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-all disabled:bg-gray-300 disabled:cursor-not-allowed disabled:hover:bg-gray-300"
                                disabled>
                            {{ __('Verify Account') }}
                        </button>

                        <button type="button"
                                id="modalResendCode"
                                class="mt-4 text-gray-600 text-sm hover:text-gray-900 transition-colors focus:outline-none focus:underline disabled:text-gray-400 disabled:cursor-not-allowed block">
                            {{ __('Resend Code') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Intl-Tel-Input CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@20.0.0/build/css/intlTelInput.css">
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@20.0.0/build/js/intlTelInput.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@20.0.0/build/js/utils.js"></script>

<script>
    (function($) {
        "use strict";
        $(document).ready(function() {
            let selectedRegisterRole = 2; // Default: freelancer
            let modalIti = null; // For phone input
            let registeredUserId = null; // Store user ID for verification

            // ============================================
            // MODAL OPEN/CLOSE
            // ============================================

            // Open registration modal from login modal
            $('#openRegisterModal').on('click', function(e) {
                e.preventDefault();
                $('#loginModal').addClass('hidden').removeClass('flex');
                $('#registerModal').removeClass('hidden').addClass('flex');
                $('body').addClass('overflow-hidden');
                resetRegisterModal();
            });

            // Open registration modal from other places
            $('a[href="{{ route('user.register') }}"]').on('click', function(e) {
                e.preventDefault();
                $('#registerModal').removeClass('hidden').addClass('flex');
                $('body').addClass('overflow-hidden');
                resetRegisterModal();
            });

            // Close registration modal
            $('#closeRegisterModal').on('click', function() {
                $('#registerModal').addClass('hidden').removeClass('flex');
                $('body').removeClass('overflow-hidden');
            });

            // Close when clicking backdrop
            $('#registerModal').on('click', function(e) {
                if (e.target === this) {
                    $('#registerModal').addClass('hidden').removeClass('flex');
                    $('body').removeClass('overflow-hidden');
                }
            });

            // Go back to login from registration
            $('#showLoginFromRegisterModal').on('click', function(e) {
                e.preventDefault();
                $('#registerModal').addClass('hidden').removeClass('flex');
                $('#loginModal').removeClass('hidden').addClass('flex');
            });

            // ============================================
            // STEP 1: ROLE SELECTION
            // ============================================

            // Role selection
            $(document).on('click', '.select-register-role', function() {
                $('.select-register-role').removeClass('border-primary border-2').addClass('border-gray-300 border-2');
                $(this).removeClass('border-gray-300 border-2').addClass('border-primary border-2');

                selectedRegisterRole = $(this).data('role');
                $('#modalUserType').val(selectedRegisterRole);

                // Update button text
                let buttonText = selectedRegisterRole == 2 ?
                    '{{ __("Join as a Freelancer") }}' :
                    '{{ __("Join as a Client") }}';
                $('#registerContinueBtn').text(buttonText).prop('disabled', false);
            });

            // Enter key support for role cards
            $(document).on('keypress', '.select-register-role', function(e) {
                if (e.which === 13) {
                    $(this).trigger('click');
                }
            });

            // Continue to registration form
            $('#registerContinueBtn').on('click', function() {
                $('#registerStep1').addClass('hidden');
                $('#registerStep2').removeClass('hidden');

                // Initialize phone input
                if (!modalIti) {
                    modalIti = window.intlTelInput(document.querySelector("#register_phone"), {
                        initialCountry: "us",
                        utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@20.0.0/build/js/utils.js",
                        separateDialCode: true,
                        nationalMode: false
                    });
                }
            });

            // Back to step 1
            $('#backToStep1').on('click', function() {
                $('#registerStep2').addClass('hidden');
                $('#registerStep1').removeClass('hidden');
            });

            // ============================================
            // STEP 2: REGISTRATION FORM
            // ============================================

            // Toggle password visibility - FIXED
            $(document).on('click', '.modal-toggle-password', function(e) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();

                const targetId = $(this).data('target');
                const passwordInput = $(targetId);
                const icon = $(this).find('i');

                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                } else {
                    passwordInput.attr('type', 'password');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                }

                // Don't trigger keyup, just call the function directly
                checkPasswordMatch();

                return false;
            });

// Prevent Enter key on toggle buttons
            $(document).on('keydown', '.modal-toggle-password', function(e) {
                if (e.key === 'Enter' || e.keyCode === 13) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    return false;
                }
            });

            $('#register_username').on('keyup', function() {
                let username = $(this).val();
                let usernameRegex = /^[a-zA-Z0-9]+$/;

                if (usernameRegex.test(username) && username != '') {
                    $.ajax({
                        url: "{{ route('user.name.availability') }}",
                        type: 'post',
                        data: {
                            _token: '{{ csrf_token() }}',
                            username: username
                        },
                        success: function(res) {
                            if (res.status == 'available') {
                                $("#register_user_name_availability").html(
                                    "<span style='color: green;'>" + res.msg + "</span>");
                            } else {
                                $("#register_user_name_availability").html(
                                    "<span style='color: red;'>" + res.msg + "</span>");
                            }
                        }
                    });
                } else {
                    $("#register_user_name_availability").html(
                        "<span style='color: red;'>{{ __('Enter valid username') }}</span>");
                }
            });

            $('#register_email').on('keyup', function() {
                let email = $(this).val();
                let emailRegex = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;

                if (emailRegex.test(email) && email != '') {
                    $.ajax({
                        url: "{{ route('user.email.availability') }}",
                        type: 'post',
                        data: {
                            _token: '{{ csrf_token() }}',
                            email: email
                        },
                        success: function(res) {
                            if (res.status == 'available') {
                                $("#register_email_availability").html(
                                    "<span style='color: green;'>" + res.msg + "</span>");
                            } else {
                                $("#register_email_availability").html(
                                    "<span style='color: red;'>" + res.msg + "</span>");
                            }
                        }
                    });
                } else {
                    $("#register_email_availability").html(
                        "<span style='color: red;'>{{ __('Enter valid email') }}</span>");
                }
            });

            $('#register_phone').on('keyup', function() {
                if (modalIti && modalIti.isValidNumber()) {
                    let phone = modalIti.getNumber();

                    $.ajax({
                        url: "{{ route('user.phone.number.availability') }}",
                        type: 'post',
                        data: {
                            _token: '{{ csrf_token() }}',
                            phone: phone
                        },
                        success: function(res) {
                            if (res.status == 'available') {
                                $("#register_phone_availability").html(
                                    "<span style='color: green;'>" + res.msg + "</span>");
                            } else {
                                $("#register_phone_availability").html(
                                    "<span style='color: red;'>" + res.msg + "</span>");
                            }
                        }
                    });
                } else {
                    $("#register_phone_availability").html(
                        "<span style='color: red;'>{{ __('Enter valid phone number') }}</span>");
                }
            });

            function checkPasswordMatch() {
                let password = $("#register_password").val();
                let confirm_password = $("#register_confirm_password").val();
                let matchElement = $("#register_check_password_match");

                if (password.length > 0 || confirm_password.length > 0) {
                    if (password.length < 6) {
                        matchElement.html("{{__('Password must be at least 6 characters')}}").css("color", "red");
                    } else if (confirm_password.length > 0 && password !== confirm_password) {
                        matchElement.html("{{__('Passwords do not match!')}}").css("color", "red");
                    } else if (confirm_password.length > 0 && password === confirm_password) {
                        matchElement.html("{{__('Passwords match!')}}").css("color", "green");
                    } else {
                        matchElement.html("");
                    }
                } else {
                    matchElement.html("");
                }
            }

            $('#register_password, #register_confirm_password').on('keyup change input', function() {
                checkPasswordMatch();
            });

// Also check on page load/initialization
            checkPasswordMatch();

            // Form submission - FIXED
            $('#modalRegisterForm').on('submit', function(e) {
                e.preventDefault();

                let btn = $('#modalRegisterBtn');
                let erContainer = $(".error-message");
                erContainer.html('');
                btn.prop('disabled', true);
                $('#modal_user_register_load_spinner').html('<i class="fas fa-spinner fa-pulse ml-2"></i>');

                // Get all form values
                let password = $('#register_password').val().trim();
                let confirmPassword = $('#register_confirm_password').val().trim();
                let firstName = $('#register_first_name').val().trim();
                let lastName = $('#register_last_name').val().trim();
                let username = $('#register_username').val().trim();
                let email = $('#register_email').val().trim();

                // Validate password
                if (password.length < 6) {
                    erContainer.html('<div class="alert alert-danger rounded-lg p-3 mb-4"><p class="text-sm">{{ __("Password must be at least 6 characters") }}</p></div>');
                    btn.prop('disabled', false);
                    $('#modal_user_register_load_spinner').html('');
                    return;
                }

                // Validate password match
                if (password !== confirmPassword) {
                    erContainer.html('<div class="alert alert-danger rounded-lg p-3 mb-4"><p class="text-sm">{{ __("Passwords do not match") }}</p></div>');
                    btn.prop('disabled', false);
                    $('#modal_user_register_load_spinner').html('');
                    return;
                }

                // Validate terms and conditions
                if (!$('#register_terms_condition').is(':checked')) {
                    erContainer.html('<div class="alert alert-danger rounded-lg p-3 mb-4"><p class="text-sm">{{ __("You must accept the terms and conditions") }}</p></div>');
                    btn.prop('disabled', false);
                    $('#modal_user_register_load_spinner').html('');
                    return;
                }

                // Get phone number
                let phone = '';
                if (modalIti) {
                    phone = modalIti.getNumber();
                    if (!modalIti.isValidNumber()) {
                        erContainer.html('<div class="alert alert-danger rounded-lg p-3 mb-4"><p class="text-sm">{{ __("Please enter a valid phone number") }}</p></div>');
                        btn.prop('disabled', false);
                        $('#modal_user_register_load_spinner').html('');
                        return;
                    }
                } else {
                    phone = $('#register_phone').val().trim();
                }

                // Get recaptcha if exists
                let recaptchaResponse;
                if (document.getElementById('recaptcha_element_modal')) {
                    recaptchaResponse = grecaptcha.getResponse();
                    if (!recaptchaResponse || recaptchaResponse.length === 0) {
                        erContainer.html('<div class="alert alert-danger rounded-lg p-3 mb-4"><p class="text-sm">{{ __("Google Captcha is required") }}</p></div>');
                        btn.prop('disabled', false);
                        $('#modal_user_register_load_spinner').html('');
                        return;
                    }
                }

                // Debug: Log password before sending
                console.log('Password length:', password.length);
                console.log('Password value:', password);

                let formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('user_type', $('#modalUserType').val());
                formData.append('first_name', firstName);
                formData.append('last_name', lastName);
                formData.append('username', username);
                formData.append('email', email);
                formData.append('phone', phone);
                formData.append('password', password);
                formData.append('confirm_password', confirmPassword);
                formData.append('terms_condition', '1');
                formData.append('recaptchaResponse', recaptchaResponse);

                // Debug: Log FormData
                for (let pair of formData.entries()) {
                    console.log(pair[0] + ': ' + pair[1]);
                }

                 // Debug: Log FormData
                for (let pair of formData.entries()) {
                    console.log(pair[0] + ': ' + pair[1]);
                }

                $.ajax({
                    url: "{{ route('user.register') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status == 'client' || response.status == 'freelancer') {
                            // Store user ID for verification
                            if (response.user_id) {
                                registeredUserId = response.user_id;
                                localStorage.setItem('temp_user_id', response.user_id);
                            }

                            // Move to verification step
                            $('#registerStep2').addClass('hidden');
                            $('#registerStep3').removeClass('hidden');

                            // Show alert banner
                            $('#modalAlertBanner').removeClass('hidden');

                            btn.prop('disabled', false);
                            $('#modal_user_register_load_spinner').html('');
                        } else if (response.redirect) {
                            // Redirect if immediate login is successful
                            window.location.href = response.redirect;
                        }
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON?.errors || {};
                        let errorHtml = '<div class="alert alert-danger rounded-lg p-3 mb-4">';

                        if (xhr.responseJSON?.message) {
                            errorHtml += '<p class="text-sm">' + xhr.responseJSON.message + '</p>';
                        } else {
                            $.each(errors, function(index, value) {
                                if (Array.isArray(value)) {
                                    value.forEach(function(msg) {
                                        errorHtml += '<p class="text-sm">' + msg + '</p>';
                                    });
                                } else {
                                    errorHtml += '<p class="text-sm">' + value + '</p>';
                                }
                            });
                        }

                        errorHtml += '</div>';
                        erContainer.html(errorHtml);
                        btn.prop('disabled', false);
                        $('#modal_user_register_load_spinner').html('');

                        // Reset recaptcha if exists
                        if (typeof grecaptcha !== 'undefined' && document.getElementById('recaptcha_element_modal')) {
                            grecaptcha.reset();
                        }
                    }
                });
            });

            // ============================================
            // STEP 3: VERIFICATION
            // ============================================

            // Close alert banner
            $('#modalCloseAlert').on('click', function() {
                $('#modalAlertBanner').addClass('hidden');
            });

            // Enable verify button when code is entered
            $('#modalVerificationCode').on('input', function() {
                const code = $(this).val().trim();
                $('#modalVerifyBtn').prop('disabled', code.length !== 6);
            });

            // Verification form submission
            $('#modalVerifyForm').on('submit', function(e) {
                e.preventDefault();

                const code = $('#modalVerificationCode').val().trim();
                const btn = $('#modalVerifyBtn');

                // Clear previous errors
                $('#modalCodeError').addClass('hidden').removeClass('text-green-600');

                // Basic validation
                if (code.length !== 6) {
                    $('#modalCodeError').text('{{ __("Verification code must be 6 digits") }}').removeClass('hidden');
                    return;
                }

                btn.prop('disabled', true);
                btn.html('<i class="fas fa-spinner fa-spin mr-2"></i>{{ __("Verifying...") }}');

                $.ajax({
                    url: "{{ route('email.verify') }}",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        email_verify_token: code
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success && response.redirect) {
                            $('#modalCodeError').text('{{ __("Verification successful! Redirecting...") }}')
                                .removeClass('hidden text-red-500')
                                .addClass('text-green-600');
                            setTimeout(function() {
                                window.location.href = response.redirect;
                            }, 1000);
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = '{{ __("Invalid verification code") }}';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors && xhr.responseJSON.errors.email_verify_token) {
                            errorMsg = xhr.responseJSON.errors.email_verify_token[0];
                        }

                        $('#modalCodeError').text(errorMsg).removeClass('hidden text-green-600').addClass('text-red-500');
                        btn.prop('disabled', false);
                        btn.html('{{ __("Verify Account") }}');

                        // Clear the input on error
                        $('#modalVerificationCode').val('');
                    }
                });
            });

            // Resend verification code
            $('#modalResendCode').on('click', function() {
                const btn = $(this);
                const originalText = btn.text();

                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>{{ __("Sending...") }}');

                $.ajax({
                    url: "{{ route('resend.verify.code') }}",
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#modalSuccessMessage').find('p').text(response.message || '{{ __("Verification code has been resent to your email") }}');
                            $('#modalSuccessMessage').removeClass('hidden');
                            $('#modalAlertBanner').addClass('hidden');

                            setTimeout(() => {
                                $('#modalSuccessMessage').addClass('hidden');
                            }, 5000);
                        }

                        btn.prop('disabled', false).text(originalText);
                    },
                    error: function(xhr) {
                        let errorMsg = '{{ __("Failed to resend code. Please try again.") }}';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        alert(errorMsg);
                        btn.prop('disabled', false).text(originalText);
                    }
                });
            });

            // ============================================
            // SOCIAL LOGIN
            // ============================================

            // Social login buttons
            $('.social-register-btn').on('click', function(e) {
                e.preventDefault();
                let provider = $(this).data('provider');
                let user_type = selectedRegisterRole;

                let redirectUrl = '';
                if (provider === 'google') {
                    redirectUrl = '{{ route('login.google.redirect') }}' + '?user_type=' + user_type;
                } else if (provider === 'facebook') {
                    redirectUrl = '{{ route('login.facebook.redirect') }}' + '?user_type=' + user_type;
                }

                if (redirectUrl) {
                    window.location.href = redirectUrl;
                }
            });

            // ============================================
            // HELPER FUNCTIONS
            // ============================================

            function resetRegisterModal() {
                // Reset to step 1
                $('#registerStep1').removeClass('hidden');
                $('#registerStep2').addClass('hidden');
                $('#registerStep3').addClass('hidden');

                // Reset form
                $('#modalRegisterForm')[0].reset();
                $('#modalVerifyForm')[0].reset();

                // Reset validation messages
                $('.error-message').html('');
                $('#register_user_name_availability, #register_email_availability, #register_phone_availability, #register_check_password_match').html('');
                $('#modalCodeError').addClass('hidden').removeClass('text-green-600');

                // Reset password toggle icons
                $('.modal-toggle-password').each(function () {
                    $(this).find('i').removeClass('fa-eye').addClass('fa-eye-slash');
                });

                // Reset password fields to type password
                $('#register_password, #register_confirm_password').attr('type', 'password');

                 // Reset buttons
                $('#registerContinueBtn').prop('disabled', true);
                $('#modalVerifyBtn').prop('disabled', true);
                $('#modalVerifyBtn').html('{{ __("Verify Account") }}');

                 // Reset role selection
                $('.select-register-role').removeClass('border-primary border-2').addClass('border-gray-300 border-2');
                $('#registerFreelancerCard').addClass('border-primary border-2');
                selectedRegisterRole = 2;
                $('#modalUserType').val(2);

                // Reset phone input if exists
                if (modalIti) {
                    modalIti.setNumber('');
                }

                 // Show alert banner in step 3
                $('#modalAlertBanner').removeClass('hidden');
                $('#modalSuccessMessage').addClass('hidden');

                 // Clear stored user ID
                localStorage.removeItem('temp_user_id');
                registeredUserId = null;

                // Reset recaptcha if exists
                if (typeof grecaptcha !== 'undefined' && document.getElementById('recaptcha_element_modal')) {
                    try {
                        grecaptcha.reset();
                    } catch (e) {
                        console.log('reCAPTCHA reset error:', e);
                    }
                }
            }

            // Initialize with freelancer selected
            $('#registerFreelancerCard').addClass('border-primary border-2');
        });
    }(jQuery));
</script>