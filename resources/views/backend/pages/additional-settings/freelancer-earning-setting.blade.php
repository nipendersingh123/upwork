@extends('backend.layout.master')
@section('title', __('Freelancer Earnings Display Settings'))
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
                            <h4 class="customMarkup__single__title">{{ __('Freelancer Earnings: Show/Hide Settings') }}</h4>
                        </div>
                        <x-validation.error />
                        <div class="customMarkup__single__inner mt-4">
                            <x-notice.general-notice :class="'mt-5'" :description="__('Notice: To prevent users from showing or hiding their earnings, keep this disabled. To allow users to toggle the visibility of their earnings, enable this option.')" />
                            <form action="{{route('admin.user.earning.toggle.settings')}}" method="post">
                                @csrf
                                <div class="single-input my-5">
                                    <label class="label-title">{{ __('Enable/Disable Settings') }}</label>
                                    <select name="user_earning_toggle" class="form-control">
                                        <option value="">{{ __('Select Option') }}</option>
                                        <option value="enable" @if(get_static_option('user_earning_toggle') == 'enable') selected @endif>{{ __('Enable') }}</option>
                                        <option value="disable" @if(get_static_option('user_earning_toggle') == 'disable') selected @endif>{{ __('Disable') }}</option>
                                    </select>
                                </div>
                                <x-btn.submit :title="__('Update')" :class="'btn btn-primary mt-4 pr-4 pl-4'" />
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-media.markup/>
@endsection