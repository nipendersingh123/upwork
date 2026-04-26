@if(isset($relatedProjects) && $relatedProjects->count() > 0)
    <section class="bg-[#F8F9FD] py-10 md:py-16 lg:py-[120px]">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-2xl font-medium text-base-300 mb-6">Related Services</h2>

            <!-- Carousel Wrapper -->
            <div class="relative px-0">
                <!-- Arrow left -->
                <button title="{{ __('Previous') }}" id="relatedPrevBtn"
                        class="absolute -left-4 top-1/2 transform -translate-y-1/2 z-20 w-10 h-10 bg-secondary rounded-full shadow-lg hover:bg-secondary/90 transition-all duration-300 flex items-center justify-center">
                    <i class="fa-solid fa-arrow-left text-white"></i>
                </button>

                <!-- Arrow right -->
                <button title="{{ __('Next') }}" id="relatedNextBtn"
                        class="absolute -right-4 top-1/2 transform -translate-y-1/2 z-20 w-10 h-10 bg-secondary rounded-full shadow-lg hover:bg-secondary/90 transition-all duration-300 flex items-center justify-center">
                    <i class="fa-solid fa-arrow-right text-white"></i>
                </button>

                <!-- Carousel Container -->
                <div class="outer-carousel-container overflow-hidden">
                    <div id="relatedCardsTrack" class="outer-carousel-track flex gap-6 transition-transform duration-500 ease-in-out">
                        @foreach($relatedProjects as $relatedProject)
                            @php
                                $relatedPrice = floatval($relatedProject->basic_discount_charge ?: $relatedProject->basic_regular_charge);

                                // Check if bookmarked
                                if (Auth::guard('web')->check()) {
                                    $bookmark_exists = \App\Models\Bookmark::where('user_id', Auth::guard('web')->user()->id)
                                        ->where('identity', $relatedProject->id)
                                        ->where('is_project_job', 'project')
                                        ->first();

                                    $user_type = Auth::guard('web')->user()->user_type;
                                    $add_route = $user_type == 1 ? route('client.bookmark') : route('freelancer.bookmark');
                                    $remove_route = $user_type == 1 ? route('client.bookmark.remove') : route('freelancer.bookmark.remove');
                                }
                            @endphp
                            <div class="related-service-card bg-white rounded-2xl border overflow-hidden w-full max-w-[390px] flex-shrink-0">
                                <figure class="relative w-full px-4 pt-4">
                                    @php
                                        // Get all media for this project (images AND videos)
                                        $projectMedia = $relatedProject->media ?? [];
                                        $mediaCount = count($projectMedia);
                                        $hasMultipleMedia = $mediaCount > 1;
                                    @endphp
                                    <div class="card-image-carousel relative w-full rounded-lg overflow-hidden h-48">
                                        @if($mediaCount > 0)
                                            <div class="card-image-track">
                                                @foreach($projectMedia as $mediaIndex => $mediaFile)
                                                    @php
                                                        $ext = pathinfo($mediaFile, PATHINFO_EXTENSION);
                                                        $isImage = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'bmp', 'tiff', 'svg', 'webp', 'gif', 'avif']);
                                                    @endphp
                                                    <div class="card-image-slide">
                                                        @if($isImage)
                                                            @if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi']))
                                                                <img src="{{ render_frontend_cloud_image_if_module_exists('project/'.$mediaFile, load_from: $relatedProject->load_from) }}"
                                                                     alt="{{ $relatedProject->title }}"
                                                                     class="w-full h-full object-cover">
                                                            @else
                                                                <img src="{{ asset('assets/uploads/project/'.$mediaFile) }}"
                                                                     alt="{{ $relatedProject->title }}"
                                                                     class="w-full h-full object-cover">
                                                            @endif

                                                        @else
                                                            @php
                                                                $videoSrc = cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi'])
                                                                    ? render_frontend_cloud_image_if_module_exists('project/'.$mediaFile, load_from: $relatedProject->load_from)
                                                                    : asset('assets/uploads/project/' . $mediaFile);
                                                            @endphp
                                                            <video class="project-video"
                                                                   src="{{ $videoSrc }}"
                                                                   muted
                                                                   loop
                                                                   playsinline
                                                                   preload="metadata"
                                                                   controlslist="nodownload nofullscreen noremoteplayback"
                                                                   disablePictureInPicture>
                                                                Your browser does not support the video tag.
                                                            </video>

                                                            <!-- Center Play Button Overlay (before hover) -->
                                                            <div class="video-play-overlay-center">
                                                                <div class="play-button-circle-center">
                                                                    <div class="play-icon-center"></div>
                                                                </div>
                                                            </div>

                                                            <!-- Bottom Left Play Button with Circular Progress -->
                                                            <div class="video-play-progress">
                                                                <svg class="progress-ring" width="40" height="40">
                                                                    <circle class="progress-ring-circle-bg" cx="20" cy="20" r="18"></circle>
                                                                    <circle class="progress-ring-circle" cx="20" cy="20" r="18"
                                                                            stroke-dasharray="113.1" stroke-dashoffset="113.1"></circle>
                                                                </svg>
                                                                <div class="play-button-small">
                                                                    <div class="play-icon-small"></div>
                                                                </div>
                                                            </div>

                                                            <!-- Video Duration -->
                                                            <span class="video-duration" style="display: none;"></span>

                                                            <!-- Volume Control Button -->
                                                            <button class="video-volume-control" type="button" title="Mute/Unmute">
                                                                <!-- Muted Icon (default) -->
                                                                <svg class="volume-icon volume-muted" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                                    <path d="M3.63 3.63a.996.996 0 0 0 0 1.41L7.29 8.7 7 9H4c-.55 0-1 .45-1 1v4c0 .55.45 1 1 1h3l3.29 3.29c.63.63 1.71.18 1.71-.71v-4.17l4.18 4.18c-.49.37-1.02.68-1.6.91-.36.15-.58.53-.58.92 0 .72.73 1.18 1.39.91.8-.33 1.55-.77 2.22-1.31l1.34 1.34a.996.996 0 1 0 1.41-1.41L5.05 3.63c-.39-.39-1.02-.39-1.42 0zM19 12c0 .82-.15 1.61-.41 2.34l1.53 1.53c.56-1.17.88-2.48.88-3.87 0-3.83-2.4-7.11-5.78-8.4-.59-.23-1.22.23-1.22.86v.19c0 .38.25.71.61.85C17.18 6.54 19 9.06 19 12zm-8.71-6.29-.17.17L12 7.76V6.41c0-.89-1.08-1.33-1.71-.7zM16.5 12A4.5 4.5 0 0 0 14 7.97v1.79l2.48 2.48c.01-.08.02-.16.02-.24z"/>
                                                                </svg>
                                                                <!-- Unmuted Icon (hidden by default) -->
                                                                <svg class="volume-icon volume-unmuted" style="display: none;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                                    <path d="M3 10v4c0 .55.45 1 1 1h3l3.29 3.29c.63.63 1.71.18 1.71-.71V6.41c0-.89-1.08-1.34-1.71-.71L7 9H4c-.55 0-1 .45-1 1zm13.5 2A4.5 4.5 0 0 0 14 7.97v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 4.45v.2c0 .38.25.71.6.85C17.18 6.53 19 9.06 19 12s-1.82 5.47-4.4 6.5c-.36.14-.6.47-.6.85v.2c0 .63.63 1.07 1.21.85C18.6 19.11 21 15.84 21 12s-2.4-7.11-5.79-8.4c-.58-.23-1.21.22-1.21.85z"/>
                                                                </svg>
                                                            </button>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>

                                            <!-- Carousel navigation arrows - Show only if multiple media -->
                                            @if($hasMultipleMedia)
                                                <button class="card-left-arrow absolute left-2 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white p-2 rounded-full shadow-lg transition-all duration-300 hidden">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-gray-800">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                                                    </svg>
                                                </button>
                                                <button class="card-right-arrow absolute right-2 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white p-2 rounded-full shadow-lg transition-all duration-300">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-gray-800">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                                    </svg>
                                                </button>

                                                <!-- Carousel pagination dots -->
                                                <div class="card-pagination-container">
                                                    @foreach($projectMedia as $dotIndex => $mediaFile)
                                                        <button class="card-pagination-dot w-2 h-2 rounded-full transition-all {{ $dotIndex === 0 ? 'active bg-white w-4' : 'bg-white/60' }}"
                                                                data-index="{{ $dotIndex }}"
                                                                aria-label="{{ __('Go to slide') }} {{ $dotIndex + 1 }}"></button>
                                                    @endforeach
                                                </div>
                                            @endif
                                        @else
                                            <!-- Fallback: No media -->
                                            <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                                <div class="text-center">
                                                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                    <span class="text-gray-400 text-sm">{{ __('No Media') }}</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Favorite button -->
                                    @if(Auth::guard('web')->check())
                                        @if($bookmark_exists)
                                            {{-- Remove bookmark button --}}
                                            <button type="button"
                                                    aria-label="{{ __('Remove from favorites') }}"
                                                    class="absolute top-6 right-6 p-2 bg-white rounded-full hover:text-red-500 transition-colors z-20 related-bookmark-btn"
                                                    data-bookmark-id="{{ $bookmark_exists->id }}"
                                                    data-identity="{{ $relatedProject->id }}"
                                                    data-type="project"
                                                    data-add-route="{{ $add_route }}"
                                                    data-remove-route="{{ $remove_route }}"
                                                    data-is-bookmarked="true"
                                                    onclick="relatedProjectsBookmarkHandler.toggleBookmark(event, this)"
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
                                                    aria-label="{{ __('Add to favorites') }}"
                                                    class="absolute top-6 right-6 p-2 bg-white rounded-full hover:text-red-500 transition-colors z-20 related-bookmark-btn"
                                                    data-identity="{{ $relatedProject->id }}"
                                                    data-type="project"
                                                    data-add-route="{{ $add_route }}"
                                                    data-remove-route="{{ $remove_route }}"
                                                    data-is-bookmarked="false"
                                                    onclick="relatedProjectsBookmarkHandler.toggleBookmark(event, this)"
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
                                                aria-label="{{ __('Add to favorites (Login required)') }}"
                                                class="absolute top-6 right-6 p-2 bg-white rounded-full hover:text-red-500 transition-colors z-20 related-bookmark-btn"
                                                onclick="relatedProjectsBookmarkHandler.loginRequired(event)"
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
                                    <!-- Sponsored Badge for promoted projects -->
                                    @if(moduleExists('PromoteFreelancer') && $relatedProject->is_pro == 'yes' && $relatedProject->pro_expire_date >= now())
                                        <span class="bg-secondary rounded-md text-white px-2 text-sm py-1 z-10 absolute top-6 left-6">
                                       {{ get_static_option('promoted_badge_text', 'Sponsored') }}
                                       </span>
                                    @endif
                                </figure>
                                <div class="p-4 space-y-3">
                                    <hgroup class="space-y-3">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium text-gray-500">
                                                {{ $relatedProject->project_category->category ?? '' }}
                                            </p>

                                            @php
                                                // Get freelancer level using your helper function - only if module exists
                                                $freelancerLevel = moduleExists('FreelancerLevel') ? freelancer_level($relatedProject->project_creator?->id) : null;
                                                $isPromotedProject = moduleExists('PromoteFreelancer') &&
                                                                    $relatedProject->is_pro == 'yes' &&
                                                                    $relatedProject->pro_expire_date >= now();

                                                // Check if project is new (less than 24 hours old)
                                                $isNewProject = $relatedProject->created_at &&
                                                               $relatedProject->created_at->diffInHours(now()) < 24;
                                            @endphp

                                            @if($isPromotedProject)
                                                {{-- Promoted projects show "Sponsored" badge in image area, not here --}}
                                            @elseif(!empty($freelancerLevel))
                                                <p class="bg-secondary/20 px-2 py-1 rounded-lg text-secondary text-xs font-medium">
                                                    {{ $freelancerLevel }}
                                                </p>
                                            @elseif($isNewProject)
                                                <p class="bg-green-100 px-2 py-1 rounded-lg text-green-700 text-xs font-medium">
                                                    {{ __('New') }}
                                                </p>
                                            @endif
                                        </div>
                                        <a href="{{ route('project.details', ['username' => $relatedProject->project_creator->username ?? 'user', 'slug' => $relatedProject->slug]) }}"
                                           class="block">
                                            <h2 class="text-xl font-medium leading-tight text-gray-800 line-clamp-2 hover:underline">
                                                {{ $relatedProject->title }}
                                            </h2>
                                        </a>
                                    </hgroup>
                                    <div class="flex items-center gap-1 text-yellow-500">
                                        <i class="fa-solid fa-star"></i>
                                        @php
                                            $avgRating = $relatedProject->ratings->avg('rating') ?? 0;
                                            $formattedRating = number_format($avgRating, 1);
                                            $reviewCount = $relatedProject->ratings->count();
                                        @endphp
                                        <span class="text-sm font-semibold text-gray-700">{{ $formattedRating }}</span>
                                        <span class="text-sm text-gray-500">({{ $reviewCount }} {{ $reviewCount == 1 ? 'Review' : 'Reviews' }})</span>
                                    </div>
                                    <footer class="flex items-center justify-between pt-4 border-t border-gray-200">
                                        <div class="flex items-center gap-3">
                                            @if($relatedProject->project_creator && $relatedProject->project_creator->image)
                                                <div class="relative w-10 h-10">
                                                    @if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi']))
                                                        <img src="{{ render_frontend_cloud_image_if_module_exists('profile/'.$relatedProject->project_creator->image, load_from: $relatedProject->project_creator->load_from) }}"
                                                             alt="{{ $relatedProject->project_creator->first_name }}"
                                                             class="w-full h-full rounded-full object-cover">
                                                    @else
                                                        <img src="{{ asset('assets/uploads/profile/' . $relatedProject->project_creator->image) }}"
                                                             alt="{{ $relatedProject->project_creator->first_name }}"
                                                             class="w-full h-full rounded-full object-cover">
                                                    @endif
                                                </div>
                                            @else
                                                <div class="relative w-10 h-10">
                                                    <img src="{{ asset('assets/static/img/author/author.jpg') }}"
                                                         alt="Author"
                                                         class="w-full h-full rounded-full object-cover">
                                                </div>
                                            @endif
                                            <span class="text-sm font-semibold text-gray-700">
                                                {{ $relatedProject->project_creator->first_name ?? 'User' }}
                                            </span>
                                        </div>
                                        <div class="text-right flex items-center gap-1">
                                            <p class="text-xs text-gray-600">Starting at:</p>
                                            @php
                                                $relatedPrice = floatval($relatedProject->basic_discount_charge ?: $relatedProject->basic_regular_charge);
                                            @endphp
                                            <p class="text-lg font-semibold text-gray-800">
                                                {{ float_amount_with_currency_symbol($relatedPrice) }}
                                            </p>
                                        </div>
                                    </footer>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <style>
            /* Video Styling for Related Services */
            .card-image-slide video {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
            }

            .card-image-slide {
                position: relative;
                aspect-ratio: 16/9;
                background: #000;
            }

            .card-image-slide img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            /* Center Play Button Overlay (before hover) */
            .video-play-overlay-center {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                background: rgba(0, 0, 0, 0.3);
                opacity: 1;
                transition: opacity 0.3s ease;
                z-index: 5;
                pointer-events: none;
            }

            .card-image-slide:hover .video-play-overlay-center,
            .card-image-slide.video-playing .video-play-overlay-center {
                opacity: 0;
                visibility: hidden;
            }

            .play-button-circle-center {
                width: 64px;
                height: 64px;
                background: rgba(255, 255, 255, 0.9);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            }

            .play-icon-center {
                width: 0;
                height: 0;
                border-left: 16px solid #000;
                border-top: 10px solid transparent;
                border-bottom: 10px solid transparent;
                margin-left: 4px;
            }

            /* Bottom Left Play Button with Progress */
            .video-play-progress {
                position: absolute;
                bottom: 12px;
                left: 12px;
                width: 40px;
                height: 40px;
                z-index: 6;
                opacity: 0;
                transition: opacity 0.3s ease;
            }

            .card-image-slide:hover .video-play-progress,
            .card-image-slide.video-playing .video-play-progress {
                opacity: 1;
            }

            .progress-ring {
                transform: rotate(-90deg);
            }

            .progress-ring-circle-bg {
                fill: none;
                stroke: rgba(255, 255, 255, 0.3);
                stroke-width: 3;
            }

            .progress-ring-circle {
                fill: none;
                stroke: #fff;
                stroke-width: 3;
                stroke-linecap: round;
                transition: stroke-dashoffset 0.1s linear;
            }

            .play-button-small {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 32px;
                height: 32px;
                background: rgba(0, 0, 0, 0.7);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .play-icon-small {
                width: 0;
                height: 0;
                border-left: 10px solid #fff;
                border-top: 6px solid transparent;
                border-bottom: 6px solid transparent;
                margin-left: 2px;
            }

            /* Video duration badge */
            .video-duration {
                position: absolute;
                bottom: 12px;
                right: 56px;
                background: rgba(0, 0, 0, 0.8);
                color: white;
                padding: 4px 8px;
                border-radius: 4px;
                font-size: 11px;
                font-weight: 600;
                z-index: 6;
                letter-spacing: 0.5px;
            }

            /* Volume Control Button */
            .video-volume-control {
                position: absolute;
                bottom: 12px;
                right: 12px;
                width: 36px;
                height: 36px;
                background: rgba(0, 0, 0, 0.7);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                z-index: 7;
                opacity: 0;
                transition: all 0.3s ease;
            }

            .card-image-slide:hover .video-volume-control,
            .card-image-slide.video-playing .video-volume-control {
                opacity: 1;
            }

            .video-volume-control:hover {
                background: rgba(0, 0, 0, 0.9);
                transform: scale(1.1);
            }

            .video-volume-control svg {
                width: 20px;
                height: 20px;
                fill: #fff;
            }

            /* Hide video controls */
            .card-image-slide video::-webkit-media-controls {
                display: none !important;
            }

            .card-image-slide video::-webkit-media-controls-enclosure {
                display: none !important;
            }

            .card-image-slide video::-webkit-media-controls-panel {
                display: none !important;
            }
        </style>
    </section>

    <script>
        // Simple isolated JavaScript for related services slider
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Initializing related services slider...');

            // Main slider functionality
            const relatedTrack = document.getElementById('relatedCardsTrack');
            const prevBtn = document.getElementById('relatedPrevBtn');
            const nextBtn = document.getElementById('relatedNextBtn');

            if (!relatedTrack) {
                console.log('No related track found');
                return;
            }

            let currentSlide = 0;
            const slides = document.querySelectorAll('.related-service-card');
            const slidesPerView = 3; // Show 3 cards at once
            const maxSlide = Math.max(0, slides.length - slidesPerView);

            function updateSlider() {
                if (!relatedTrack) return;

                const cardWidth = slides[0]?.offsetWidth || 390;
                const gap = 24; // gap-6 = 24px
                const translateX = -currentSlide * (cardWidth + gap);

                relatedTrack.style.transform = `translateX(${translateX}px)`;

                // Hide/show buttons instead of disabling
                if (prevBtn) {
                    prevBtn.style.display = currentSlide === 0 ? 'none' : 'flex';
                }
                if (nextBtn) {
                    nextBtn.style.display = currentSlide >= maxSlide ? 'none' : 'flex';
                }

                console.log('Slider updated to slide:', currentSlide);
            }

            // Previous button
            if (prevBtn) {
                prevBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    if (currentSlide > 0) {
                        currentSlide--;
                        updateSlider();
                    }
                });
            }

            // Next button
            if (nextBtn) {
                nextBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    if (currentSlide < maxSlide) {
                        currentSlide++;
                        updateSlider();
                    }
                });
            }

            // Initialize card image carousels
            document.querySelectorAll('.card-image-carousel').forEach(carousel => {
                const track = carousel.querySelector('.card-image-track');
                const slides = carousel.querySelectorAll('.card-image-slide');
                const leftBtn = carousel.querySelector('.card-left-arrow');
                const rightBtn = carousel.querySelector('.card-right-arrow');
                const dots = carousel.querySelectorAll('.card-pagination-dot');

                if (!track || slides.length <= 1) return;

                let currentImageIndex = 0;
                const totalImages = slides.length;

                function updateImageCarousel() {
                    track.style.transform = `translateX(-${currentImageIndex * 100}%)`;

                    // Update dots
                    dots.forEach((dot, index) => {
                        if (index === currentImageIndex) {
                            dot.classList.add('active', 'bg-white', 'w-4');
                            dot.classList.remove('bg-white/60');
                        } else {
                            dot.classList.remove('active', 'bg-white', 'w-4');
                            dot.classList.add('bg-white/60');
                        }
                    });

                    // Update arrow visibility
                    if (leftBtn) {
                        leftBtn.classList.toggle('hidden', currentImageIndex === 0);
                    }
                    if (rightBtn) {
                        rightBtn.classList.toggle('hidden', currentImageIndex === totalImages - 1);
                    }
                }

                // Left arrow
                if (leftBtn) {
                    leftBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        if (currentImageIndex > 0) {
                            currentImageIndex--;
                            updateImageCarousel();
                        }
                    });
                }

                // Right arrow
                if (rightBtn) {
                    rightBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        if (currentImageIndex < totalImages - 1) {
                            currentImageIndex++;
                            updateImageCarousel();
                        }
                    });
                }

                // Dots
                dots.forEach(dot => {
                    dot.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        const index = parseInt(this.getAttribute('data-index'));
                        if (!isNaN(index)) {
                            currentImageIndex = index;
                            updateImageCarousel();
                        }
                    });
                });

                // Initialize
                updateImageCarousel();
            });

            // Initialize main slider
            updateSlider();

            // Hide arrows if not needed
            if (slides.length <= slidesPerView) {
                if (prevBtn) prevBtn.style.display = 'none';
                if (nextBtn) nextBtn.style.display = 'none';
            }

            // Handle window resize
            let resizeTimeout;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(updateSlider, 250);
            });
        });

        // Related Projects Bookmark Handler
        window.relatedProjectsBookmarkHandler = {
            isProcessing: false,

            toggleBookmark: function(event, button) {
                event.preventDefault();
                event.stopPropagation();

                if (this.isProcessing) return;

                var $btn = $(button);
                var isBookmarked = $btn.data('is-bookmarked') === true;
                var identity = $btn.data('identity');
                var type = $btn.data('type');
                var addRoute = $btn.data('add-route');
                var removeRoute = $btn.data('remove-route');

                // Determine which action to take
                var route = isBookmarked ? removeRoute : addRoute;
                var action = isBookmarked ? 'remove' : 'add';

                console.log('=== RELATED PROJECT BOOKMARK TOGGLE ===');
                console.log('Action:', action);
                console.log('Project ID:', identity);
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
                    $btn.data('is-bookmarked', true);
                    $btn.data('bookmark-id', bookmarkId);
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
                    $btn.data('is-bookmarked', false);
                    $btn.removeData('bookmark-id');
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

        // Initialize related services video players
        function initializeRelatedVideoPlayers() {
            const videoContainers = document.querySelectorAll('.card-image-slide');

            videoContainers.forEach(container => {
                const video = container.querySelector('video');
                const durationBadge = container.querySelector('.video-duration');
                const progressCircle = container.querySelector('.progress-ring-circle');
                const volumeButton = container.querySelector('.video-volume-control');
                const volumeMutedIcon = container.querySelector('.volume-muted');
                const volumeUnmutedIcon = container.querySelector('.volume-unmuted');

                if (!video) return;

                const radius = 18;
                const circumference = 2 * Math.PI * radius;

                // Get video duration and display it
                video.addEventListener('loadedmetadata', function() {
                    if (durationBadge) {
                        const duration = Math.floor(video.duration);
                        const minutes = Math.floor(duration / 60);
                        const seconds = duration % 60;
                        durationBadge.textContent = minutes + ':' + seconds.toString().padStart(2, '0');
                        durationBadge.style.display = 'block';
                    }
                });

                // Update circular progress bar
                video.addEventListener('timeupdate', function() {
                    if (progressCircle && video.duration) {
                        const progress = (video.currentTime / video.duration) * 100;
                        const offset = circumference - (progress / 100) * circumference;
                        progressCircle.style.strokeDashoffset = offset;
                    }
                });

                // Volume button click handler
                if (volumeButton) {
                    volumeButton.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();

                        if (video.muted) {
                            video.muted = false;
                            volumeMutedIcon.style.display = 'none';
                            volumeUnmutedIcon.style.display = 'block';
                        } else {
                            video.muted = true;
                            volumeMutedIcon.style.display = 'block';
                            volumeUnmutedIcon.style.display = 'none';
                        }
                    });
                }

                // Hover to play
                container.addEventListener('mouseenter', function() {
                    if (video && video.paused) {
                        container.classList.add('video-playing');
                        video.play().catch(err => console.log('Play error:', err));
                    }
                });

                // Stop on mouse leave
                container.addEventListener('mouseleave', function() {
                    if (video && !video.paused) {
                        container.classList.remove('video-playing');
                        video.pause();
                        video.currentTime = 0;
                        if (progressCircle) {
                            progressCircle.style.strokeDashoffset = circumference;
                        }
                        // Reset to muted when leaving
                        video.muted = true;
                        if (volumeMutedIcon && volumeUnmutedIcon) {
                            volumeMutedIcon.style.display = 'block';
                            volumeUnmutedIcon.style.display = 'none';
                        }
                    }
                });

                // Pause other videos when one plays
                video.addEventListener('play', function() {
                    document.querySelectorAll('.card-image-slide video').forEach(otherVideo => {
                        if (otherVideo !== video && !otherVideo.paused) {
                            otherVideo.pause();
                            otherVideo.currentTime = 0;
                            const otherContainer = otherVideo.closest('.card-image-slide');
                            if (otherContainer) {
                                otherContainer.classList.remove('video-playing');
                                const otherProgress = otherContainer.querySelector('.progress-ring-circle');
                                if (otherProgress) {
                                    otherProgress.style.strokeDashoffset = circumference;
                                }
                            }
                        }
                    });
                });

                // Initialize progress circle
                if (progressCircle) {
                    progressCircle.style.strokeDasharray = circumference;
                    progressCircle.style.strokeDashoffset = circumference;
                }
            });
        }

        // Call the video initialization after DOM is ready
        initializeRelatedVideoPlayers();

    </script>
@endif