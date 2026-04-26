//  Verify Your Account - JavaScript Functionality 

// DOM Elements
const alertBanner = document.getElementById('alertBanner');
const closeAlertBtn = document.getElementById('closeAlert');
const successMessage = document.getElementById('successMessage');
const verifyForm = document.getElementById('verifyForm');
const verificationCodeInput = document.getElementById('verificationCode');
const verifyBtn = document.getElementById('verifyBtn');
const resendCodeBtn = document.getElementById('resendCode');
const codeError = document.getElementById('codeError');

// Configuration
const MIN_CODE_LENGTH = 6;
const RESEND_COOLDOWN = 30000; // 30 seconds

// State
let resendCooldownActive = false;

/**
 * Close Alert Banner
 * Hides the alert banner with smooth animation
 */
function closeAlert() {
    alertBanner.classList.add('fade-out');
    setTimeout(() => {
        alertBanner.style.display = 'none';
    }, 300);
}

/**
 * Show Success Message
 * Displays success message after resending code
 */
function showSuccessMessage() {
    successMessage.classList.remove('hidden');
    successMessage.classList.add('fade-in');
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        successMessage.classList.add('fade-out');
        setTimeout(() => {
            successMessage.classList.add('hidden');
            successMessage.classList.remove('fade-out', 'fade-in');
        }, 300);
    }, 5000);
}

/**
 * Validate Verification Code
 * Checks if code is numeric and meets minimum length
 * @param {string} code - The verification code to validate
 * @returns {boolean} - True if valid, false otherwise
 */
function validateCode(code) {
    // Remove any whitespace
    code = code.trim();
    
    // Check if empty
    if (code.length === 0) {
        return false;
    }
    
    // Check if numeric only
    if (!/^\d+$/.test(code)) {
        showError('Verification code must contain only numbers');
        return false;
    }
    
    // Check minimum length
    if (code.length < MIN_CODE_LENGTH) {
        showError(`Verification code must be at least ${MIN_CODE_LENGTH} digits`);
        return false;
    }
    
    hideError();
    return true;
}

/**
 * Show Error Message
 * Displays validation error below input field
 * @param {string} message - Error message to display
 */
function showError(message) {
    codeError.textContent = message;
    codeError.classList.remove('hidden');
    verificationCodeInput.classList.add('border-red-500');
    verificationCodeInput.classList.remove('border-gray-300');
}

/**
 * Hide Error Message
 * Removes validation error message
 */
function hideError() {
    codeError.classList.add('hidden');
    verificationCodeInput.classList.remove('border-red-500');
    verificationCodeInput.classList.add('border-gray-300');
}

/**
 * Update Verify Button State
 * Enables/disables button based on input validity
 */
function updateVerifyButtonState() {
    const code = verificationCodeInput.value.trim();
    
    if (code.length >= MIN_CODE_LENGTH && /^\d+$/.test(code)) {
        verifyBtn.disabled = false;
    } else {
        verifyBtn.disabled = true;
    }
}

/**
 * Handle Verification Form Submit
 * Validates and processes verification code
 * @param {Event} e - Form submit event
 */
function handleVerifySubmit(e) {
    e.preventDefault();
    
    const code = verificationCodeInput.value.trim();
    
    if (validateCode(code)) {
        console.log('Verification code entered:', code);
        
        // Simulate verification process
        verifyBtn.disabled = true;
        verifyBtn.textContent = 'Verifying...';
        
        // Simulate API call
        setTimeout(() => {
            console.log('Verification successful!');
            alert('Account verified successfully!');
            verifyBtn.textContent = 'Verify Account';
            verifyBtn.disabled = false;
            verificationCodeInput.value = '';
        }, 1500);
    }
}

/**
 * Handle Resend Code Click
 * Resends verification code with cooldown period
 */
function handleResendCode() {
    if (resendCooldownActive) {
        return;
    }
    
    // Set cooldown state
    resendCooldownActive = true;
    resendCodeBtn.disabled = true;
    
    // Show countdown
    let countdown = 30;
    const originalText = resendCodeBtn.textContent;
    resendCodeBtn.textContent = `Resend Code (${countdown}s)`;
    
    const countdownInterval = setInterval(() => {
        countdown--;
        if (countdown > 0) {
            resendCodeBtn.textContent = `Resend Code (${countdown}s)`;
        } else {
            clearInterval(countdownInterval);
            resendCodeBtn.textContent = originalText;
            resendCodeBtn.disabled = false;
            resendCooldownActive = false;
        }
    }, 1000);
    
    // Simulate API call to resend code
    console.log('Resending verification code...');
    
    // Hide alert banner if visible
    if (alertBanner.style.display !== 'none') {
        closeAlert();
    }
    
    // Show success message
    setTimeout(() => {
        showSuccessMessage();
        console.log('Verification code resent successfully');
    }, 500);
}

/**
 * Handle Input - Numeric Only
 * Restricts input to numbers only
 * @param {Event} e - Input event
 */
function handleInput(e) {
    // Allow only numeric input
    e.target.value = e.target.value.replace(/[^\d]/g, '');
    
    // Update button state
    updateVerifyButtonState();
    
    // Hide error on input change
    if (codeError.classList.contains('hidden') === false) {
        hideError();
    }
}

/**
 * Handle Paste Event
 * Ensures pasted content is numeric only
 * @param {Event} e - Paste event
 */
function handlePaste(e) {
    e.preventDefault();
    const pastedText = (e.clipboardData || window.clipboardData).getData('text');
    const numericOnly = pastedText.replace(/[^\d]/g, '');
    verificationCodeInput.value = numericOnly;
    updateVerifyButtonState();
}

// Event Listeners
closeAlertBtn.addEventListener('click', closeAlert);
verifyForm.addEventListener('submit', handleVerifySubmit);
verificationCodeInput.addEventListener('input', handleInput);
verificationCodeInput.addEventListener('paste', handlePaste);
resendCodeBtn.addEventListener('click', handleResendCode);

// Initialize button state on page load
updateVerifyButtonState();

console.log('Verify Your Account page loaded successfully');