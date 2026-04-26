<!-- Hire the best -->
<section class="py-10 px-6 md:py-16 lg:py-28" style="background-color: {{ $background_color }}; padding-top: {{ $padding_top }}px; padding-bottom: {{ $padding_bottom }}px;">
    <div class="container mx-auto max-w-7xl px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Left Content -->
            <div class="space-y-8">
                @if(!empty($title))
                    <h2 class="text-3xl md:text-4xl font-medium leading-tight animate-on-scroll">
                        {!! $title !!}
                    </h2>
                @endif

                @if(!empty($feature_cards))
                    <div class="space-y-8">
                        @foreach($feature_cards['card_title_'] ?? [] as $key => $cardTitle)
                            <!-- Card -->
                            <div class="flex items-start gap-4">
                                @if(!empty($feature_cards['icon_'][$key]))
                                    <div class="flex-shrink-0 w-12 h-12 flex items-center justify-center">
                                        {!! render_image_markup_by_attachment_id($feature_cards['icon_'][$key], '', 'w-full h-full object-contain') !!}
                                    </div>
                                @else
                                    <div class="flex-shrink-0 w-12 h-12 flex items-center justify-center bg-gray-100 rounded-lg">
                                        <i class="fas fa-check text-gray-600"></i>
                                    </div>
                                @endif
                                <div>
                                    @if(!empty($cardTitle))
                                        <h3 class="text-xl font-medium mb-1">{{ $cardTitle }}</h3>
                                    @endif
                                    @if(!empty($feature_cards['card_description_'][$key]))
                                        <p class="text-gray-600">{{ $feature_cards['card_description_'][$key] }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Right Image Section -->
            @if(!empty($right_image))
                <div class="flex justify-center items-center">
                    <div class="overflow-hidden rounded-xl shadow-xl max-w-lg">
                        {!! render_image_markup_by_attachment_id($right_image, '', 'w-full h-auto') !!}
                    </div>
                </div>
            @else
                <!-- Default placeholder image -->
                <div class="flex justify-center items-center">
                    <div class="w-full h-64 bg-gray-200 rounded-xl shadow-xl max-w-lg flex items-center justify-center">
                        <span class="text-gray-500">Image will appear here</span>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>