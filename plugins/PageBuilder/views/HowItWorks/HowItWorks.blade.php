<!-- How It Works Section -->
<section id="subscription" class="bg-[#F8F9FD]" style="padding-top: {{ $padding_top }}px; padding-bottom: {{ $padding_bottom }}px; background-color: {{ $section_bg }}">
    <div class="container mx-auto max-w-7xl px-6 py-10 md:py-16 lg:py-28">

        <!-- Section Title -->
        @if(!empty($title))
            <h2 class="mb-[40px] text-4xl text-gray-800 font-medium text-center animate-on-scroll whitespace-pre-line">
                {!! nl2br(e($title)) !!}
            </h2>
        @endif

        <!-- Cards Grid -->
        @if(!empty($steps_data) && isset($steps_data['title_']))
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                @foreach($steps_data['title_'] ?? [] as $key => $step_title)
                    <div class="bg-white rounded-xl border border-gray-200 p-8 text-center hover:shadow-lg transition-shadow" style="background-color: {{ $card_bg }}">

                        <!-- Icon/Image -->
                        @if(!empty($steps_data['icon_'][$key]))
                            <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6" style="background-color: {{ $icon_bg }}">
                                {!! render_image_markup_by_attachment_id($steps_data['icon_'][$key], '', 'w-10 h-10 object-contain') !!}
                            </div>
                        @endif

                        <!-- Step Title -->
                        @if(!empty($step_title))
                            <h3 class="text-xl font-medium text-base-300 mb-2 whitespace-pre-line">
                                {!! nl2br(e($step_title)) !!}
                            </h3>
                        @endif

                        <!-- Step Description -->
                        @if(!empty($steps_data['description_'][$key]))
                            <p class="text-base-400 text-sm leading-relaxed whitespace-pre-line">
                                {!! nl2br(e($steps_data['description_'][$key])) !!}
                            </p>
                        @endif
                    </div>
                @endforeach

            </div>
        @endif

    </div>
</section>