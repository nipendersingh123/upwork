<section id="joinus" class="container mx-auto max-w-7xl px-6 py-10 md:py-16 lg:py-28"
         style="padding-top: {{ $padding_top }}px; padding-bottom: {{ $padding_bottom }}px;">
    <div class="container overflow-hidden mx-auto max-w-7xl px-10 py-10 md:py-20 lg:py-28 rounded-lg relative"
         @if($gradient_from && $gradient_to)
             style="background: linear-gradient(to right, {{ $gradient_from }}, {{ $gradient_to }});"
         @elseif($gradient_from)
             style="background-color: {{ $gradient_from }};"
         @else
             style="background-color: #051D17;"
            @endif>

        <div class="absolute inset-0 z-0 pointer-events-none">
            @if($decorative_image)
                @php
                    $decorative_img = get_attachment_image_by_id($decorative_image);
                @endphp
                @if(!empty($decorative_img))
                    <img class="absolute -left-10 bottom-5 opacity-10" src="{{ $decorative_img['img_url'] }}" alt="">
                @else
                    {{-- Fallback if image not found --}}
                    <img class="absolute -left-10 bottom-5" src="{{ asset('assets/images/get-started/arc-2.svg') }}" alt="">
                @endif
            @else
                {{-- Fallback to the original arc-2.svg if admin doesn't upload a decorative image --}}
                <img class="absolute -left-10 bottom-5" src="{{ asset('assets/images/get-started/arc-2.svg') }}" alt="">
            @endif
        </div>

        <div class="text-center relative z-10 flex items-center justify-center flex-col gap-6">
            <h3 class="text-[36px] text-black -mb-4 font-medium animate-on-scroll">{{ $title }}</h3>
            <p class="text-black max-w-[600px] text-center">{{ $subtitle }}</p>
            <a href="{{ $button_link }}"
               class="text-black flex font-medium hover:text-white bg-secondary hover:bg-primary transition-all duration-300 px-4 py-2 rounded-lg border-primary/50 items-center gap-2">
                {{ $button_text }}
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="size-4">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="m4.5 19.5 15-15m0 0H8.25m11.25 0v11.25" />
                </svg>
            </a>
        </div>
    </div>
</section>