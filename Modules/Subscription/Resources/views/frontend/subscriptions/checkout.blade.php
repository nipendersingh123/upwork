@extends('frontend.new_design.layout.new_master')
@section('site_title', __('Checkout'))
@section('meta_title'){{ __('Subscription Checkout') }}@endsection

@section('content')
    <x-breadcrumb.user-profile-breadcrumb-02 :innerTitle="__('Checkout')" />
    <main class="py-10 md:py-16 lg:py-[120px]">

        <section class="container mx-auto max-w-7xl px-6">
            <h1 class="text-2xl font-medium text-base-300 mb-6">{{ __('Payment method') }}</h1>

            <!-- Notice -->
            @if(Auth::guard('web')->check())
                @if(Auth::guard('web')->user()->user_type == 1 && Session::get('user_role') != 'freelancer')
                    <div class="bg-[#FA8C00]/10 p-4 rounded-lg mb-8 text-sm text-base-400">
                        <span class="font-medium">{{ __('Notice:') }}</span> {{ __('Please login as a freelancer to buy a subscription.') }}
                    </div>
                @endif
            @endif

            <form action="{{ route('subscriptions.buy') }}" method="post" enctype="multipart/form-data" id="checkout-form">
                @csrf
                <input type="hidden" name="subscription_id" id="subscription_id" value="{{ $subscription->id }}">
                <!-- FIXED: Add hidden input for free plans -->
                <input type="hidden" name="selected_payment_gateway" id="order_from_user_wallet" value="{{ $subscription->price == 0 ? 'free' : '' }}">

                <div class="flex flex-col lg:flex-row gap-8">
                    <!-- Left Column -->
                    <div class="flex-1">
                        <div class="border border-[#D9D9D9] rounded-2xl p-6 bg-white">
                            <h2 class="text-xl font-medium text-base-300 mb-6">{{ __('Choose a payment method') }}</h2>

                            @if($subscription->price > 0)
                                <!-- Wallet Balance -->
                                @if(Auth::check() && Auth::user()->user_wallet?->balance > 0 && $subscription->price > 0)
                                    <div class="mb-8">
                                        <label class="flex items-center gap-3 cursor-pointer mb-2 group">
                                            <div class="relative flex items-center">
                                                <input type="checkbox"
                                                       id="wallet_selected_payment_gateway"
                                                       class="wallet_selected_payment_gateway size-5 appearance-none border border-gray-400 rounded checked:bg-primary checked:border-primary
                       checked:bg-[url('{{ asset("assets/frontend/new_design/assets/images/icons/check.svg") }}')] bg-no-repeat bg-center text-white" />
                                            </div>
                                            <span class="text-base-400 font-medium">{{ __('Use Wallet balance') }}</span>
                                        </label>
                                        <p class="text-base-400">{{ __('Wallet Balance:') }}
                                            <span class="font-semibold text-base-300 main-balance">{{ float_amount_with_currency_symbol(Auth::user()->user_wallet?->balance) }}</span>
                                        </p>
                                        <span class="display_balance block mt-2"></span>
                                        <span class="deposit_link block mt-2"></span>
                                    </div>
                                @endif

                                <!-- Payment Gateway Options -->
                                <div class="mb-8">
                                    <div class="payment-gateway-wrapper">
                                        {!! \App\Helper\PaymentGatewayList::renderPaymentGatewayForForm2(false) !!}
                                    </div>
                                </div>
                            @else
                                <!-- Free Plan Message -->
                                <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg mb-6">
                                    <div class="flex items-center gap-3">
                                        <i class="fa-solid fa-circle-check text-blue-600 text-xl"></i>
                                        <p class="text-blue-800 font-medium">{{ __('This is a free plan. Click Confirm to activate.') }}</p>
                                    </div>
                                </div>
                            @endif

                            <hr class="border-[#D9D9D9] mb-8">

                            <!-- Buttons -->
                            <div class="flex gap-4">
                                <a href="{{ route('subscriptions.all') }}"
                                   class="flex-1 border border-gray-300 text-base-400 py-3 rounded-lg font-medium hover:bg-gray-50 transition text-center">
                                    {{ __('Cancel') }}
                                </a>

                                @if(Auth::guard('web')->check())
                                    @if(Auth::guard('web')->user()->user_type == 2 ||
                                        (Auth::guard('web')->user()->user_type == 1 && Session::get('user_role') == 'freelancer') ||
                                        (Auth::guard('web')->user()->user_type == 1 && get_static_option('subscription_chat_enable_disable') == 'disable'))
                                        <button type="submit"
                                                id="confirm_buy_subscription_load_spinner"
                                                class="flex-1 bg-primary text-white py-3 rounded-lg font-medium hover:bg-primary/90 transition">
                                            {{ $subscription->price > 0 ? __('Confirm & Pay') : __('Confirm') }} <span id="buy_subscription_load_spinner"></span>
                                        </button>
                                    @endif
                                @else
                                    <button type="button"
                                            id="openLoginModalForSubscription"
                                            class="openLoginModalForSubscription flex-1 bg-primary text-white py-3 rounded-lg font-medium hover:bg-primary/90 transition text-center">
                                        {{ __('Login to Continue') }}
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Right Column (Summary) -->
                    <div class="lg:w-[380px]">
                        <div class="border border-[#D9D9D9] rounded-2xl p-6 bg-white sticky top-[100px]">
                            <!-- Plan Info -->
                            <div class="mb-6">
                                <h3 class="text-xl font-medium text-base-300 mb-2">{{ $subscription->title ?? '' }}</h3>
                                <div class="flex items-baseline mb-4">
                                    <span class="text-3xl font-semibold text-base-300">{{ float_amount_with_currency_symbol($subscription->price ?? 0) }}</span>
                                    <span class="text-gray-500 ml-2">/{{ ucfirst($subscription->subscription_type?->type ?? 'month') }}</span>
                                </div>
                                <p class="text-gray-600">
                                    {{ $subscription->limit ?? '' }} {{ __('Connects') }}
                                </p>
                            </div>

                            <!-- Features -->
                            @if($subscription->features && count($subscription->features) > 0)
                                <div class="mb-6">
                                    <h4 class="font-semibold text-base-300 mb-3">{{ __('Plan includes:') }}</h4>
                                    <ul class="space-y-2">
                                        @foreach($subscription->features as $feature)
                                            <li class="flex items-start gap-2 text-sm text-base-400">
                                                <i class="fa-solid fa-check text-base-300 mt-1"></i>
                                                <span>{{ $feature->feature ?? '' }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <hr class="border-[#D9D9D9] my-4">

                            <!-- Total -->
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-medium text-base-300">{{ __('Total') }}</span>
                                <span class="font-medium text-base-300" id="subscription_price">{{ float_amount_with_currency_symbol($subscription->price ?? 0) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </main>
@endsection

@section('script')
    <script>
        (function($){
            "use strict";

            $(document).ready(function(){
                // Handle "Login to Continue" button click for subscription checkout
                $('#openLoginModalForSubscription').on('click', function(e) {
                    e.preventDefault();

                    // Open the login modal
                    $('#loginModal').removeClass('hidden').addClass('flex');
                    $('body').addClass('overflow-hidden');
                });

                let site_default_currency_symbol = '{{ site_currency_symbol() }}';
                let subscription_price = {{ $subscription->price ?? 0 }};
                let balance = {{ Auth::check() ? Auth::user()->user_wallet?->balance : 0 }};
                let default_gateway = '{{ get_static_option("site_default_payment_gateway") }}';

                // FIXED: For free plans, ensure the hidden field is set
                if(subscription_price == 0) {
                    $('#order_from_user_wallet').val('free');
                    console.log('Free plan detected - gateway set to: free');
                }

                // Reset all payment gateways to default state on page load
                $('.payment_getway_image ul li').each(function(){
                    if($(this).data('is-default') === true && subscription_price > 0 && default_gateway) {
                        // This is the default gateway
                        $(this).removeClass('border-[#D9D9D9]').addClass('border-secondary border-2 selected active');
                    } else {
                        // Not default, ensure it has default styling
                        $(this).removeClass('border-secondary border-2 selected active').addClass('border-[#D9D9D9]');
                    }
                });

                @php
                    $user_type = '';
                    if(Auth::check()){
                        $user_type = Auth::user()->user_type == 1 ? 'client' : 'freelancer';
                        $user_type = route($user_type .'.'. 'wallet.history');
                    }
                @endphp

                // Check wallet balance on page load
                if(subscription_price > balance && balance > 0){
                    $('.display_balance').html('<span class="text-danger">{{__('Wallet Balance Shortage:')}}'+ site_default_currency_symbol + (subscription_price-balance) +'</span>');
                    $('.deposit_link').html('<a href="{{ $user_type }}" target="_blank" class="text-primary hover:underline">{{ __('Deposit')}}</a>');
                }

                // Set default gateway if it's a paid plan (only on page load)
                if(subscription_price > 0 && default_gateway) {
                    $('#order_from_user_wallet').val(default_gateway);
                    $('.payment-gateway-wrapper ul li[data-gateway="'+ default_gateway +'"]').addClass('active selected');
                }

                // Payment gateway click handler
                $(document).on('click', '.payment_getway_image ul li', function(e){

                    // Uncheck wallet
                    $('.wallet_selected_payment_gateway').prop("checked", false);

                    // Remove active/selected styling from all, add to clicked
                    $('.payment_getway_image ul li').removeClass('active selected border-secondary border-2').addClass('border-[#D9D9D9]');
                    $(this).removeClass('border-[#D9D9D9]').addClass('active selected border-secondary border-2');

                    let gateway = $(this).data('gateway');
                    $('#order_from_user_wallet').val(gateway);

                    // Show/hide kineticpay fields
                    $('.kinetic_payment_show_hide').hide();
                    if(gateway == 'kineticpay'){
                        $('.kinetic_payment_show_hide').show();
                    }

                    // Show/hide manual payment fields
                    $('.payment_gateway_extra_field_information_wrap > div').hide();
                    $('.payment_gateway_extra_field_information_wrap div.'+gateway+'_gateway_extra_field').show();
                });

                // Handle wallet checkbox change
                $(document).on('change', '#wallet_selected_payment_gateway, .wallet_selected_payment_gateway', function(){

                    let isChecked = $(this).is(':checked');

                    if(isChecked){
                        $('#order_from_user_wallet').val('wallet');
                        $('.payment_getway_image ul li').removeClass('active selected border-secondary border-2').addClass('border-[#D9D9D9]');

                    } else {
                        // FIXED: When unchecking wallet, restore default gateway or free
                        if(subscription_price == 0) {
                            $('#order_from_user_wallet').val('free');
                        } else if(default_gateway) {
                            $('#order_from_user_wallet').val(default_gateway);
                            $('.payment-gateway-wrapper ul li[data-gateway="'+ default_gateway +'"]').addClass('active selected border-secondary border-2').removeClass('border-[#D9D9D9]');
                        } else {
                            $('#order_from_user_wallet').val('');
                        }

                    }
                });

                // FIXED: Form submission validation
                $(document).on('click','#confirm_buy_subscription_load_spinner',function(e){
                    let selected_gateway = $('#order_from_user_wallet').val();

                    console.log('Form submission - Selected gateway:', selected_gateway);
                    console.log('Subscription price:', subscription_price);

                    // FIXED: Validate payment method selection ONLY for paid plans
                    if(subscription_price > 0 && (!selected_gateway || selected_gateway === '')) {
                        e.preventDefault();
                        toastr_warning_js("{{__('Please select a payment method')}}")
                        return false;
                    }

                    // For free plans, ensure gateway is set to 'free'
                    if(subscription_price == 0) {
                        $('#order_from_user_wallet').val('free');
                    }

                    // Manual payment image validation
                    let manual_payment = $('#order_from_user_wallet').val();
                    if(manual_payment == 'manual_payment') {
                        let manual_payment_image = $('input[name="manual_payment_image"]').val();
                        if(manual_payment_image == '') {
                            e.preventDefault();
                            toastr_warning_js("{{__('Image field is required')}}")
                            return false;
                        }
                    }

                    $('#buy_subscription_load_spinner').html('<i class="fas fa-spinner fa-pulse"></i>');
                    setTimeout(function () {
                        $('#buy_subscription_load_spinner').html('');
                    }, 10000);
                });
            });
        }(jQuery));
    </script>
@endsection