@include('frontend.new_design.layout.partial.new_header')
@include('frontend.layout.partials.navbar')

@if (!empty($page_post) && $page_post->breadcrumb_status == 'on')
    <x-breadcrumb.user-profile-breadcrumb-02 :innerTitle="$page_post->title ?? ''" />
@endif

@yield('content')
@include('frontend.new_design.layout.partial.new_footer')

