// ============================================
// SIGNUP FLOW MANAGER - Vanilla JavaScript
// ============================================

class SignupFlowManager {
  constructor() {
    // State
    this.selectedRole = 'freelancer'; // default
    this.resendCooldown = false;
    
    // DOM Elements - Role Selection
    this.freelancerCard = document.getElementById('freelancerCard');
    this.clientCard = document.getElementById('clientCard');
    this.joinButton = document.getElementById('joinButton');
    
    // DOM Elements - Signup Form
    this.signupFormContainer = document.querySelector('.w-full.h-full.lg\\:max-h-fit');
    this.signupForm = document.getElementById('signupForm');
    this.submitButton = document.getElementById('submitButton');
    this.termsCheckbox = document.getElementById('terms');
    this.togglePasswordBtn = document.getElementById('togglePassword');
    this.passwordInput = document.getElementById('password');
    
    // DOM Elements - Verification
    this.verificationCard = document.querySelector('.bg-white.rounded-lg.border.border-gray-200.shadow-sm');
    this.verifyForm = document.getElementById('verifyForm');
    this.verificationCodeInput = document.getElementById('verificationCode');
    this.verifyBtn = document.getElementById('verifyBtn');
    this.resendCodeBtn = document.getElementById('resendCode');
    this.closeAlertBtn = document.getElementById('closeAlert');
    this.alertBanner = document.getElementById('alertBanner');
    this.successMessage = document.getElementById('successMessage');
    this.codeError = document.getElementById('codeError');
    
    // Role Selection Card Container
    this.roleSelectionCard = document.querySelector('.relative.w-full.max-w-5xl.z-30.bg-white.rounded-3xl');
    
    this.init();
  }
  
  init() {
    this.setupRoleSelection();
    this.setupSignupForm();
    this.setupVerification();
  }
  
  // ============================================
  // ROLE SELECTION LOGIC
  // ============================================
  
  setupRoleSelection() {
    // Freelancer card click handler
    this.freelancerCard.addEventListener('click', () => {
      this.selectRole('freelancer');
    });
    
    // Client card click handler
    this.clientCard.addEventListener('click', () => {
      this.selectRole('client');
    });
    
    // Keyboard support for cards
    this.freelancerCard.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        this.selectRole('freelancer');
      }
    });
    
    this.clientCard.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        this.selectRole('client');
      }
    });
    
    // Join button - show signup form
    this.joinButton.addEventListener('click', () => {
      this.showSignupForm();
    });
  }
  
  selectRole(role) {
    this.selectedRole = role;
    
    if (role === 'freelancer') {
      // Style freelancer card as active
      this.freelancerCard.classList.remove('border-gray-300', 'bg-white');
      this.freelancerCard.classList.add('border-primary', 'shadow-lg', 'bg-primary/5');
      this.freelancerCard.setAttribute('aria-pressed', 'true');
      
      // Style client card as inactive
      this.clientCard.classList.remove('border-primary', 'shadow-lg', 'bg-primary/5');
      this.clientCard.classList.add('border-gray-300', 'bg-white');
      this.clientCard.setAttribute('aria-pressed', 'false');
      
      // Update button text
      this.joinButton.textContent = 'Join as a Freelancer';
    } else {
      // Style client card as active
      this.clientCard.classList.remove('border-gray-300', 'bg-white');
      this.clientCard.classList.add('border-primary', 'shadow-lg', 'bg-primary/5');
      this.clientCard.setAttribute('aria-pressed', 'true');
      
      // Style freelancer card as inactive
      this.freelancerCard.classList.remove('border-primary', 'shadow-lg', 'bg-primary/5');
      this.freelancerCard.classList.add('border-gray-300', 'bg-white');
      this.freelancerCard.setAttribute('aria-pressed', 'false');
      
      // Update button text
      this.joinButton.textContent = 'Join as a Client';
    }
  }
  
  showSignupForm() {
    // Hide role selection card with transition
    this.roleSelectionCard.classList.add('opacity-0', 'pointer-events-none');
    
    // Show signup form after brief delay for smooth transition
    setTimeout(() => {
      this.roleSelectionCard.classList.add('hidden');
      this.signupFormContainer.classList.remove('hidden');
      
      // Trigger reflow for transition
      this.signupFormContainer.offsetHeight;
      this.signupFormContainer.classList.add('opacity-100');
    }, 300);
  }
  
  // ============================================
  // SIGNUP FORM LOGIC
  // ============================================
  
  setupSignupForm() {
    // Get all required inputs
    const requiredInputs = this.signupForm.querySelectorAll('[required]');
    
    // Validate form on input change
    requiredInputs.forEach(input => {
      input.addEventListener('input', () => {
        this.validateSignupForm();
      });
    });
    
    // Terms checkbox
    this.termsCheckbox.addEventListener('change', () => {
      this.validateSignupForm();
    });
    
    // Password toggle
    this.togglePasswordBtn.addEventListener('click', () => {
      this.togglePasswordVisibility();
    });
    
    // Form submission
    this.signupForm.addEventListener('submit', (e) => {
      e.preventDefault();
      this.handleSignupSubmit();
    });
  }
  
  validateSignupForm() {
    const firstName = document.getElementById('firstName').value.trim();
    const lastName = document.getElementById('lastName').value.trim();
    const password = document.getElementById('password').value;
    const country = document.getElementById('country').value;
    const termsChecked = this.termsCheckbox.checked;
    
    // Enable submit button only if all fields are filled and terms are checked
    const isValid = firstName && lastName && password.length >= 8 && country && termsChecked;
    
    this.submitButton.disabled = !isValid;
  }
  
  togglePasswordVisibility() {
    const icon = this.togglePasswordBtn.querySelector('i');
    
    if (this.passwordInput.type === 'password') {
      this.passwordInput.type = 'text';
      icon.classList.remove('ti-eye-off');
      icon.classList.add('ti-eye');
    } else {
      this.passwordInput.type = 'password';
      icon.classList.remove('ti-eye');
      icon.classList.add('ti-eye-off');
    }
  }
  
  handleSignupSubmit() {
    // In production, this would send data to backend
    console.log('Signup submitted with role:', this.selectedRole);
    
    // Simulate API call delay
    this.submitButton.disabled = true;
    this.submitButton.textContent = 'Creating Account...';
    
    setTimeout(() => {
      this.showVerificationCard();
    }, 1000);
  }
  
  showVerificationCard() {
    // Hide signup form
    this.signupFormContainer.classList.add('opacity-0');
    
    setTimeout(() => {
      this.signupFormContainer.classList.add('hidden');
      this.verificationCard.classList.remove('hidden');
      
      // Trigger reflow for transition
      this.verificationCard.offsetHeight;
      this.verificationCard.classList.add('opacity-100');
    }, 300);
  }
  
  // ============================================
  // VERIFICATION LOGIC
  // ============================================
  
  setupVerification() {
    // Enable verify button only when code is entered
    this.verificationCodeInput.addEventListener('input', () => {
      const code = this.verificationCodeInput.value.trim();
      this.verifyBtn.disabled = code.length === 0;
      
      // Clear error when user types
      this.codeError.classList.add('hidden');
    });
    
    // Verify form submission
    this.verifyForm.addEventListener('submit', (e) => {
      e.preventDefault();
      this.handleVerification();
    });
    
    // Resend code button
    this.resendCodeBtn.addEventListener('click', () => {
      this.handleResendCode();
    });
    
    // Close alert banner
    this.closeAlertBtn.addEventListener('click', () => {
      this.alertBanner.classList.add('hidden');
    });
  }
  
  handleVerification() {
    const code = this.verificationCodeInput.value.trim();
    
    // Simulate verification (in production, call backend API)
    this.verifyBtn.disabled = true;
    this.verifyBtn.textContent = 'Verifying...';
    
    setTimeout(() => {
      // Simulate validation - check if code is "123456"
      if (code === '123456') {
        // Success - redirect or show success message
        alert('Account verified successfully!');
        // In production: window.location.href = '/dashboard';
      } else {
        // Show error
        this.codeError.textContent = 'Invalid verification code. Please try again.';
        this.codeError.classList.remove('hidden');
        this.verifyBtn.disabled = false;
        this.verifyBtn.textContent = 'Verify Account';
      }
    }, 1000);
  }
  
  handleResendCode() {
    if (this.resendCooldown) return;
    
    // Set cooldown
    this.resendCooldown = true;
    this.resendCodeBtn.disabled = true;
    
    // Show success message
    this.successMessage.classList.remove('hidden');
    
    // Simulate API call
    setTimeout(() => {
      // Hide success message after 3 seconds
      setTimeout(() => {
        this.successMessage.classList.add('hidden');
      }, 3000);
      
      // Re-enable resend button after 30 seconds cooldown
      setTimeout(() => {
        this.resendCooldown = false;
        this.resendCodeBtn.disabled = false;
      }, 30000);
    }, 500);
  }
}

// ============================================
// INITIALIZE ON DOM READY
// ============================================

document.addEventListener('DOMContentLoaded', () => {
  new SignupFlowManager();
});
