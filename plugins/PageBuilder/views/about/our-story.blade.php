<!-- Our Story/Vision/Values Section -->
<section class="w-full px-6" style="padding-top: {{ $padding_top }}px; padding-bottom: {{ $padding_bottom }}px; background-color: {{ $section_bg }}">

    @if(!empty($story_sections['section_title_']))
        @foreach($story_sections['section_title_'] as $index => $section_title)
            @php
                $image = $story_sections['section_image_'][$index] ?? '';
                $image_position = $story_sections['image_position_'][$index] ?? 'right';
                $section_content = $story_sections['section_content_'][$index] ?? '';

                // Determine order classes
                $content_order = ($image_position === 'left') ? 'lg:order-2' : '';
                $image_order = ($image_position === 'left') ? 'lg:order-1' : '';
                $is_last = ($index === count($story_sections['section_title_']) - 1);
            @endphp

            <div class="max-w-7xl mx-auto {{ !$is_last ? 'mb-16 md:mb-24 lg:mb-32' : '' }}"
                 style="{{ !$is_last ? 'margin-bottom: ' . $section_spacing . 'px' : '' }}">

                <!-- Main Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">

                    <!-- Left/Right Content -->
                    <div class="flex flex-col gap-10 {{ $content_order }}">
                        <!-- Heading -->
                        @if(!empty($section_title))
                            <h2 class="text-3xl lg:text-4xl font-medium">
                                {{ $section_title }}
                            </h2>
                        @endif

                        <!-- Section Content -->
                        @if(!empty($section_content))
                            <div class="our-story-content">
                                {!! $section_content !!}
                            </div>
                        @endif
                    </div>

                    <!-- Image Content -->
                    @if(!empty($image))
                        <div class="flex justify-center lg:justify-end {{ $image_order }}">
                            <div class="w-full max-w-md lg:max-w-full rounded-3xl overflow-hidden shadow-xl">
                                {!! render_image_markup_by_attachment_id($image, '', 'w-full h-auto object-contain') !!}
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        @endforeach
    @endif

</section>