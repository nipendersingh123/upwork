<!DOCTYPE html>
<html lang="{{get_user_lang()}}" dir="{{get_user_lang_direction()}}">

<head>
    {!! renderHeadStartHooks() !!}
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- favicon -->
    @php
        $site_favicon = get_attachment_image_by_id(get_static_option('site_favicon'),"full",false);
    @endphp
    @if (!empty($site_favicon))
        <link rel="icon" href="{{$site_favicon['img_url'] ?? ''}}" sizes="40x40" type="icon/png">
    @endif

    {!! load_google_fonts() !!}

    <!-- TAILWIND CSS FILES -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/new_design/assets/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/new_design/assets/css/all_job.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/new_design/assets/css/tailwindmain.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/new_design/assets/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/new_design/assets/css/tablar-icon.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/new_design/assets/css/style.css') }}">
    <!-- Toastr Css -->
    <link rel="stylesheet" href="{{ asset('assets/common/css/toastr.min.css') }}">
    <link rel="stylesheet" href="{{asset('assets/common/css/jquery.ihavecookies.css')}}">


    <!-- TAILWIND JS FILES -->
    <script src="{{ asset('assets/frontend/new_design/assets/js/tailwind.js') }}"></script>
    <script src="{{ asset('assets/frontend/new_design/assets/js/tailwind-config.js') }}"></script>

    <!-- jQuery (if needed for your components) -->
    <script src="{{ asset('assets/frontend/new_design/assets/js/jquery-3.7.1.min.js') }}"></script>

    @include('frontend.layout.partials.root-style')
    @yield('style')

    <!-- Page Title & Meta Data -->
    @if(request()->routeIs('homepage'))
        <title>{{get_static_option('site_title')}} - {{get_static_option('site_tag_line')}}</title>
        {!! render_site_meta_home() !!}
    @elseif( request()->routeIs('frontend.dynamic.page') && $page_type === 'page' )
        {!! render_site_title(optional($page_post)->title ) !!}
        {!! render_site_meta(optional($page_post)->title ) !!}
    @else
        @if(View::hasSection('page-meta-data'))
            @yield('page-meta-data')
        @else
            <title>
                @yield('site_title') | {{get_static_option('site_title')}}
            </title>
        @endif
        @if(View::hasSection('meta_title'))
            <meta name="title" content="@yield('meta_title') | {{get_static_option('site_title')}}">
        @endif
        @if(View::hasSection('meta_description'))
            <meta name="description" content="@yield('meta_description')">
        @endif
    @endif

    @php

        $custom_css = '';
        if (file_exists('assets/frontend/css/dynamic-style.css')) {
            $custom_css = file_get_contents('assets/frontend/css/dynamic-style.css');
        }
    @endphp
    @if(!empty($custom_css))
        <link rel="stylesheet" href="{{asset('assets/frontend/css/dynamic-style.css')}}">
    @endif
    {!! renderHeadEndHooks() !!}
</head>

<body>
{!! renderBodyStartHooks() !!}