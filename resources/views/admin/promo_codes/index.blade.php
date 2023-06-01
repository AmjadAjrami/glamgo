@extends('admin.layouts.app')

@section('main-content')
    <link type="text/css" rel="stylesheet"
          href="{{ asset('app-assets/image-uploader-master/dist/image-uploader.min.css') }}">

    <style>
        .select2 {
            visibility: visible !important;
        }
    </style>
    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">@lang('common.promo_codes')</h4>
                </div>
                <div class="card-body">
                    @if(auth()->user()->hasAnyPermission(['add_promo_code']))
                        <button type="button" class="btn btn-outline-primary add_btn" style="float: left"
                                id="create_btn"
                                data-bs-original-title="Edit" data-bs-toggle="modal"
                                data-bs-target=".create_modal">+ @lang('common.add')</button>
                    @endif
                    @if(auth()->user()->hasAnyPermission(['edit_promo_code_status']))
                        <button value="1" disabled="disabled" id="status_btn" class="status_btn btn btn-dark">
                            @lang('common.activate')
                        </button>
                        <button value="0" disabled="disabled" id="status_btn" class="status_btn btn btn-warning">
                            @lang('common.deactivate')
                        </button>
                    @endif
                    @if(auth()->user()->hasAnyPermission(['delete_promo_code']))
                        <button disabled="disabled" id="delete_btn" class="delete-btn btn btn-danger"><i
                                class="fa fa-lg fa-trash-o"></i> @lang('common.delete')</button>
                    @endif
                </div>
                {{--                <form id="search_form" style="margin-right: 25px;margin-top: 30px">--}}
                {{--                    <h6>@lang('common.search')</h6>--}}
                {{--                    <div class="form-row">--}}
                {{--                        <div class="form-group">--}}
                {{--                            <input name="name" id="s_name" class="form-control" style="width: 15%; display: inline" placeholder="@lang('common.name')">--}}
                {{--                            <input name="email" id="s_email" class="form-control" style="width: 15%; display: inline" placeholder="@lang('common.email')">--}}
                {{--                            <select id="s_category_id" name="category_id" class="form-control"--}}
                {{--                                    style="width: 15%; display: inline">--}}
                {{--                                <option selected disabled>@lang('common.choose') @lang('common.category')</option>--}}
                {{--                                @foreach($categories as $category)--}}
                {{--                                    <option value="{{ $category->id }}">{{ $category->name }}</option>--}}
                {{--                                @endforeach--}}
                {{--                            </select>--}}
                {{--                            <select id="s_clinic_category_id" name="clinic_category_id" class="form-control"--}}
                {{--                                    style="width: 15%; display: inline">--}}
                {{--                                <option selected disabled>@lang('common.choose') @lang('common.clinic_category')</option>--}}
                {{--                                @foreach($clinic_categories as $clinic_category)--}}
                {{--                                    <option value="{{ $clinic_category->id }}">{{ $clinic_category->name }}</option>--}}
                {{--                                @endforeach--}}
                {{--                            </select>--}}
                {{--                            <select id="s_status" name="status" class="form-control"--}}
                {{--                                    style="width: 15%; display: inline">--}}
                {{--                                <option selected disabled>@lang('common.choose') @lang('common.status')</option>--}}
                {{--                                <option value="1">@lang('common.active')</option>--}}
                {{--                                <option value="0">@lang('common.inactive')</option>--}}
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
                            <th>@lang('common.code')</th>
                            <th>@lang('common.date_from')</th>
                            <th>@lang('common.date_to')</th>
                            <th>@lang('common.discount_type')</th>
                            <th>@lang('common.discount_price')</th>
                            <th>@lang('common.number_of_usage')</th>
                            <th>@lang('common.number_of_usage_for_user')</th>
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
                        <h1 class="mb-1">@lang('common.add') @lang('common.promo_code')</h1>
                    </div>
                    <form id="create_form" data-reset="true" method="POST" class="row gy-1 pt-75 ajax_form"
                          onsubmit="return false" enctype="multipart/form-data">
                        @csrf
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="code">{{ __('common.code') }}</label>
                            <input type="text" id="code" name="code" class="form-control"
                                   placeholder="{{ __('common.code') }}"/>
                            @if($errors->has('code'))
                                <div
                                    style="color: red">{{ $errors->first('code') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="number_of_usage">{{ __('common.number_of_usage') }}</label>
                            <input type="number" id="number_of_usage" name="number_of_usage" class="form-control"
                                   placeholder="{{ __('common.number_of_usage') }}" min="0"/>
                            @if($errors->has('number_of_usage'))
                                <div
                                    style="color: red">{{ $errors->first('number_of_usage') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="number_of_usage_for_user">{{ __('common.number_of_usage_for_user') }}</label>
                            <input type="number" id="number_of_usage_for_user" name="number_of_usage_for_user"
                                   class="form-control"
                                   placeholder="{{ __('common.number_of_usage_for_user') }}" min="0"/>
                            @if($errors->has('number_of_usage_for_user'))
                                <div
                                    style="color: red">{{ $errors->first('number_of_usage_for_user') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="discount_type">{{ __('common.discount_type') }}</label>
                            <select id="discount_type" name="discount_type" class="form-control select2">
                                <option selected disabled>@lang('common.choose')</option>
                                <option value="1">@lang('common.fixed_price')</option>
                                <option value="2">@lang('common.percentage')</option>
                            </select>
                            @if($errors->has('discount_type'))
                                <div
                                    style="color: red">{{ $errors->first('discount_type') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="discount">{{ __('common.discount_price') }}</label>
                            <input type="number" id="discount" name="discount" class="form-control"
                                   placeholder="{{ __('common.discount_price') }}" min="0"/>
                            @if($errors->has('discount'))
                                <div
                                    style="color: red">{{ $errors->first('discount') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="date_from">{{ __('common.date_from') }}</label>
                            <input type="date" id="date_from" name="date_from" class="form-control"
                                   placeholder="{{ __('common.date_from') }}"/>
                            @if($errors->has('date_from'))
                                <div
                                    style="color: red">{{ $errors->first('date_from') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="date_to">{{ __('common.date_to') }}</label>
                            <input type="date" id="date_to" name="date_to" class="form-control"
                                   placeholder="{{ __('common.date_to') }}"/>
                            @if($errors->has('date_to'))
                                <div
                                    style="color: red">{{ $errors->first('date_to') }}</div>
                            @endif
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
                        <h1 class="mb-1">@lang('common.edit') @lang('common.promo_code')</h1>
                    </div>
                    <form id="editUserForm" data-reset="true" method="POST" class="row gy-1 pt-75 ajax_form"
                          onsubmit="return false" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="edit_code">{{ __('common.code') }}</label>
                            <input type="text" id="edit_code" name="code" class="form-control"
                                   placeholder="{{ __('common.code') }}"/>
                            @if($errors->has('code'))
                                <div
                                    style="color: red">{{ $errors->first('code') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="edit_number_of_usage">{{ __('common.number_of_usage') }}</label>
                            <input type="number" id="edit_number_of_usage" name="number_of_usage" class="form-control"
                                   placeholder="{{ __('common.number_of_usage') }}" min="0"/>
                            @if($errors->has('number_of_usage'))
                                <div
                                    style="color: red">{{ $errors->first('number_of_usage') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="edit_number_of_usage_for_user">{{ __('common.number_of_usage_for_user') }}</label>
                            <input type="number" id="edit_number_of_usage_for_user" name="number_of_usage_for_user"
                                   class="form-control"
                                   placeholder="{{ __('common.number_of_usage_for_user') }}" min="0"/>
                            @if($errors->has('number_of_usage_for_user'))
                                <div
                                    style="color: red">{{ $errors->first('number_of_usage_for_user') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="edit_discount_type">{{ __('common.discount_type') }}</label>
                            <select id="edit_discount_type" name="discount_type" class="form-control select2">
                                <option selected disabled>@lang('common.choose')</option>
                                <option value="1">@lang('common.fixed_price')</option>
                                <option value="2">@lang('common.percentage')</option>
                            </select>
                            @if($errors->has('discount_type'))
                                <div
                                    style="color: red">{{ $errors->first('discount_type') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="edit_discount">{{ __('common.discount_price') }}</label>
                            <input type="number" id="edit_discount" name="discount" class="form-control"
                                   placeholder="{{ __('common.discount_price') }}" min="0"/>
                            @if($errors->has('discount'))
                                <div
                                    style="color: red">{{ $errors->first('discount') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="edit_date_from">{{ __('common.date_from') }}</label>
                            <input type="date" id="edit_date_from" name="date_from" class="form-control"
                                   placeholder="{{ __('common.date_from') }}"/>
                            @if($errors->has('date_from'))
                                <div
                                    style="color: red">{{ $errors->first('date_from') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="edit_date_to">{{ __('common.date_to') }}</label>
                            <input type="date" id="edit_date_to" name="date_to" class="form-control"
                                   placeholder="{{ __('common.date_to') }}"/>
                            @if($errors->has('date_to'))
                                <div
                                    style="color: red">{{ $errors->first('date_to') }}</div>
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
    <script>
        var url = '{{ url(app()->getLocale() . "/tmg/promo_codes/") }}/';

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
                    d.category_id = $('#s_category_id').val();
                    d.clinic_category_id = $('#s_clinic_category_id').val();
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
                {data: 'code', name: 'code', orderable: false, searchable: false},
                {data: 'date_from', name: 'date_from', orderable: false, searchable: false},
                {data: 'date_to', name: 'date_to', orderable: false, searchable: false},
                {data: 'discount_type_text', name: 'discount_type_text', orderable: false, searchable: false},
                {data: 'discount', name: 'discount', orderable: false, searchable: false},
                {data: 'number_of_usage', name: 'number_of_usage', orderable: false, searchable: false},
                {
                    data: 'number_of_usage_for_user',
                    name: 'number_of_usage_for_user',
                    orderable: false,
                    searchable: false
                },
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

            $(document).on('click', '.edit_btn', function (event) {
                var button = $(this);
                var id = button.data('id');

                $('#editUserForm').attr('action', url + id + '/update');
                $('#edit_code').val(button.data('code'));
                $('#edit_number_of_usage').val(button.data('number_of_usage'));
                $('#edit_number_of_usage_for_user').val(button.data('number_of_usage_for_user'));
                $('#edit_discount_type').val(button.data('discount_type'));
                $('#edit_discount_type').trigger('change');
                $('#edit_discount').val(button.data('discount'));
                $('#edit_date_from').val(button.data('date_from'));
                $('#edit_date_to').val(button.data('date_to'));
            });

            $(document).on('click', '#create_btn', function (event) {
                $('#create_form').attr('action', '{{ route('promo_codes.store') }}');
            });
        });

        function add_remove(id) {

            $(this).removeClass('btn btn-dark');
            $(this).addClass('btn btn-warning');
            $.ajaxSetup({
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
            });

            $.ajax({
                url: '{{ url(app()->getLocale() . '/tmg/promo_codes/update_status/') }}/' + id,
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

