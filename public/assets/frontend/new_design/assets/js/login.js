document.addEventListener('DOMContentLoaded', function() {
    // 1. ADD THIS: Modal open/close handlers
    // Handle clicking on signup/login buttons
    document.addEventListener('click', function(e) {
        // Check if clicked element or its parent has data-toggle="login-modal"
        const loginModalTrigger = e.target.closest('[data-toggle="login-modal"]');

        if (loginModalTrigger) {
            e.preventDefault();
            e.stopPropagation();

            // Show the modal
            const loginModal = document.getElementById('loginModal');
            if (loginModal) {
                loginModal.classList.remove('hidden');
                loginModal.classList.add('flex');
            } else {
                console.log('Login modal not found');
                // Fallback: redirect to login page
                window.location.href = '/login';
            }
        }

        // Close modal on outside click
        if (e.target.id === 'loginModal') {
            e.target.classList.remove('flex');
            e.target.classList.add('hidden');
        }

        // Close modal with close button
        if (e.target.id === 'closeLoginModal' || e.target.closest('#closeLoginModal')) {
            const modal = document.getElementById('loginModal');
            if (modal) {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }
        }
    });

    // 2. YOUR EXISTING PASSWORD TOGGLE CODE
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const loginForm = document.getElementById('loginForm');
    const emailInput = document.getElementById('email');
    const agreeCheckbox = document.getElementById('agree');

    // Password visibility toggle
    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            const icon = this.querySelector('i');
            if (type === 'text') {
                icon.classList.remove('ti-eye');
                icon.classList.add('ti-eye-off');
            } else {
                icon.classList.remove('ti-eye-off');
                icon.classList.add('ti-eye');
            }
        });
    }

    // 3. FORM SUBMISSION - Update this to work with Laravel AJAX
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Remove your existing validation code and replace with AJAX call
            // Your existing validation is interfering with the jQuery AJAX

            // Instead, let the jQuery handle it from login-modal.blade.php
            // Just prevent default and let jQuery take over
            return false;
        });
    }

    // 4. Remove error styling on input
    if (emailInput) {
        emailInput.addEventListener('input', function() {
            this.classList.remove('border-red-500');
        });
    }

    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            this.classList.remove('border-red-500');
        });
    }
});

// 5. Fill credentials function for demo login buttons
function fillCredentials(username, password) {
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const agreeCheckbox = document.getElementById('agree');

    if (emailInput) emailInput.value = username;
    if (passwordInput) passwordInput.value = password;
    if (agreeCheckbox) agreeCheckbox.checked = true;
}