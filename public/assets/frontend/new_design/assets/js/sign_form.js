
        // Initialize Choices.js
        const countrySelect = new Choices('#country', {
            searchEnabled: true,
            searchPlaceholderValue: 'Search country...',
            itemSelectText: '',
            shouldSort: false,
            position: 'bottom',
            removeItemButton: false,
            classNames: {
                containerOuter: 'choices w-full',
                containerInner: 'choices__inner px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none transition bg-white',
                input: 'choices__input',
                inputCloned: 'choices__input--cloned',
                list: 'choices__list',
                listItems: 'choices__list--multiple',
                listSingle: 'choices__list--single',
                listDropdown: 'choices__list--dropdown',
                item: 'choices__item',
                itemSelectable: 'choices__item--selectable',
                itemDisabled: 'choices__item--disabled',
                itemChoice: 'choices__item--choice',
                placeholder: 'choices__placeholder',
                group: 'choices__group',
                groupHeading: 'choices__heading',
                button: 'choices__button',
                activeState: 'is-active',
                focusState: 'is-focused',
                openState: 'is-open',
                disabledState: 'is-disabled',
                highlightedState: 'is-highlighted',
                selectedState: 'is-selected',
                flippedState: 'is-flipped',
                loadingState: 'is-loading',
                noResults: 'has-no-results',
                noChoices: 'has-no-choices'
            }
        });

        function showSelected() {
            const selected = document.getElementById('country').value;
            const resultDiv = document.getElementById('result');
            const resultText = resultDiv.querySelector('p');
            
            if (selected) {
                resultText.textContent = `Selected Country: ${selected}`;
                resultDiv.classList.remove('hidden');
            } else {
                resultText.textContent = 'Please select a country';
                resultDiv.classList.remove('hidden');
            }
        }


document.addEventListener('DOMContentLoaded', function () {
  // Elements
  const form = document.getElementById('signupForm');
  const togglePassword = document.getElementById('togglePassword');
  const passwordInput = document.getElementById('password');
  const submitButton = document.getElementById('submitButton');
  const firstNameInput = document.getElementById('firstName');
  const lastNameInput = document.getElementById('lastName');
  const countrySelectEl = document.getElementById('country');
  const termsCheckbox = document.getElementById('terms');

  // Guard
  if (!form || !togglePassword || !passwordInput || !submitButton || !firstNameInput || !lastNameInput || !countrySelectEl || !termsCheckbox) {
    console.error('sign_form.js: required form elements not found', { form, togglePassword, passwordInput, submitButton, firstNameInput, lastNameInput, countrySelectEl, termsCheckbox });
    return;
  }

  // Initialize Choices (only once)
  let choicesInstance = null;
  if (typeof Choices !== 'undefined') {
    choicesInstance = new Choices(countrySelectEl, {
      searchEnabled: true,
      searchPlaceholderValue: 'Search country...',
      itemSelectText: '',
      shouldSort: false,
      position: 'bottom',
      removeItemButton: false
    });
  }

  // Toggle password visibility
  togglePassword.addEventListener('click', function () {
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
    const icon = this.querySelector('i');
    if (icon) {
      if (type === 'text') {
        icon.classList.remove('ti-eye-off'); icon.classList.add('ti-eye');
      } else {
        icon.classList.remove('ti-eye'); icon.classList.add('ti-eye-off');
      }
    }
  });

  // Validation
  function validateForm() {
    const firstName = firstNameInput.value.trim();
    const lastName = lastNameInput.value.trim();
    const password = passwordInput.value || '';
    const country = countrySelectEl.value || '';
    const termsAccepted = termsCheckbox.checked;

    const isValid = firstName !== '' &&
                    lastName !== '' &&
                    password.length >= 8 &&
                    country !== '' &&
                    termsAccepted;

    submitButton.disabled = !isValid;
  }

  // Listen for changes
  firstNameInput.addEventListener('input', validateForm);
  lastNameInput.addEventListener('input', validateForm);
  passwordInput.addEventListener('input', validateForm);
  termsCheckbox.addEventListener('change', validateForm);
  countrySelectEl.addEventListener('change', validateForm);

  // If Choices exposes the original element differently, attach there as well
  if (choicesInstance && choicesInstance.passedElement) {
    const original = choicesInstance.passedElement.element || countrySelectEl;
    original.addEventListener('change', validateForm);
  }

  // Optional: show selected (only if you have a #result block)
  function showSelected() {
    const resultDiv = document.getElementById('result');
    if (!resultDiv) return;
    const resultText = resultDiv.querySelector('p');
    if (!resultText) return;
    const selected = countrySelectEl.value;
    resultText.textContent = selected ? `Selected Country: ${selected}` : 'Please select a country';
    resultDiv.classList.remove('hidden');
  }
  countrySelectEl.addEventListener('change', showSelected);

  // Submit handler
  form.addEventListener('submit', function (e) {
    e.preventDefault();
    const formData = {
      firstName: firstNameInput.value.trim(),
      lastName: lastNameInput.value.trim(),
      password: passwordInput.value,
      country: countrySelectEl.value,
      termsAccepted: termsCheckbox.checked
    };
    console.log('Form Data:', formData);
    alert('Sign up successful! Check console for form data.');
  });

  // Initial validation
  validateForm();
});