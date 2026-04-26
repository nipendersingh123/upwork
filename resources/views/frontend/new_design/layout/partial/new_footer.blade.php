@php
    $footer_variant = !is_null(get_footer_style()) ? get_footer_style() : '04';
@endphp
@include('frontend.layout.partials.footer-variant.footer-'.$footer_variant)

<script src="{{ asset('assets/frontend/new_design/assets/js/navbar.js') }}"></script>
<!-- new design js -->
{{-- Font Awesome --}}
<script src="https://kit.fontawesome.com/c4e937c9d9.js" crossorigin="anonymous"></script>

{{-- All Page's JS --}}
<script src="{{ asset('assets/frontend/new_design/assets/js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/frontend/new_design/assets/js/about_us.js') }}"></script>
<script src="{{ asset('assets/frontend/new_design/assets/js/all_categories.js') }}"></script>
<script src="{{ asset('assets/frontend/new_design/assets/js/job_details.js') }}"></script>
<script src="{{ asset('assets/frontend/new_design/assets/js/animation.js') }}"></script>
<script src="{{ asset('assets/frontend/new_design/assets/js/blog.js') }}"></script>
<script src="{{ asset('assets/frontend/new_design/assets/js/browser_service_by_category.js') }}"></script>
<script src="{{ asset('assets/frontend/new_design/assets/js/gsap.min.js') }}"></script>
<script src="{{ asset('assets/frontend/new_design/assets/js/header.js') }}"></script>
<script src="{{ asset('assets/frontend/new_design/assets/js/ScrollTrigger.min.js') }}"></script>
<script src="{{ asset('assets/frontend/new_design/assets/js/service_card.js') }}"></script>
<script src="{{ asset('assets/frontend/new_design/assets/js/trending_service.js') }}"></script>
<script src="{{ asset('assets/frontend/new_design/assets/js/checkout.js') }}"></script>
<script src="{{ asset('assets/frontend/new_design/assets/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/frontend/new_design/assets/js/service_details.js') }}"></script>
<script src="{{ asset('assets/frontend/new_design/assets/js/talent_details.js') }}"></script>
<script src="{{ asset('assets/frontend/new_design/assets/js/login.js') }}"></script>
<script src="{{ asset('assets/frontend/new_design/assets/js/sign_up.js') }}"></script>
<script src="{{ asset('assets/frontend/new_design/assets/js/sign_form.js') }}"></script>
<script src="{{ asset('assets/frontend/new_design/assets/js/varify.js') }}"></script>
<script src="{{ asset('assets/frontend/js/newsletter.js') }}"></script>
<script src="{{ asset('assets/frontend/new_design/assets/js/main.js') }}"></script>




<!-- Toastr js -->
@if(get_static_option('home_page_animation') != 'disable')
    <!-- Wow Js -->
    <script>new WOW().init();</script>
@endif

<script src="{{ asset('assets/common/js/toastr.min.js') }}"></script>
{!! Toastr::message() !!}
<!-- global ajax setup -->
<script> $.ajaxSetup({headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'} }) </script>

@if(moduleExists('HourlyJob'))
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
@endif

<x-chat::livechat-js />

<!-- End Of new-design js -->

@yield('script')

@stack('page_scripts')

@if(!empty( get_static_option('site_third_party_tracking_code')))
    {!! get_static_option('site_third_party_tracking_code') !!}
@endif
{!! renderBodyEndHooks() !!}
@include('frontend.layout.partials.gdpr-cookie')
</body>
</html>