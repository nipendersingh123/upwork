@extends('backend.layout.master')
@section('title', __('Job Country Restriction Settings'))
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-6">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item__flex">
                            <h4 class="customMarkup__single__title">{{ __('Job Country Restriction Settings') }}</h4>
                        </div>

                        <x-validation.error />

                        <div class="customMarkup__single__inner mt-4">
                            <x-notice.general-notice :description="__('Notice: Control how country restrictions apply to jobs posted by clients.')" :description1="__(
                                'If disabled, clients cannot set any country restrictions and jobs are global by default.',
                            )" :description2="__(
                                'If enabled, you can also choose whether restrictions apply at VIEW level or only APPLY level.',
                            )" />

                            <form action="{{ route('admin.job.country.restriction.settings') }}" method="POST">
                                @csrf

                                <!-- Enable/Disable Country Restrictions -->
                                <div class="switch mt-4">
                                    <label
                                        class="label-title mt-3"><strong>{{ __('Enable Country Restrictions') }}</strong></label>
                                    <input class="custom-switch" type="checkbox" id="job_country_restriction_enabled"
                                        name="job_country_restriction_enabled"
                                        @if (get_static_option('job_country_restriction_enabled', 0)) checked @endif>
                                    <label class="switch-label"
                                        for="job_country_restriction_enabled">{{ __('Allow clients to set country restrictions for jobs') }}</label>
                                    <small>{{ __('Toggle ON to let clients choose allowed/excluded countries when posting jobs.') }}</small>
                                </div>

                                <!-- Restrict at View Level -->
                                <div class="switch mt-4">
                                    <label
                                        class="label-title mt-3"><strong>{{ __('Restrict at View Level') }}</strong></label>
                                    <input class="custom-switch" type="checkbox" id="job_country_view_level_enabled"
                                        name="job_country_view_level_enabled"
                                        @if (get_static_option('job_country_view_level_enabled', 0)) checked @endif>
                                    <label class="switch-label"
                                        for="job_country_view_level_enabled">{{ __('Hide jobs from freelancers in restricted countries') }}</label>
                                    <small>{{ __('If OFF, restrictions will apply only when freelancers try to apply (jobs remain visible).') }}</small>
                                </div>

                                @can('job-country-restriction-settings')
                                    <x-btn.submit :title="__('Update')" :class="'btn btn-primary mt-4 pr-4 pl-4'" />
                                @endcan
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
