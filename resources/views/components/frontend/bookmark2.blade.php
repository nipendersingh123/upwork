@props(['identity', 'type', 'btnClass' => 'absolute top-6 right-6 p-2 bg-white rounded-full hover:text-red-500 transition-colors z-20'])

@php
    $uniqueId = 'bookmark_' . $identity . '_' . uniqid();
@endphp

@if(Auth::guard('web')->check())
    @php
        $bookmark_exists = \App\Models\Bookmark::where('user_id', Auth::guard('web')->user()->id)
            ->where('identity', $identity)
            ->where('is_project_job', $type)
            ->first();

        $user_type = Auth::guard('web')->user()->user_type;
        $add_route = $user_type == 1 ? route('client.bookmark') : route('freelancer.bookmark');
        $remove_route = $user_type == 1 ? route('client.bookmark.remove') : route('freelancer.bookmark.remove');
    @endphp

    @if($bookmark_exists)
        {{-- Remove bookmark button --}}
        <button type="button"
                id="{{ $uniqueId }}"
                aria-label="Remove from favorites"
                class="{{ $btnClass }} remove_from_bookmark bookmark-btn"
                data-bookmark-id="{{ $bookmark_exists->id }}"
                data-identity="{{ $identity }}"
                data-type="{{ $type }}"
                data-add-route="{{ $add_route }}"
                data-remove-route="{{ $remove_route }}"
                onclick="bookmarkHandler.toggleBookmark(event, this, true)"
                style="pointer-events: auto;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"
                 stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-500"
                 style="pointer-events: none;">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"
                      style="pointer-events: none;" />
            </svg>
        </button>
    @else
        {{-- Add bookmark button --}}
        <button type="button"
                id="{{ $uniqueId }}"
                aria-label="Add to favorites"
                class="{{ $btnClass }} add_to_bookmark bookmark-btn"
                data-identity="{{ $identity }}"
                data-type="{{ $type }}"
                data-add-route="{{ $add_route }}"
                data-remove-route="{{ $remove_route }}"
                onclick="bookmarkHandler.toggleBookmark(event, this, false)"
                style="pointer-events: auto;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 stroke-width="1.5" stroke="currentColor" class="w-6 h-6"
                 style="pointer-events: none;">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"
                      style="pointer-events: none;" />
            </svg>
        </button>
    @endif
@else
    {{-- Guest user - prompt login --}}
    <button type="button"
            id="{{ $uniqueId }}"
            aria-label="Add to favorites (Login required)"
            class="{{ $btnClass }} bookmark-login-required bookmark-btn"
            data-identity="{{ $identity }}"
            data-type="{{ $type }}"
            onclick="bookmarkHandler.loginRequired(event)"
            style="pointer-events: auto;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
             stroke-width="1.5" stroke="currentColor" class="w-6 h-6"
             style="pointer-events: none;">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"
                  style="pointer-events: none;" />
        </svg>
    </button>
@endif

{{-- Initialize bookmark handler script --}}
@once
    @push('page_scripts')
        <script>
            // Create a global bookmark handler object
            window.bookmarkHandler = {
                isProcessing: false,

                toggleBookmark: function(event, button, isBookmarked) {
                    event.preventDefault();
                    event.stopPropagation();

                    if (this.isProcessing) return;

                    var $btn = $(button);
                    var identity = $btn.data('identity');
                    var type = $btn.data('type');
                    var addRoute = $btn.data('add-route');
                    var removeRoute = $btn.data('remove-route');

                    // Determine which action to take
                    var route = isBookmarked ? removeRoute : addRoute;
                    var action = isBookmarked ? 'remove' : 'add';

                    console.log('=== TOGGLE BOOKMARK ===');
                    console.log('Action:', action);
                    console.log('Identity:', identity);
                    console.log('Type:', type);
                    console.log('Route:', route);

                    if (!route || !identity || !type) {
                        console.error('Missing data!');
                        alert('Error: Missing bookmark data');
                        return;
                    }

                    this.isProcessing = true;
                    $btn.prop('disabled', true).css('opacity', '0.5');

                    // Prepare data based on action
                    var data = {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    };

                    if (action === 'add') {
                        data.identity = identity;
                        data.type = type;
                    } else {
                        // For remove, we need the bookmark ID
                        data.identity = $btn.data('bookmark-id') || identity;
                    }

                    $.ajax({
                        url: route,
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: data,
                        success: (res) => {
                            console.log('=== SUCCESS ===');
                            console.log('Response:', res);

                            if (res.status === 'success' || res.status === 'exists') {
                                // Update button appearance without reload
                                this.updateButtonUI($btn, action, res.bookmark_id);

                                // Show appropriate message
                                if (action === 'add') {
                                    if (typeof toastr !== 'undefined') {
                                        toastr.success(res.message || 'Bookmark added');
                                    } else {
                                        alert('Bookmark added successfully!');
                                    }
                                } else {
                                    if (typeof toastr !== 'undefined') {
                                        toastr.success(res.message || 'Bookmark removed');
                                    } else {
                                        alert('Bookmark removed');
                                    }
                                }
                            } else if (res.status === 'error') {
                                alert(res.message || 'Failed to update bookmark');
                                this.isProcessing = false;
                                $btn.prop('disabled', false).css('opacity', '1');
                            }
                        },
                        error: (xhr, status, error) => {
                            console.error('=== ERROR ===');
                            console.error('Status:', status);
                            console.error('Error:', error);
                            console.error('XHR Status:', xhr.status);

                            var errorMsg = 'Failed to update bookmark';

                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON.message;
                            } else if (xhr.status === 401) {
                                errorMsg = 'Please login to bookmark';
                            } else if (xhr.status === 419) {
                                errorMsg = 'Session expired. Refresh the page.';
                            } else if (xhr.status === 500) {
                                errorMsg = 'Server error. Check logs.';
                            } else if (xhr.status === 404) {
                                errorMsg = 'Route not found: ' + route;
                            }

                            if (typeof toastr !== 'undefined') {
                                toastr.error(errorMsg);
                            } else {
                                alert('ERROR: ' + errorMsg);
                            }

                            this.isProcessing = false;
                            $btn.prop('disabled', false).css('opacity', '1');
                        }
                    });
                },

                updateButtonUI: function($btn, action, bookmarkId) {
                    // Toggle button classes and attributes
                    $btn.removeClass('processing');

                    if (action === 'add') {
                        // Change from "add" to "remove" state
                        $btn.removeClass('add_to_bookmark').addClass('remove_from_bookmark');
                        $btn.data('bookmark-id', bookmarkId);
                        $btn.attr('onclick', 'bookmarkHandler.toggleBookmark(event, this, true)');
                        $btn.attr('aria-label', 'Remove from favorites');

                        // Update SVG icon to filled heart
                        $btn.find('svg').attr('fill', 'currentColor');
                        $btn.find('path').attr('stroke', 'currentColor');
                        $btn.find('svg').addClass('text-red-500');

                        // Change tooltip if you have one
                        if ($btn.attr('title')) {
                            $btn.attr('title', 'Remove from favorites');
                        }
                    } else {
                        // Change from "remove" to "add" state
                        $btn.removeClass('remove_from_bookmark').addClass('add_to_bookmark');
                        $btn.removeData('bookmark-id');
                        $btn.attr('onclick', 'bookmarkHandler.toggleBookmark(event, this, false)');
                        $btn.attr('aria-label', 'Add to favorites');

                        // Update SVG icon to outline heart
                        $btn.find('svg').attr('fill', 'none');
                        $btn.find('path').attr('stroke', 'currentColor');
                        $btn.find('svg').removeClass('text-red-500');

                        // Change tooltip if you have one
                        if ($btn.attr('title')) {
                            $btn.attr('title', 'Add to favorites');
                        }
                    }

                    // Re-enable button
                    this.isProcessing = false;
                    $btn.prop('disabled', false).css('opacity', '1');

                    // Optional: Add a subtle animation
                    $btn.addClass('scale-110');
                    setTimeout(() => {
                        $btn.removeClass('scale-110');
                    }, 300);
                },

                loginRequired: function(event) {
                    event.preventDefault();
                    event.stopPropagation();

                    console.log('=== LOGIN REQUIRED ===');

                    if (typeof toastr !== 'undefined') {
                        toastr.warning('Please login to bookmark');
                    } else {
                        alert('Please login to bookmark');
                    }
                }
            };
        </script>
    @endpush
@endonce