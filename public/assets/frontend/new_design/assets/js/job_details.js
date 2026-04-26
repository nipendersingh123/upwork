$(document).ready(function () {
    // Setup AJAX to include CSRF token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    const modalOverlay = $('#modalOverlay');
    const openModalBtn = $('.openModal');
    const closeModalBtn = $('#closeModal');
    const proposalForm = $('#proposalForm');
    const coverLetter = $('#coverLetter');
    const charCount = $('#charCount');
    const fileInput = $('#fileInput');
    const fileStatus = $('#fileStatus');

    // Open modal
    openModalBtn.on('click', function () {
        modalOverlay.removeClass('hidden').addClass('flex');
        $('body').css('overflow', 'hidden');
    });

    // Close modal
    function closeModal() {
        modalOverlay.addClass('hidden').removeClass('flex');
        $('body').css('overflow', 'auto');
    }

    closeModalBtn.on('click', closeModal);

    // Close modal when clicking outside
    modalOverlay.on('click', function (e) {
        if ($(e.target).is(modalOverlay)) {
            closeModal();
        }
    });

    // Close modal on Escape key
    $(document).on('keydown', function (e) {
        if (e.key === 'Escape' && !modalOverlay.hasClass('hidden')) {
            closeModal();
        }
    });

    // Character counter
    coverLetter.on('input', function () {
        const count = $(this).val().length;
        charCount.text(count);
    });

    // File input change
    fileInput.on('change', function () {
        const files = this.files;
        if (files.length > 0) {
            if (files.length === 1) {
                fileStatus.text(files[0].name);
            } else {
                fileStatus.text(files.length + ' files selected');
            }
        } else {
            fileStatus.text('No file chosen');
        }
    });


    // Form submission with AJAX - FIXED VERSION
    proposalForm.on('submit', function (e) {
        e.preventDefault();
        console.log('Form submitted via AJAX - Fixed Version');

        // Clear previous messages
        $('#errorMessages').addClass('hidden');
        $('#successMessage').addClass('hidden');
        $('#errorList').empty();

        // Client-side validation
        const amount = $('#proposalAmount').val();
        const deliveryTime = $('#deliveryTime').val();
        const revision = $('#revision').val();
        const coverLetterText = coverLetter.val();

        // Validation
        let errors = [];
        if (!amount || parseFloat(amount) <= 0) {
            errors.push('Proposal amount must be greater than 0');
        }
        if (!deliveryTime || deliveryTime === '') {
            errors.push('Delivery time is required');
        }
        if (!revision || revision === '') {
            errors.push('Revision is required');
        } else if (parseInt(revision) < 0 || parseInt(revision) > 100) {
            errors.push('Revision must be between 0 and 100');
        }
        if (!coverLetterText || coverLetterText.length < 10) {
            errors.push('Cover letter must be at least 10 characters long');
        }

        if (errors.length > 0) {
            showError(errors);
            return false;
        }

        // Prepare form data
        const formData = new FormData(this);

        // Add AJAX indicator
        formData.append('_ajax', '1');

        // Disable submit button
        const submitBtn = proposalForm.find('button[type="submit"]');
        const originalBtnText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Sending...');

        // Submit via AJAX with proper headers
        $.ajax({
            url: proposalForm.attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            success: function(response) {
                console.log('AJAX Success Response:', response);

                // Check if response is HTML (error case)
                if (typeof response === 'string' && response.startsWith('<!DOCTYPE')) {
                    console.log('Received HTML instead of JSON');
                    showError(['Server returned an error. Please try again.']);
                    return;
                }

                if (response.success) {
                    // Show success message
                    $('#successText').text(response.message || 'Proposal successfully sent!');
                    $('#successMessage').removeClass('hidden');

                    // Reset form
                    proposalForm[0].reset();
                    charCount.text('0');
                    fileStatus.text('No file chosen');

                    // Close modal after 2 seconds and reload page
                    setTimeout(function() {
                        closeModal();
                        location.reload();
                    }, 2000);
                } else if (response.errors) {
                    showError(response.errors);
                } else {
                    showError(['An unexpected error occurred.']);
                }
            },
            error: function(xhr, status, error) {
                console.log('AJAX Error Details:', {
                    status: xhr.status,
                    responseText: xhr.responseText,
                    error: error
                });

                if (xhr.status === 422) {
                    // Validation errors
                    try {
                        const errors = JSON.parse(xhr.responseText);
                        if (errors.errors) {
                            showError(Object.values(errors.errors).flat());
                        } else if (errors.message) {
                            showError([errors.message]);
                        }
                    } catch (e) {
                        showError(['Validation error occurred.']);
                    }
                } else if (xhr.status === 419) {
                    showError(['Session expired. Please refresh the page.']);
                } else {
                    // Try to extract error from HTML response
                    try {
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = xhr.responseText;
                        const errorElement = tempDiv.querySelector('.toastr-error');
                        if (errorElement) {
                            showError([errorElement.textContent.trim()]);
                        } else {
                            showError(['An error occurred. Please try again.']);
                        }
                    } catch (e) {
                        showError(['An error occurred. Please try again.']);
                    }
                }
            },
            complete: function() {
                // Re-enable submit button
                submitBtn.prop('disabled', false).html(originalBtnText);
            }
        });
    });

// Helper function to show errors - FIXED FOR MODAL
    function showError(messages) {
        console.log('Showing errors:', messages);
        $('#errorList').empty();
        messages.forEach(function(message) {
            $('#errorList').append('<li>' + message + '</li>');
        });
        $('#errorMessages').removeClass('hidden');

        // Scroll to error message within modal
        const modalContent = modalOverlay.find('.custom-scrollbar, .overflow-y-auto');
        if (modalContent.length) {
            modalContent.animate({
                scrollTop: 0
            }, 300);
        } else {
            // Fallback to scroll within modal overlay
            modalOverlay.animate({
                scrollTop: 0
            }, 300);
        }
    }

});


$('#deliveryTime').select2({
    dropdownParent: $('#modalOverlay'),
    minimumResultsForSearch: Infinity
});