@extends('home_service.layouts.app')

@section('main-content')
    <link type="text/css" rel="stylesheet"
          href="{{ asset('app-assets/image-uploader-master/dist/image-uploader.min.css') }}">

    <style>
        button.btn.btn-warning.bs-tooltip.edit_btn.waves-effect.waves-float.waves-light {
            color: #ffffff !important;
        }

        input[type=time]::-webkit-datetime-edit-ampm-field {
            display: none;
        }
    </style>

    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">@lang('common.booking_times')</h4>
                </div>
                <div class="table-responsive">
                    <a href="{{ route('home_service_booking_times.add_times', request()->type) }}" class="btn btn-success"
                       style="float: left;margin-left: 20px">@lang('common.add_times')</a>

                    <button type="button" class="btn btn-dark bs-tooltip mb-2" style="float: left;margin-left: 20px"
                            data-bs-placement="top" title=""
                            data-bs-original-title="Edit" data-bs-toggle="modal" data-bs-target="#time_settings"
                            data-id="6">@lang('common.time_settings')</button>

                    <table class="table table-bordered" id="datatable">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>@lang('common.day')</th>
                            <th>@lang('common.actions')</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>1</td>
                            <td>@lang('common.sunday')</td>
                            <td>
                                <button type="button" class="btn btn-warning bs-tooltip edit_btn"
                                        data-bs-placement="top" title="" data-bs-original-title="Edit"
                                        data-bs-toggle="modal" data-bs-target="#editUser"
                                        data-id="1">@lang('common.edit')</button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>@lang('common.monday')</td>
                            <td>
                                <button type="button" class="btn btn-warning bs-tooltip edit_btn"
                                        data-bs-placement="top" title=""
                                        data-bs-original-title="Edit" data-bs-toggle="modal" data-bs-target="#editUser"
                                        data-id="2">@lang('common.edit')</button>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>@lang('common.tuesday')</td>
                            <td>
                                <button type="button" class="btn btn-warning bs-tooltip edit_btn"
                                        data-bs-placement="top" title=""
                                        data-bs-original-title="Edit" data-bs-toggle="modal" data-bs-target="#editUser"
                                        data-id="3">@lang('common.edit')</button>
                            </td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>@lang('common.wednesday')</td>
                            <td>
                                <button type="button" class="btn btn-warning bs-tooltip edit_btn"
                                        data-bs-placement="top" title=""
                                        data-bs-original-title="Edit" data-bs-toggle="modal" data-bs-target="#editUser"
                                        data-id="4">@lang('common.edit')</button>
                            </td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>@lang('common.thursday')</td>
                            <td>
                                <button type="button" class="btn btn-warning bs-tooltip edit_btn"
                                        data-bs-placement="top" title=""
                                        data-bs-original-title="Edit" data-bs-toggle="modal" data-bs-target="#editUser"
                                        data-id="5">@lang('common.edit')</button>
                            </td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td>@lang('common.friday')</td>
                            <td>
                                <button type="button" class="btn btn-warning bs-tooltip edit_btn"
                                        data-bs-placement="top" title=""
                                        data-bs-original-title="Edit" data-bs-toggle="modal" data-bs-target="#editUser"
                                        data-id="6">@lang('common.edit')</button>
                            </td>
                        </tr>
                        <tr>
                            <td>7</td>
                            <td>@lang('common.saturday')</td>
                            <td>
                                <button type="button" class="btn btn-warning bs-tooltip edit_btn"
                                        data-bs-placement="top" title=""
                                        data-bs-original-title="Edit" data-bs-toggle="modal" data-bs-target="#editUser"
                                        data-id="7">@lang('common.edit')</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editUser" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 px-sm-5 pt-50">
                    <div class="text-center mb-2">
                        <h1 class="mb-1">@lang('common.add') @lang('common.booking_times')</h1>
                    </div>
                    <form id="editUserForm" data-reset="true" method="POST" class="row gy-1 pt-75 ajax_form"
                          onsubmit="return false" enctype="multipart/form-data">
                        @csrf
                        {{--                        @method('PUT')--}}
                        <div class="col-12 col-md-12">
                            <div class="row">
                                <input type="hidden" name="day" id="day">
                                <input hidden name="type" value="{{ request()->type }}">
                                <div class="row" id="edit_times">
                                    <input type="hidden" name="booking_time[time_id][]" value="0">
                                    <div class="col-3">
                                        <label class="form-label"
                                               for="from">{{ __('common.from') }}</label>
                                        <input type="time" name="booking_time[from][]" id="from"
                                               class="form-control">
                                    </div>
                                    <div class="col-3">
                                        <label class="form-label"
                                               for="to">{{ __('common.to') }}</label>
                                        <input type="time" name="booking_time[to][]" id="to"
                                               class="form-control">
                                    </div>
                                    <div class="col-3">
                                        <div class="form-check form-check-inline" style="margin-top: 35px">
                                            {{--                                            <input hidden name="booking_time[is_reserved][]"--}}
                                            {{--                                                   id="is_reserved_input_0">--}}
                                            <input class="form-check-input is_reserved_0"
                                                   name="booking_time[is_reserved][]" type="checkbox" value="0"/>
                                            <label class="form-check-label"
                                                   for="is_reserved">@lang('common.is_reserved')</label>
                                        </div>
                                    </div>
                                </div>
                                <div id="times">

                                </div>
                                <div class="col-3 mt-2">
                                    <button type="button" id="add_new_time" class="btn btn-success">+</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 text-center mt-2 pt-50">
                            <button type="submit" class="btn btn-primary me-1 submit_btn"
                                    form="editUserForm">@lang('common.save_changes')</button>
                            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                                    aria-label="Close">
                                @lang('common.discard')
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="time_settings" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 px-sm-5 pt-50">
                    <div class="text-center mb-2">
                        <h1 class="mb-1">@lang('common.edit') @lang('common.time_settings')</h1>
                    </div>
                    <form id="time_settingsForm" data-reset="true" method="POST" class="row gy-1 pt-75 ajax_form"
                          onsubmit="return false" enctype="multipart/form-data"
                          action="{{ route('home_service_booking_times.time_settings') }}">
                        @csrf
                        <div class="col-12 col-md-12">
                            <div class="row">
                                <div class="row" id="edit_times">
                                    <input hidden name="type" value="{{ request()->type }}">
                                    <div class="col-6">
                                        <label class="form-label"
                                               for="from">{{ __('common.time_from') }}</label>
                                        <input type="time" name="settings[time_from]" id="from"
                                               value="{{ $time_from == null ? '' : $time_from->value }}"
                                               class="form-control">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label"
                                               for="to">{{ __('common.time_to') }}</label>
                                        <input type="time" name="settings[time_to]" id="to"
                                               value="{{ $time_to == null ? '' : $time_to->value }}"
                                               class="form-control">
                                    </div>
                                    <div class="col-6 mt-2">
                                        <label class="form-label"
                                               for="to">{{ __('common.reservation_duration') . ' - ' . __('common.in_minutes') }}</label>
                                        <input type="number" name="settings[reservation_duration]" id="to"
                                               value="{{ $added_minutes == null ? '' : $added_minutes->value }}"
                                               class="form-control">
                                    </div>
                                    <div class="col-6 mt-2">
                                        <label class="form-label"
                                               for="to">{{ __('common.off_days') }}</label>
                                        <select name="settings[off_days][]" class="form-control select2" multiple>
                                            <option disabled>@lang('common.choose')</option>
                                            @if($off_days != null)
                                                @foreach($days as $key => $day)
                                                    <option value="{{ $key }}" {{ in_array($key, json_decode($off_days->value)) ? 'selected' : '' }}>{{ $day }}</option>
                                                @endforeach
                                            @else
                                                @foreach($days as $key => $day)
                                                    <option value="{{ $key }}">{{ $day }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 text-center mt-2 pt-50">
                            <button type="submit" class="btn btn-primary me-1 submit_btn"
                                    form="time_settingsForm">@lang('common.save_changes')</button>
                            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                                    aria-label="Close">
                                @lang('common.discard')
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('scripts')
    <script type="text/javascript"
            src="{{ asset('app-assets/image-uploader-master/dist/image-uploader.min.js') }}"></script>
    <script type="text/javascript"
            src="{{ asset('app-assets/image-uploader-master/dist/image-uploader_2.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            let i = 0;
            $('#add_new_time').on('click', function () {
                let html = '<div class="row"><input type="hidden" name="booking_time[time_id][]" value="0"><div class="col-3">' +
                    '<label class="form-label" for="from">{{ __('common.from') }}</label>' +
                    '<input type="time" name="booking_time[from][]" id="from" class="form-control">' +
                    '</div>' +
                    '<div class="col-3">' +
                    '<label class="form-label" for="to">{{ __('common.to') }}</label>' +
                    '<input type="time" name="booking_time[to][]" id="to" class="form-control">' +
                    '</div>' +
                    '<div class="col-2">' +
                    '<label class="form-label" for="to">{{ __('common.is_reserved') }}</label>' +
                    '<select name="booking_time[is_reserved][]" class="form-control">'+
                        '<option value="0" selected>@lang('common.choose')</option>'+
                        '<option value="1">@lang('common.yes')</option>'+
                    '</select>'+
                    {{--'<div class="form-check form-check-inline" style="margin-top: 35px">' +--}}
                    {{--// '<input type="text" hidden name="booking_time[is_reserved][]" data-index="' + (i == 0 ? i : i + 1) + '" id="is_reserved_input_' + (i == 0 ? i : i + 1) + '">' +--}}
                    {{--'<input class="form-check-input is_reserved_0" name="booking_time[is_reserved][]" type="checkbox" value="0"/>' +--}}
                    {{--'<label class="form-check-label" for="is_reserved">@lang('common.is_reserved')</label>' +--}}
                    {{--'</div>' +--}}
                    '</div>' +
                    '<div class="col-2" style="margin-top: 23px">' +
                    '<button type="button" class="btn btn-danger remove_new_time">-</button>' +
                    '</div>' +
                    '</div>';

                $('#times').append(html);

                i++;
            });

            $(document).on('click', '.remove_new_time', function () {
                $(this).parent('div').parent('div').remove();
            });

            $(document).on('click', '.edit_btn', function (event) {
                i = 0;
                var button = $(this);
                var id = button.data('id');

                $('#day').attr('value', id);

                $('#edit_times').html('');
                $('#times').html('');

                $.ajaxSetup({
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                });

                $.ajax({
                    url: '{{ url(app()->getLocale() . '/h-service/booking_times/day') }}',
                    method: 'GET',
                    data: {
                        home_service_id: '{{ auth('home_service')->id() }}',
                        day: id,
                        type: '{{ request()->type }}',
                    },
                    success: function (data) {
                        let html = '';
                        $.each(data.times, function (index, value) {
                            let is_reserved = '';
                            let is_reserved_value = 0;
                            if (value.is_reserved == 1) {
                                is_reserved +=  '<option value="0">@lang('common.choose')</option>'+
                                                '<option value="1" selected>@lang('common.yes')</option>';
                            } else {
                                is_reserved +=  '<option value="0" selected>@lang('common.choose')</option>'+
                                                '<option value="1"1>@lang('common.yes')</option>';
                            }

                            html += '<div class="row"><input type="hidden" name="booking_time[time_id][]" value="' + value.id + '"><div class="col-3">' +
                                '<label class="form-label" for="from">{{ __('common.from') }}</label>' +
                                '<input type="time" name="booking_time[from][]" id="from" class="form-control" value="' + value.from + '">' +
                                '</div>' +
                                '<div class="col-3">' +
                                '<label class="form-label" for="to">{{ __('common.to') }}</label>' +
                                '<input type="time" name="booking_time[to][]" id="to" class="form-control" value="' + value.to + '">' +
                                '</div>' +
                                '<div class="col-2">' +
                                '<label class="form-label" for="to">{{ __('common.is_reserved') }}</label>' +
                                '<select name="booking_time[is_reserved][]" class="form-control">'+
                                    is_reserved +
                                '</select>'+
                                {{--'<div class="form-check form-check-inline" style="margin-top: 35px">' +--}}
                                {{--// '<input type="text" hidden name="booking_time[is_reserved][]" id="is_reserved_input_' + index + '">' +--}}
                                {{--'<input class="form-check-input is_reserved_0" name="booking_time[is_reserved][]" data-index="' + index + '" data-id="' + value.id + '" type="checkbox" ' + is_reserved + ' value="' + is_reserved_value + '_' + value.id + '"/>' +--}}
                                {{--'<label class="form-check-label" for="is_reserved">@lang('common.is_reserved')</label>' +--}}
                                // '</div>' +
                                '</div>' +
                                '<div class="col-2" style="margin-top: 23px">' +
                                '<button type="button" class="btn btn-danger remove_new_time">-</button>' +
                                '</div>' +
                                '</div>';

                            i = index;
                        });
                        $('#edit_times').append(html);
                    }
                });

                $('#editUserForm').attr('action', '{{ url(app()->getLocale() . '/h-service/booking_times/update') }}');
            });

            $(document).on('change', '.is_reserved_0', function () {
                var value = $(this).val();
                var id = $(this).data('id');
                var index = $(this).data('index');

                var item = value.substring(0, value.indexOf('_'));

                if (item == 1) {
                    $(this).val(0 + '_' + id);
                } else {
                    $(this).val(1 + '_' + id);
                }
            });
        });
    </script>
@endsection

