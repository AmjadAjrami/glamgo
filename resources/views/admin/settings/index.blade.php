@extends('admin.layouts.app')

@section('main-content')
    <style>
        .alert.alert-success{
            padding: 10px !important;
            color: #23a15b !important;
        }
    </style>

    <div class="row">
        <div class="col-12">
            @include('flash::message')
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">@lang('common.settings')</h4>
                </div>
                <div class="card-body">
                    <form data-reset="true" method="POST" class="row gy-1 pt-75" enctype="multipart/form-data" action="{{ route('settings.update') }}">
                        @csrf
                        @method('PUT')
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="email">{{ __('common.email') }}</label>
                            <input type="text" id="email" name="settings[email]" class="form-control"
                                   placeholder="{{ __('common.email') }}" value="{{ $email != null ? $email->value : '' }}"/>
                            @if($errors->has('email'))
                                <div
                                    style="color: red">{{ $errors->first('email') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="mobile">{{ __('common.mobile') }}</label>
                            <input type="text" id="mobile" name="settings[mobile]" class="form-control"
                                   placeholder="{{ __('common.mobile') }}" value="{{ $mobile != null ? $mobile->value : '' }}"/>
                            @if($errors->has('mobile'))
                                <div
                                    style="color: red">{{ $errors->first('mobile') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="website">{{ __('common.website') }}</label>
                            <input type="text" id="website" name="settings[website]" class="form-control"
                                   placeholder="{{ __('common.website') }}" value="{{ $website != null ? $website->value : '' }}"/>
                            @if($errors->has('website'))
                                <div
                                    style="color: red">{{ $errors->first('website') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="facebook_link">{{ __('common.facebook_link') }}</label>
                            <input type="text" id="facebook_link" name="settings[facebook_link]" class="form-control"
                                   placeholder="{{ __('common.facebook_link') }}" value="{{ $facebook_link != null ? $facebook_link->value : '' }}"/>
                            @if($errors->has('facebook_link'))
                                <div
                                    style="color: red">{{ $errors->first('facebook_link') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="twitter_link">{{ __('common.twitter_link') }}</label>
                            <input type="text" id="twitter_link" name="settings[twitter_link]" class="form-control"
                                   placeholder="{{ __('common.twitter_link') }}" value="{{ $twitter_link != null ? $twitter_link->value : '' }}"/>
                            @if($errors->has('twitter_link'))
                                <div
                                    style="color: red">{{ $errors->first('twitter_link') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="instagram_link">{{ __('common.instagram_link') }}</label>
                            <input type="text" id="instagram_link" name="settings[instagram_link]" class="form-control"
                                   placeholder="{{ __('common.instagram_link') }}" value="{{ $instagram_link != null ? $instagram_link->value : '' }}"/>
                            @if($errors->has('instagram_link'))
                                <div
                                    style="color: red">{{ $errors->first('instagram_link') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="snapchat_link">{{ __('common.snapchat_link') }}</label>
                            <input type="text" id="snapchat_link" name="settings[snapchat_link]" class="form-control"
                                   placeholder="{{ __('common.snapchat_link') }}" value="{{ $snapchat_link != null ? $snapchat_link->value : '' }}"/>
                            @if($errors->has('snapchat_link'))
                                <div
                                    style="color: red">{{ $errors->first('snapchat_link') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="whatsapp_link">{{ __('common.whatsapp_link') }}</label>
                            <input type="text" id="whatsapp_link" name="settings[whatsapp_link]" class="form-control"
                                   placeholder="{{ __('common.whatsapp_link') }}" value="{{ $whatsapp_link != null ? $whatsapp_link->value : '' }}"/>
                            @if($errors->has('whatsapp_link'))
                                <div
                                    style="color: red">{{ $errors->first('whatsapp_link') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="whatsapp_link">{{ __('common.evacuation_responsibility') . ' - ' . __('common.Arabic') }}</label>
                            <input type="text" id="whatsapp_link" name="settings[evacuation_responsibility][ar]" class="form-control"
                                   placeholder="{{ __('common.evacuation_responsibility') }}" value="{{ $evacuation_responsibility != null ? $evacuation_responsibility->value : '' }}"/>
                            @if($errors->has('evacuation_responsibility'))
                                <div
                                    style="color: red">{{ $errors->first('evacuation_responsibility') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="whatsapp_link">{{ __('common.evacuation_responsibility') . ' - ' . __('common.English') }}</label>
                            <input type="text" id="whatsapp_link" name="settings[evacuation_responsibility][en]" class="form-control"
                                   placeholder="{{ __('common.evacuation_responsibility') }}" value="{{ $evacuation_responsibility != null ? $evacuation_responsibility->value_en : '' }}"/>
                            @if($errors->has('evacuation_responsibility'))
                                <div
                                    style="color: red">{{ $errors->first('evacuation_responsibility') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="delivery_price">{{ __('common.delivery_price') }}</label>
                            <input type="text" id="delivery_price" name="settings[delivery_price]" class="form-control"
                                   placeholder="{{ __('common.delivery_price') }}" value="{{ $delivery_price != null ? $delivery_price->value : '' }}"/>
                            @if($errors->has('delivery_price'))
                                <div
                                    style="color: red">{{ $errors->first('delivery_price') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="app_percentage">{{ __('common.app_percentage_reservations') }} %</label>
                            <input type="text" id="app_percentage" name="settings[app_percentage]" class="form-control"
                                   placeholder="{{ __('common.app_percentage') }}" value="{{ $app_percentage != null ? $app_percentage->value : '' }}"/>
                            @if($errors->has('app_percentage'))
                                <div
                                    style="color: red">{{ $errors->first('app_percentage') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="app_percentage_store">{{ __('common.app_percentage_store') }} %</label>
                            <input type="text" id="app_percentage_store" name="settings[app_percentage_store]" class="form-control"
                                   placeholder="{{ __('common.app_percentage_store') }}" value="{{ $app_percentage_store != null ? $app_percentage_store->value : '' }}"/>
                            @if($errors->has('app_percentage_store'))
                                <div
                                    style="color: red">{{ $errors->first('app_percentage_store') }}</div>
                            @endif
                        </div>
                        <div class="col-12 text-center mt-2 pt-50">
                            <button type="submit" class="btn btn-primary me-1">@lang('common.save_changes')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')

@endsection

