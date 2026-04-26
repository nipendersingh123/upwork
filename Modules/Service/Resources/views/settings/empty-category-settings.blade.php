@extends('backend.layout.master')
@section('title', __('Empty Category Settings'))
@section('style')
    <x-media.css/>
@endsection
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-6">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item__flex">
                            <h4 class="customMarkup__single__title">{{ __('Empty Category Settings') }}</h4>
                        </div>
                        <x-validation.error />
                        <div class="customMarkup__single__inner mt-4">
                            <x-notice.general-notice 
                                :class="'mt-5'" 
                                :description="__('Notice: Enable this option to hide categories that don\'t have any related projects, jobs, or talents on the frontend.')" 
                            />
                            <form action="{{route('admin.category.empty.settings')}}" method="post">
                                @csrf
                                <div class="single-input my-5">
                                    <label class="label-title">{{ __('Hide Empty Categories') }}</label>
                                    <select name="hide_empty_categories" class="form-control">
                                        <option value="">{{ __('Select Option') }}</option>
                                        <option value="on" @if(get_static_option('hide_empty_categories') == 'on') selected @endif>{{ __('Enable') }}</option>
                                        <option value="off" @if(get_static_option('hide_empty_categories') == 'off') selected @endif>{{ __('Disable') }}</option>
                                    </select>
                                    <small class="form-text text-muted">
                                        {{ __('When enabled, categories without any projects, jobs, or talents will not be displayed on the frontend.') }}
                                    </small>
                                </div>
                                <x-btn.submit :title="__('Update Settings')" :class="'btn btn-primary mt-4 pr-4 pl-4'" />
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-media.markup/>
@endsection