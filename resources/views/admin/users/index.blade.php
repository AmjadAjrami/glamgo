@extends('admin.layouts.app')

@section('main-content')
    <link type="text/css" rel="stylesheet"
          href="{{ asset('app-assets/image-uploader-master/dist/image-uploader.min.css') }}">

    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">@lang('common.users')</h4>
                </div>
                <div class="card-body">
                    @if(auth()->user()->hasAnyPermission(['add_user']))
                        <button type="button" class="btn btn-outline-primary add_btn" style="float: left"
                                id="create_btn"
                                data-bs-original-title="Edit" data-bs-toggle="modal"
                                data-bs-target=".create_modal">+ @lang('common.add')</button>
                    @endif
                    @if(auth()->user()->hasAnyPermission(['edit_user_status']))
                        <button value="1" disabled="disabled" id="status_btn" class="status_btn btn btn-dark">
                            @lang('common.activate')
                        </button>
                        <button value="0" disabled="disabled" id="status_btn" class="status_btn btn btn-warning">
                            @lang('common.deactivate')
                        </button>
                    @endif
                    @if(auth()->user()->hasAnyPermission(['delete_user']))
                        <button disabled="disabled" id="delete_btn" class="delete-btn btn btn-danger"><i
                                class="fa fa-lg fa-trash-o"></i> @lang('common.delete')</button>
                    @endif
                </div>
                <form id="search_form" style="margin-right: 25px;margin-top: 30px">
                    <h6>@lang('common.search')</h6>
                    <div class="form-row">
                        <div class="form-group">
                            <input id="s_name" name="name" class="form-control" style="width: 15%; display: inline"
                                   placeholder="@lang('common.name')">
                            <input id="s_email" name="email" class="form-control" style="width: 15%; display: inline"
                                   placeholder="@lang('common.email')">
                            <input id="s_mobile" name="mobile" class="form-control" style="width: 15%; display: inline"
                                   placeholder="@lang('common.mobile')">
                            <select name="country_id" id="s_country_id" class="form-control"
                                    style="width: 15%; display: inline">
                                <option selected disabled>@lang('common.choose') @lang('common.country')</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                            <select name="city_id" id="s_city_id" class="form-control"
                                    style="width: 15%; display: inline">
                                <option selected disabled>@lang('common.choose') @lang('common.city')</option>
                            </select>
                            <select id="s_status" name="status" class="form-control"
                                    style="width: 15%; display: inline">
                                <option selected disabled>@lang('common.choose') @lang('common.status')</option>
                                <option value="1">@lang('common.active')</option>
                                <option value="2">@lang('common.inactive')</option>
                            </select>
                            <input type="button" id="search_btn"
                                   class="btn btn-info" value="@lang('common.search')">
                            <input type="button" id="clear_btn"
                                   class="btn btn-secondary" value="@lang('common.clear_search')">
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-bordered" id="datatable">
                        <thead>
                        <tr>
                            <th class="checkbox-column sorting_disabled" rowspan="1" colspan="1"
                                style="width: 35px;" aria-label="Record Id">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="select_all"/>
                                    <label class="form-check-label" for="select_all"></label>
                                </div>
                            </th>
                            <th>@lang('common.id')</th>
                            <th>@lang('common.image')</th>
                            <th>@lang('common.name')</th>
                            <th>@lang('common.email')</th>
                            <th>@lang('common.mobile')</th>
                            <th>@lang('common.country')</th>
                            <th>@lang('common.city')</th>
                            <th>@lang('common.wallet_balance')</th>
                            <th>@lang('common.created_at')</th>
                            <th>@lang('common.actions')</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade create_modal" id="create_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 px-sm-5 pt-50">
                    <div class="text-center mb-2">
                        <h1 class="mb-1">@lang('common.add') @lang('common.user')</h1>
                    </div>
                    <form id="create_form" data-reset="true" method="POST" class="row gy-1 pt-75 ajax_form"
                          onsubmit="return false" enctype="multipart/form-data">
                        @csrf
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="name">{{ __('common.name') }}</label>
                            <input type="text" id="name" name="name" class="form-control"
                                   placeholder="{{ __('common.name') }}"/>
                            @if($errors->has('name'))
                                <div
                                    style="color: red">{{ $errors->first('name') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="email">{{ __('common.email') }}</label>
                            <input type="email" id="email" name="email" class="form-control"
                                   placeholder="{{ __('common.email') }}"/>
                            @if($errors->has('email'))
                                <div
                                    style="color: red">{{ $errors->first('email') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="mobile">{{ __('common.mobile') }}</label>
                            <input type="text" id="mobile" name="mobile" class="form-control"
                                   placeholder="{{ __('common.mobile') }}"/>
                            @if($errors->has('mobile'))
                                <div
                                    style="color: red">{{ $errors->first('mobile') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="password">{{ __('common.password') }}</label>
                            <input type="password" id="password" name="password" class="form-control"
                                   placeholder="{{ __('common.password') }}"/>
                            @if($errors->has('password'))
                                <div
                                    style="color: red">{{ $errors->first('password') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="country_id">{{ __('common.country') }}</label>
                            <select id="country_id" name="country_id" class="form-control select2">
                                <option selected disabled>@lang('common.choose')</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('country_id'))
                                <div
                                    style="color: red">{{ $errors->first('country_id') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="city_id">{{ __('common.city') }}</label>
                            <select id="city_id" name="city_id" class="form-control select2">
                                <option selected disabled>@lang('common.choose')</option>
                            </select>
                            @if($errors->has('city_id'))
                                <div
                                    style="color: red">{{ $errors->first('city_id') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="gender">{{ __('common.gender') }}</label>
                            <select name="gender" id="gender" class="form-control">
                                <option selected disabled>@lang('common.choose')</option>
                                <option value="1">@lang('common.male')</option>
                                <option value="2">@lang('common.female')</option>
                            </select>
                            @if($errors->has('gender'))
                                <div
                                    style="color: red">{{ $errors->first('gender') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="dob">{{ __('common.dob') }}</label>
                            <input type="date" id="dob" name="dob" class="form-control"
                                   placeholder="{{ __('common.dob') }}"/>
                            @if($errors->has('dob'))
                                <div
                                    style="color: red">{{ $errors->first('dob') }}</div>
                            @endif
                        </div>
                        <div class="col-12">
                            <label class="form-label"
                                   for="end_date">{{ __('common.user_image') }}</label>
                            <br>
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;"
                                     data-trigger="fileinput">
                                    <img src="{{ asset('placeholder.jpg') }}" alt="...">
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail"
                                     style="max-width: 200px; max-height: 150px"></div>
                                <div>
                                        <span class="btn btn-white btn-file">
                                            <span class="fileinput-new">@lang('common.chooseImage')</span>
                                            <span class="fileinput-exists">@lang('common.change')</span>
                                            <input type="file" name="image">
                                        </span>
                                    <a href="#" class="btn btn-orange fileinput-exists"
                                       data-dismiss="fileinput">@lang('common.remove')</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 text-center mt-2 pt-50">
                            <button type="submit" class="btn btn-primary me-1 submit_btn"
                                    form="create_form">@lang('common.save_changes')</button>
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

    <div class="modal fade" id="editUser" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 px-sm-5 pt-50">
                    <div class="text-center mb-2">
                        <h1 class="mb-1">@lang('common.edit') @lang('common.user')</h1>
                    </div>
                    <form id="editUserForm" data-reset="true" method="POST" class="row gy-1 pt-75 ajax_form"
                          onsubmit="return false" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="edit_name">{{ __('common.name') }}</label>
                            <input type="text" id="edit_name" name="name" class="form-control"
                                   placeholder="{{ __('common.name') }}"/>
                            @if($errors->has('name'))
                                <div
                                    style="color: red">{{ $errors->first('name') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="edit_email">{{ __('common.email') }}</label>
                            <input type="email" id="edit_email" name="email" class="form-control"
                                   placeholder="{{ __('common.email') }}"/>
                            @if($errors->has('email'))
                                <div
                                    style="color: red">{{ $errors->first('email') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="edit_mobile">{{ __('common.mobile') }}</label>
                            <input type="text" id="edit_mobile" name="mobile" class="form-control"
                                   placeholder="{{ __('common.mobile') }}"/>
                            @if($errors->has('mobile'))
                                <div
                                    style="color: red">{{ $errors->first('mobile') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="edit_password">{{ __('common.password') }}</label>
                            <input type="password" id="edit_password" name="password" class="form-control"
                                   placeholder="{{ __('common.password') }}"/>
                            @if($errors->has('password'))
                                <div
                                    style="color: red">{{ $errors->first('password') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="edit_country_id">{{ __('common.country') }}</label>
                            <select id="edit_country_id" name="country_id" class="form-control select2">
                                <option selected disabled>@lang('common.choose')</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('country_id'))
                                <div
                                    style="color: red">{{ $errors->first('country_id') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="edit_city_id">{{ __('common.city') }}</label>
                            <select id="edit_city_id" name="city_id" class="form-control select2">
                                <option selected disabled>@lang('common.choose')</option>
                            </select>
                            @if($errors->has('city_id'))
                                <div
                                    style="color: red">{{ $errors->first('city_id') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="edit_gender">{{ __('common.gender') }}</label>
                            <select name="gender" id="edit_gender" class="form-control">
                                <option selected disabled>@lang('common.choose')</option>
                                <option value="1">@lang('common.male')</option>
                                <option value="2">@lang('common.female')</option>
                            </select>
                            @if($errors->has('gender'))
                                <div
                                    style="color: red">{{ $errors->first('gender') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="dob">{{ __('common.dob') }}</label>
                            <input type="date" id="edit_dob" name="dob" class="form-control"
                                   placeholder="{{ __('common.dob') }}"/>
                            @if($errors->has('dob'))
                                <div
                                    style="color: red">{{ $errors->first('dob') }}</div>
                            @endif
                        </div>
                        <div class="col-12">
                            <label class="form-label"
                                   for="end_date">{{ __('common.user_image') }}</label>
                            <br>
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;"
                                     data-trigger="fileinput">
                                    <img src="{{ asset('placeholder.jpg') }}" alt="..." id="edit_image">
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail"
                                     style="max-width: 200px; max-height: 150px"></div>
                                <div>
                                        <span class="btn btn-white btn-file">
                                            <span class="fileinput-new">@lang('common.chooseImage')</span>
                                            <span class="fileinput-exists">@lang('common.change')</span>
                                            <input type="file" name="image">
                                        </span>
                                    <a href="#" class="btn btn-orange fileinput-exists"
                                       data-dismiss="fileinput">@lang('common.remove')</a>
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

    <div class="modal fade" id="add_balance" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 px-sm-5 pt-50">
                    <div class="text-center mb-2">
                        <h1 class="mb-1">@lang('common.add_balance')</h1>
                    </div>
                    <form id="add_balance_form" data-reset="true" method="POST" class="row gy-1 pt-75 ajax_form"
                          onsubmit="return false" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="col-12 col-md-12">
                            <label class="form-label"
                                   for="balance">{{ __('common.balance') }}</label>
                            <input type="text" id="balance" name="balance" class="form-control"
                                   placeholder="{{ __('common.balance') }}"/>
                            @if($errors->has('balance'))
                                <div
                                    style="color: red">{{ $errors->first('balance') }}</div>
                            @endif
                        </div>
                        <div class="col-12 text-center mt-2 pt-50">
                            <button type="submit" class="btn btn-primary me-1 submit_btn"
                                    form="add_balance_form">@lang('common.save_changes')</button>
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
        $('.input-images').imageUploader({
            imagesInputName: 'image',
        });

        var url = '{{ url(app()->getLocale() . "/tmg/users/") }}/';

        var dt_adv_filter = $('#datatable').DataTable({
            "oLanguage": {
                "oPaginate": {
                    "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>',
                    "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>'
                },
                "sInfo": "@lang('common.showing') _START_ @lang('common.to') _END_ @lang('common.from') @lang('common.origin') _TOTAL_ @lang('common.entered')",
                "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                "sSearchPlaceholder": "@lang('common.search')",
                "sLengthMenu": "@lang('common.show') _MENU_ @lang('common.data')",
            },
            'columnDefs': [
                {
                    "targets": 1,
                    "visible": false
                },
                {
                    'targets': 0,
                    "searchable": false,
                    "orderable": false
                },
            ],
            // dom: 'lrtip',
            "order": [[1, 'asc']],
            processing: true,
            serverSide: true,
            searching: false,
            ajax: {
                url: url + 'indexTable',
                data: function (d) {
                    d.status = $('#s_status').val();
                    d.name = $('#s_name').val();
                    d.email = $('#s_email').val();
                    d.mobile = $('#s_mobile').val();
                    d.country_id = $('#s_country_id').val();
                    d.city_id = $('#s_city_id').val();
                }
            },
            columns: [
                {
                    "render": function (data, type, full, meta) {
                        return `<td class="checkbox-column sorting_1">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input table_ids" type="checkbox" name="table_ids[]" value="` + full.id + `"/>
                                        <label class="form-check-label"></label>
                                    </div>
                                </td>`;
                    }
                },
                {data: 'id', name: 'id', orderable: false, searchable: false},
                {
                    "render": function (data, type, full, meta) {
                        return '<span><img src="' + full.image + '" class="rounded-circle profile-img" alt="avatar" style="width: 50px;height: 50px"></span>';
                    }, orderable: false, searchable: false
                },
                {data: 'name', name: 'name', orderable: false, searchable: false},
                {data: 'email', name: 'email', orderable: false, searchable: false},
                {data: 'mobile', name: 'mobile', orderable: false, searchable: false},
                {data: 'country_name', name: 'country_name', orderable: false, searchable: false},
                {data: 'city_name', name: 'city_name', orderable: false, searchable: false},
                {data: 'balance', name: 'balance', orderable: false, searchable: false},
                {data: 'add_time', name: 'add_time', orderable: false, searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });

        $(document).ready(function () {
            dt_adv_filter.on('draw', function () {
                $("#select_all").prop("checked", false)
                $('#delete_btn').prop('disabled', 'disabled');
                $('.status_btn').prop('disabled', 'disabled');
            });

            $(document).on('click', '.add_balance', function (event) {
                var button = $(this);
                var id = button.data('id');

                $('#add_balance_form').attr('action', url + id + '/update_balance');
            });

            $(document).on('click', '.edit_btn', function (event) {
                var button = $(this);
                var id = button.data('id');

                $('#editUserForm').attr('action', url + id + '/update');
                $('#edit_name').val(button.data('name'));
                $('#edit_email').val(button.data('email'));
                $('#edit_mobile').val(button.data('mobile'));
                $('#edit_dob').val(button.data('dob'));
                $('#edit_gender').val(button.data('gender'));
                $('#edit_country_id').val(button.data('country_id'));
                $('#edit_country_id').trigger('change');
                $('#edit_image').attr('src', button.data('image'));

                let preloaded = [
                    {id: 1, src: '' + button.data('image') + ''},
                ];

                $('.input-images_3').imageUploader({
                    imagesInputName: 'image',
                    preloaded: preloaded,
                    maxSize: 2 * 1024 * 1024,
                });


                var data_append = "";
                @foreach($countries as $country)
                if ({{ $country->id }} == button.data('country_id')) {
                    @foreach($country->cities as $city)
                    if (button.data('city_id') == {{ $city->id }}) {
                        data_append += '<option value=' + {{ $city->id }} + ' selected>{{ $city->name }}</option>';
                    } else {
                        data_append += '<option value=' + {{ $city->id }} + '>{{ $city->name }}</option>';
                    }
                    @endforeach
                }
                @endforeach

                $('#edit_city_id').empty();
                $('#edit_city_id').append(data_append);
                $('#edit_city_id').trigger('change');
            });

            $(document).on('click', '#create_btn', function (event) {
                $('#create_form').attr('action', '{{ route('users.store') }}');
            });
        });

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

        $('#edit_country_id').change(function () {
            var data = cities_list['country_' + $(this).val() + '_cities'];
            var data_append = '<option selected disabled>@lang('common.choose')</option>';
            data.forEach(function (index) {
                data_append += '<option value=' + index.id + '>' + index.text + '</option>';
            });

            $('#edit_city_id').empty();
            $('#edit_city_id').append(data_append);
        });

        $('#s_country_id').change(function () {
            var data = cities_list['country_' + $(this).val() + '_cities'];
            var data_append = '<option selected disabled>@lang('common.choose') @lang('common.city')</option>';
            data.forEach(function (index) {
                data_append += '<option value=' + index.id + '>' + index.text + '</option>';
            });

            $('#s_city_id').empty();
            $('#s_city_id').append(data_append);
        });

        function add_remove(id) {

            $(this).removeClass('btn btn-dark');
            $(this).addClass('btn btn-warning');
            $.ajaxSetup({
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
            });

            $.ajax({
                url: '{{ url(app()->getLocale() . '/tmg/users/update_status/') }}/' + id,
                method: 'PUT',
                success: function (data) {
                    $('#btn_' + id).removeClass(data.remove);
                    $('#btn_' + id).addClass(data.add);
                    $('#btn_' + id).text(data.text);
                    toastr.success('@lang('common.done_successfully')');
                }
            });
        }

        function Delete(id) {
            Swal.fire({
                title: 'هل متأكد من الحذف ؟',
                type: 'error',
                showCancelButton: true,
                confirmButtonColor: '#3085D6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'نعم',
                cancelButtonText: 'لا',
            }).then((result) => {
                if (result.value) {
                    event.preventDefault();
                    document.getElementById('delete-form-' + id).submit();
                }
            })
        }
    </script>
@endsection

