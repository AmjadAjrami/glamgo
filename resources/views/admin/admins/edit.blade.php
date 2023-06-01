@extends('admin.layouts.app')

@section('main-content')
    <style>
        .permissions.form-group label, label {
            font-size: 13px !important;
        }

        .alert.alert-success {
            padding: 10px !important;
            color: #23a15b !important;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.3/skins/all.min.css"
          integrity="sha512-wcKDxok85zB8F9HzgUwzzzPKJhHG7qMfC7bSKrZcFTC2wZXVhmgKNXYuid02cHVnFSC8KOJCXQ8M83UVA7v5Bw=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>

    <div class="row">
        <div class="col-12">
            @include('flash::message')
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">@lang('common.settings')</h4>
                </div>
                <div class="card-body">
                    <form data-reset="true" method="POST" class="row gy-1 pt-75" enctype="multipart/form-data"
                          action="{{ route('admins.update', $admin->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="name">{{ __('common.name') }}</label>
                            <input type="text" id="name" name="name" class="form-control"
                                   placeholder="{{ __('common.name') }}" value="{{ @$admin->name }}"/>
                            @if($errors->has('name'))
                                <div
                                    style="color: red">{{ $errors->first('name') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="email">{{ __('common.email') }}</label>
                            <input type="text" id="email" name="email" class="form-control"
                                   placeholder="{{ __('common.email') }}" value="{{ @$admin->email }}"/>
                            @if($errors->has('email'))
                                <div
                                    style="color: red">{{ $errors->first('email') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="mobile">{{ __('common.mobile') }}</label>
                            <input type="text" id="mobile" name="mobile" class="form-control"
                                   placeholder="{{ __('common.mobile') }}" value="{{ @$admin->mobile }}"/>
                            @if($errors->has('mobile'))
                                <div
                                    style="color: red">{{ $errors->first('mobile') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="password" class="form-label">{{ __('common.password') }}</label>
                            <input type="password" name="password" class="form-control" id="password"
                                   placeholder="{{ __('common.password') }}">
                            @if($errors->has('password'))
                                <div
                                    style="color: red">{{ $errors->first('password') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-12">
                            <label class="form-label" for="country_id">@lang('common.country')</label>
                            <select id="country_id" name="country_id" class="form-control">
                                <option selected disabled>@lang('common.choose')</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" {{ $admin->country_id == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('country_id'))
                                <div
                                    style="color: red">{{ $errors->first('country_id') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-12">
                            <label class="form-label" for="city_id">@lang('common.city')</label>
                            <select id="city_id" name="city_id" class="form-control">
                                <option selected disabled>@lang('common.choose')</option>
                                @foreach($country_cities as $city)
                                    <option value="{{ $city->id }}" {{ $admin->city_id == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('city_id'))
                                <div
                                    style="color: red">{{ $errors->first('city_id') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-4">
                            <label for="permissions">{{ __('common.permissions') }}</label>
                            <div id="permissions_edit">

                            </div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.3/icheck.min.js"></script>
    <script>
        var cities_list = {
            @foreach($countries as $country)
            'country_{{$country->id}}_cities': [
                    @foreach($country->cities as $city)
                {
                    id: '{{ $city->id }}',
                    text: '{{ $city->name }}',
                },
                @endforeach
            ],
            @endforeach
        };

        $('#country_id').change(function () {
            var data = cities_list['country_' + $(this).val() + '_cities'];
            var data_append = '<option selected disabled>@lang('common.choose')</option>';
            data.forEach(function (index) {
                data_append += '<option value=' + index.id + '>' + index.text + '</option>';
            });

            $('#city_id').empty();
            $('#city_id').append(data_append);
        });

        jQuery(function ($) {
            var permissions = @json($permission_groups);
            var data = @json($admin);

            var content = '';
            for (var $i = 0; $i < permissions.length; $i++) {
                content +=
                    '<div class="col-12 row" style="clear: both; direction: @if(app()->getLocale() == 'ar') rtl @else ltr @endif">'
                    + '<div class="col-2">'
                    + '<div class="checkbox select-all">'
                    + '<input id="all' + $i + '" type="checkbox" class="form-check-input parent_check" data-index="'+ $i +'"';

                var nested = '';
                var count = 0;
                for (var k = 0; k < permissions[$i]['permissions'].length; k++) {
                    nested += '<div class="col-lg-2">'
                        + '<div class="checkbox">'
                        + '<input type="checkbox" class="rows form-check-input rows_'+ $i +' single_row" data-index="'+ $i +'"'
                        + 'name="permissions[]" '
                        + 'id="rows_' + permissions[$i]['permissions'][k]['id'] + '"';


                    for (var s = 0; s < data.permissions.length; s++) {
                        if (data.permissions[s]['name'] === permissions[$i]['permissions'][k]['name']) {
                            nested += ' checked ';
                            count++;
                        }
                    }
                    content += count === permissions[$i]['permissions'].length ? ' checked ' : ' ';
                    nested += 'value="' + permissions[$i]['permissions'][k]['id'] + '"/>';

                    nested += ' <label for="rows_' + permissions[$i]['permissions'][k]['id'] + '">' + permissions[$i]['permissions'][k]['name_trans'] + '</label>'
                        + '</div>'
                        + '</div>';
                }


                content += '/>'
                    + ' <label for="all' + $i + '">' + permissions[$i]['name'] + '</label>'
                    + '</div>'
                    + '</div>';

                content += nested;
                content += '</div>';

            }
            $('#permissions_edit').html(content);

            //
            for (var $a = 0; $a < permissions.length; $a++) {
                $('#all' + $a).on('change', function (event) {
                    if (this.checked) {
                        console.log('#all' + this.id.substring(4) + ' checked');
                        for (var $x = 0; $x < 5; $x++) {
                            $('#all' + this.id.substring(4) + '_' + $x).attr('checked', true);
                        }
                    } else {
                        for (var $x = 0; $x < 5; $x++) {
                            $('#all' + this.id.substring(4) + '_' + $x).prop('checked', false);
                        }
                    }
                });
            }

            $(document).ready(function () {
                $('.parent_check').on('click', function (){
                    var index = $(this).data('index');

                    $('.rows_' + index).not(this).prop('checked', this.checked);
                });

                $('.single_row').on('click', function (){
                    var index = $(this).data('index');

                    if ($('.rows_'+ index +':checked').length == $('.rows_' + index).length) {
                        $('#all' + index).prop('checked', this.checked);
                    }else{
                        $('#all' + index).prop("checked", this.checked);
                    }
                });
            });

        });
    </script>
@endsection
