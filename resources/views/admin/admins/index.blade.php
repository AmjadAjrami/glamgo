@extends('admin.layouts.app')

@section('main-content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.3/skins/all.min.css"
          integrity="sha512-wcKDxok85zB8F9HzgUwzzzPKJhHG7qMfC7bSKrZcFTC2wZXVhmgKNXYuid02cHVnFSC8KOJCXQ8M83UVA7v5Bw=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>

    <style>
        @if(app()->getLocale() == 'ar')
            .modal-dialog.modal-lg.modal-dialog-centered.modal-edit-user {
            max-width: 1340px !important;
        }

        @else
            .modal-dialog.modal-lg.modal-dialog-centered.modal-edit-user {
            max-width: 1775px !important;
        }

        @endif

        .alert.alert-success {
            padding: 10px !important;
            color: #23a15b !important;
        }
    </style>

    <div class="row" id="table-bordered">
        <div class="col-12">
            @include('flash::message')
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">@lang('common.admins')</h4>
                </div>
                <div class="card-body">
                    @if(auth()->user()->hasAnyPermission(['add_admin']))
                        <button type="button" class="btn btn-outline-primary add_btn" style="float: left"
                                data-bs-original-title="Edit" data-bs-toggle="modal" id="create_btn"
                                data-bs-target=".create_modal">+ @lang('common.add')</button>
                    @endif
                    @if(auth()->user()->hasAnyPermission(['edit_admin_status']))
                        <button value="1" disabled="disabled" id="status_btn" class="status_btn btn btn-dark">
                            @lang('common.activate')
                        </button>
                        <button value="0" disabled="disabled" id="status_btn" class="status_btn btn btn-warning">
                            @lang('common.deactivate')
                        </button>
                    @endif
                    @if(auth()->user()->hasAnyPermission(['delete_admin']))
                        <button disabled="disabled" id="delete_btn" class="delete-btn btn btn-danger"><i
                                class="fa fa-lg fa-trash-o"></i> @lang('common.delete')</button>
                    @endif

                </div>
                {{--                <form id="search_form" style="margin-right: 25px;margin-top: 30px">--}}
                {{--                    <h6>@lang('common.search')</h6>--}}
                {{--                    <div class="form-row">--}}
                {{--                        <div class="form-group">--}}
                {{--                            <select id="s_country_id" name="country_id" class="form-control" style="width: 15%; display: inline">--}}
                {{--                                <option selected disabled>@lang('common.choose') @lang('common.country')</option>--}}
                {{--                                @foreach($countries as $country)--}}
                {{--                                    <option value="{{ $country->id }}">{{ $country->name }}</option>--}}
                {{--                                @endforeach--}}
                {{--                            </select>--}}
                {{--                            <input type="button" id="search_btn"--}}
                {{--                                   class="btn btn-info" value="@lang('common.search')">--}}
                {{--                            <input type="button" id="clear_btn"--}}
                {{--                                   class="btn btn-secondary" value="@lang('common.clear_search')">--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                </form>--}}
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
                            <th>@lang('common.name')</th>
                            <th>@lang('common.email')</th>
                            <th>@lang('common.mobile')</th>
                            <th>@lang('common.country_name')</th>
                            <th>@lang('common.city_name')</th>
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
                        <h1 class="mb-1">@lang('common.add') @lang('common.admin')</h1>
                    </div>
                    <form id="create_form" data-reset="true" method="POST" class="row gy-1 pt-75 ajax_form"
                          onsubmit="return false" enctype="multipart/form-data">
                        @csrf
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="name">{{ __('common.name') }}</label>
                            <input type="text" id="name" name="name" class="form-control"
                                   placeholder="{{ __('common.name') }}"
                                   data-msg="Please enter country name"/>
                            @if($errors->has('name'))
                                <div
                                    style="color: red">{{ $errors->first('name') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="email">{{ __('common.email') }}</label>
                            <input type="text" id="email" name="email" class="form-control"
                                   placeholder="{{ __('common.email') }}"
                                   data-msg="Please enter country name"/>
                            @if($errors->has('email'))
                                <div
                                    style="color: red">{{ $errors->first('email') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="mobile">{{ __('common.mobile') }}</label>
                            <input type="text" id="mobile" name="mobile" class="form-control"
                                   placeholder="{{ __('common.mobile') }}"
                                   data-msg="Please enter country name"/>
                            @if($errors->has('mobile'))
                                <div
                                    style="color: red">{{ $errors->first('mobile') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="password">{{ __('common.password') }}</label>
                            <input type="password" id="password" name="password" class="form-control"
                                   placeholder="{{ __('common.password') }}" autocomplete="new-password"
                                   data-msg="Please enter country name"/>
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
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
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
                            </select>
                            @if($errors->has('city_id'))
                                <div
                                    style="color: red">{{ $errors->first('city_id') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-12">
                            <label class="form-label" for="permissions">@lang('common.permissions')</label>
                            @for($i = 1; $i < 25; $i++)
                                @php
                                    $permissions_group = \Spatie\Permission\Models\Permission::query()->where('type', $i)->get()
                                @endphp
                                <div class="col-12 row"
                                     style="clear: both; direction: {{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
                                    <div class="col-2">
                                        <div class="checkbox select-all">
                                            <input id="all{{$i}}" type="checkbox" class="form-check-input parent_check"
                                                   data-index="{{ $i }}"/>
                                            <label
                                                for="all{{$i}}"> {{\Spatie\Permission\Models\Permission::groupName($i)}}</label>
                                        </div>
                                    </div>
                                    @foreach($permissions_group as $permission)
                                        <div class="col-lg-2">
                                            <div class="checkbox">
                                                <input type="checkbox" class="form-check-input rows_{{$i}} single_row"
                                                       name="permissions[]" id="rows_{{ $permission->id }}"
                                                       data-index="{{ $i }}"
                                                       value="{{ $permission->id }}"/>
                                                <label
                                                    for="rows_{{ $permission->id }}">@lang('common.' . $permission->name)</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endfor
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
                        <h1 class="mb-1">@lang('common.edit') @lang('common.admin')</h1>
                    </div>
                    <form id="editUserForm" data-reset="true" method="POST" class="row gy-1 pt-75 ajax_form"
                          onsubmit="return false" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="edit_name">{{ __('common.name') }}</label>
                            <input type="text" id="edit_name" name="name" class="form-control"
                                   placeholder="{{ __('common.name') }}"
                                   data-msg="Please enter country name"/>
                            @if($errors->has('name'))
                                <div
                                    style="color: red">{{ $errors->first('name') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="edit_email">{{ __('common.email') }}</label>
                            <input type="text" id="edit_email" name="email" class="form-control"
                                   placeholder="{{ __('common.email') }}"
                                   data-msg="Please enter country name"/>
                            @if($errors->has('email'))
                                <div
                                    style="color: red">{{ $errors->first('email') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="edit_mobile">{{ __('common.mobile') }}</label>
                            <input type="text" id="edit_mobile" name="mobile" class="form-control"
                                   placeholder="{{ __('common.mobile') }}"
                                   data-msg="Please enter country name"/>
                            @if($errors->has('mobile'))
                                <div
                                    style="color: red">{{ $errors->first('mobile') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="password">{{ __('common.password') }}</label>
                            <input type="password" id="password" name="password" class="form-control"
                                   placeholder="{{ __('common.password') }}" autocomplete="new-password"
                                   data-msg="Please enter country name"/>
                            @if($errors->has('password'))
                                <div
                                    style="color: red">{{ $errors->first('password') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-12">
                            <label class="form-label" for="country_id">@lang('common.country')</label>
                            <select id="edit_country_id" name="country_id" class="form-control">
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
                        <div class="col-12 col-md-12">
                            <label class="form-label" for="city_id">@lang('common.city')</label>
                            <select id="edit_city_id" name="city_id" class="form-control">
                                <option selected disabled>@lang('common.choose')</option>
                            </select>
                            @if($errors->has('city_id'))
                                <div
                                    style="color: red">{{ $errors->first('city_id') }}</div>
                            @endif
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

@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.3/icheck.min.js"></script>
    <script>
        var url = '{{ url(app()->getLocale() . "/tmg/admins") }}/';

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
                    d.country_id = $('#s_country_id').val();
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
                {data: 'name', name: 'name', orderable: false, searchable: false},
                {data: 'email', name: 'email', orderable: false, searchable: false},
                {data: 'mobile', name: 'mobile', orderable: false, searchable: false},
                {data: 'country_name', name: 'country_name', orderable: false, searchable: false},
                {data: 'city_name', name: 'city_name', orderable: false, searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });

        $(document).ready(function () {
            dt_adv_filter.on('draw', function () {
                $("#select_all").prop("checked", false)
                $('#delete_btn').prop('disabled', 'disabled');
                $('.status_btn').prop('disabled', 'disabled');
            });

            $(document).on('click', '.edit_btn', function (event) {
                var button = $(this);
                var id = button.data('id');
                $('#editUserForm').attr('action', url + id + '/update');
                $('#edit_name').val(button.data('name'));
                $('#edit_email').val(button.data('email'));
                $('#edit_mobile').val(button.data('mobile'));
                $('#edit_country_id').val(button.data('country_id'));

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

            });

            $(document).on('click', '#create_btn', function (event) {
                $('#create_form').attr('action', '{{ route('admins.store') }}');
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

        function add_remove(id) {

            $(this).removeClass('btn btn-dark');
            $(this).addClass('btn btn-warning');
            $.ajaxSetup({
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
            });

            $.ajax({
                url: '{{ url(app()->getLocale() . '/tmg/admins/update_status/') }}/' + id,
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

        $(document).ready(function () {
            $('.parent_check').on('click', function () {
                var index = $(this).data('index');

                $('.rows_' + index).not(this).prop('checked', this.checked);
            });

            $('.single_row').on('click', function () {
                var index = $(this).data('index');

                if ($('.rows_' + index + ':checked').length == $('.rows_' + index).length) {
                    $('#all' + index).prop('checked', this.checked);
                } else {
                    $('#all' + index).prop("checked", this.checked);
                }
            });
        });
    </script>
@endsection

