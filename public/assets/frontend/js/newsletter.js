(function($) {
    "use strict";

    $(document).ready(function() {
        // Newsletter subscription
        $(document).on('submit', '.newsletter_subscribe_from_footer', function(e) {
            e.preventDefault();

            var form = $(this);
            var email = form.find('input[name="email"]').val();
            var submitBtn = form.find('.subscription_by_email_newsletter');
            var errorDiv = form.find('.error-message');
            var successDiv = form.find('.success-message');

            // Clear previous messages
            errorDiv.html('');
            successDiv.html('');

            // Disable submit button
            submitBtn.prop('disabled', true);

            $.ajax({
                url: '/news-letter/subscribe/by/user',
                type: 'POST',
                data: {
                    email: email,
                    _token: form.find('input[name="_token"]').val()
                },
                success: function(response) {
                    if (response.status === 'success') {
                        successDiv.html('Successfully subscribed to newsletter!');
                        form.find('input[name="email"]').val('');
                    } else {
                        errorDiv.html(response.msg || 'Something went wrong. Please try again.');
                    }
                    submitBtn.prop('disabled', false);
                },
                error: function(xhr) {
                    var errorMessage = 'Something went wrong. Please try again.';

                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        if (xhr.responseJSON.errors.email) {
                            errorMessage = xhr.responseJSON.errors.email[0];
                        }
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    errorDiv.html(errorMessage);
                    submitBtn.prop('disabled', false);
                }
            });
        });
    });

})(jQuery);