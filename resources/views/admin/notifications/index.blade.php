@extends('admin.layouts.app')

@section('main-content')
    <style>
        .alert.alert-success {
            padding: 10px !important;
            color: #23a15b !important;
        }
        .select2{
            visibility: visible !important;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js" integrity="sha512-yDlE7vpGDP7o2eftkCiPZ+yuUyEcaBwoJoIhdXv71KZWugFqEphIS3PU60lEkFaz8RxaVsMpSvQxMBaKVwA5xg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">@lang('common.notifications')</h4>
                </div>
                <div class="card-body">
                    <button type="button" class="btn btn-outline-primary add_btn" style="float: left"
                            data-bs-original-title="Edit" data-bs-toggle="modal" id="create_btn"
                            data-bs-target=".create_modal">+ @lang('common.send_notification')</button>
                    {{--                @if(auth()->user()->hasAnyPermission(['delete_country']))--}}
                    <button disabled="disabled" id="delete_btn" class="delete-btn btn btn-danger"><i
                            class="fa fa-lg fa-trash-o"></i> @lang('common.delete')</button>
                    {{--                @endif--}}
                </div>
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
                            <th>@lang('common.title')</th>
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
                        <h1 class="mb-1">@lang('common.add') @lang('common.notification')</h1>
                    </div>
                    <form id="create_form" data-reset="true" method="POST" class="row gy-1 pt-75 ajax_form"
                          onsubmit="return false" enctype="multipart/form-data">
                        @csrf
                        @foreach(locales() as $key => $value)
                            <div class="col-12 col-md-12">
                                <label class="form-label"
                                       for="title_{{ $key }}">{{ __('common.title') . ' - ' . __('common.'.$value) }}</label>
                                <input type="text" id="title_{{ $key }}" name="title_{{ $key }}" class="form-control"
                                       placeholder="{{ __('common.title') . ' - ' . __('common.'.$value) }}"/>
                                @if($errors->has('title_' . $key))
                                    <div
                                        style="color: red">{{ $errors->first('title_' . $key) }}</div>
                                @endif
                            </div>
                        @endforeach
                        @foreach(locales() as $key => $value)
                            <div class="col-12 col-md-12">
                                <label class="form-label"
                                       for="message_{{ $key }}">{{ __('common.message') . ' - ' . __('common.'.$value) }}</label>
                                <textarea type="text" id="message_{{ $key }}" name="message_{{ $key }}"
                                          class="form-control"
                                          style="height: 150px !important;"
                                          placeholder="{{ __('common.message') . ' - ' . __('common.'.$value) }}"></textarea>
                                @if($errors->has('message_' . $key))
                                    <div
                                        style="color: red">{{ $errors->first('message_' . $key) }}</div>
                                @endif
                            </div>
                        @endforeach
                        <div class="col-12 col-md-12">
                            <label class="form-label"
                                   for="notification_according_to">{{ __('common.notification_according_to') }}</label>
                            <select class="form-control" id="notification_according_to" name="notification_according_to">
                                <option selected disabled>@lang('common.choose')</option>
                                <option value="1">@lang('common.city')</option>
                                <option value="2">@lang('common.user')</option>
                            </select>
                            @if($errors->has('notification_according_to'))
                                <div
                                    style="color: red">{{ $errors->first('notification_according_to') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-12" id="cities_div" style="display: none">
                            <label class="form-label"
                                   for="city_id">{{ __('common.cities') }}</label>
                            <select class="form-control select2" id="city_id" name="city_id[]" multiple>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}">{{ $city->name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('city_id'))
                                <div
                                    style="color: red">{{ $errors->first('city_id') }}</div>
                            @endif
                            <div class="demo-inline-spacing">
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" id="customSwitch1" value="1" name="all_cities"/>
                                    <label class="form-check-label" for="customSwitch1">@lang('common.all')</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-12" id="users_div" style="display: none">
                            <label class="form-label"
                                   for="user_id">{{ __('common.users') }}</label>
                            <select class="form-control select2" id="user_id" name="user_id[]" multiple>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('user_id'))
                                <div
                                    style="color: red">{{ $errors->first('user_id') }}</div>
                            @endif
                            <div class="demo-inline-spacing">
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" name="all_users" value="1" id="customSwitch2" />
                                    <label class="form-check-label" for="customSwitch2">@lang('common.all')</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-12">
                            <label class="form-label"
                                   for="device_type">{{ __('common.device_type') }}</label>
                            <select class="form-control" id="device_type" name="device_type">
                                <option selected disabled>@lang('common.choose')</option>
                                <option value="3">@lang('common.all')</option>
                                <option value="1">@lang('common.android')</option>
                                <option value="2">@lang('common.ios')</option>
                            </select>
                            @if($errors->has('device_type'))
                                <div
                                    style="color: red">{{ $errors->first('device_type') }}</div>
                            @endif
                        </div>
                        <div class="col-12 text-center mt-2 pt-50">
                            <button type="submit" class="btn btn-primary me-1 submit_btn"
                                    form="create_form">@lang('common.send')</button>
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
                        <h1 class="mb-1">@lang('common.notification')</h1>
                    </div>
                    <form id="editUserForm" data-reset="true" method="POST" class="row gy-1 pt-75 ajax_form"
                          onsubmit="return false" enctype="multipart/form-data">
                        @foreach(locales() as $key => $value)
                            <div class="col-12 col-md-12">
                                <label class="form-label"
                                       for="edit_title_{{ $key }}">{{ __('common.title') . ' - ' . __('common.'.$value) }}</label>
                                <input type="text" id="edit_title_{{ $key }}" name="title_{{ $key }}" class="form-control" readonly
                                       placeholder="{{ __('common.title') . ' - ' . __('common.'.$value) }}"/>
                                @if($errors->has('title_' . $key))
                                    <div
                                        style="color: red">{{ $errors->first('title_' . $key) }}</div>
                                @endif
                            </div>
                        @endforeach
                        @foreach(locales() as $key => $value)
                            <div class="col-12 col-md-12">
                                <label class="form-label"
                                       for="edit_message_{{ $key }}">{{ __('common.message') . ' - ' . __('common.'.$value) }}</label>
                                <textarea type="text" id="edit_message_{{ $key }}" name="message_{{ $key }}" readonly
                                          class="form-control"
                                          style="height: 150px !important;"
                                          placeholder="{{ __('common.message') . ' - ' . __('common.'.$value) }}"></textarea>
                                @if($errors->has('message_' . $key))
                                    <div
                                        style="color: red">{{ $errors->first('message_' . $key) }}</div>
                                @endif
                            </div>
                        @endforeach
                        <div class="col-12 text-center mt-2 pt-50">
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
    <script>
        var url = '{{ url(app()->getLocale() . "/tmg/notifications") }}/';

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
                    d.title = $('#s_title').val();
                    d.user_name = $('#s_user_name').val();
                    d.user_mobile = $('#s_user_mobile').val();
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
                {data: 'title', name: 'title', orderable: false, searchable: false},
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

                $('#edit_title_ar').val(button.data('title_ar'));
                $('#edit_title_en').val(button.data('title_en'));
                $('#edit_message_ar').val(button.data('message_ar'));
                $('#edit_message_en').val(button.data('message_en'));
            });

            $(document).on('click', '#create_btn', function (event) {
                $('#create_form').attr('action', '{{ route('notifications.store') }}');
            });
        });

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
    <script>
        $('#notification_according_to').on('change', function (){
            var value = $(this).val();
            if(value == 1){
                $('#cities_div').show();
                $('#users_div').hide();
                $('#user_id').val(null).trigger('change');
            }else {
                $('#cities_div').hide();
                $('#users_div').show();
                $('#city_id').prop('selectedIndex', 0);
            }
        });

        $('#customSwitch1').on('change', function (){
            if($(this).is(":checked")){
                $("#city_id").val("").trigger('change')
                $('#city_id').prop('disabled', true);
            }else{
                $('#city_id').prop('disabled', false);
            }
        });

        $('#customSwitch2').on('change', function (){
            if($(this).is(":checked")){
                $("#user_id").val("").trigger('change')
                $('#user_id').prop('disabled', true);
            }else{
                $('#user_id').prop('disabled', false);
            }
        });
    </script>
@endsection

