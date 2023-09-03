@extends('home_service.layouts.app')

@section('main-content')
    <style>
        .select2 {
            visibility: visible !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow b{
            display: none;
        }
    </style>
    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">@lang('common.services')</h4>
                </div>
                <div class="card-body">
                    <button type="button" class="btn btn-outline-primary add_btn" style="float: left" id="create_btn"
                            data-bs-original-title="Edit" data-bs-toggle="modal"
                            data-bs-target=".create_modal">+ @lang('common.add')</button>
                    {{--                @if(auth()->user()->hasAnyPermission(['edit_country_status']))--}}
                    <button value="1" disabled="disabled" id="status_btn" class="status_btn btn btn-dark">
                        @lang('common.activate')
                    </button>
                    <button value="0" disabled="disabled" id="status_btn" class="status_btn btn btn-warning">
                        @lang('common.deactivate')
                    </button>
                    {{--                @endif--}}
                    {{--                @if(auth()->user()->hasAnyPermission(['delete_country']))--}}
                    <button disabled="disabled" id="delete_btn" class="delete-btn btn btn-danger"><i
                            class="fa fa-lg fa-trash-o"></i> @lang('common.delete')</button>
                    {{--                @endif--}}
                </div>
                <form id="search_form" style="margin-right: 25px;margin-top: 30px">
                    <h6>@lang('common.search')</h6>
                    <div class="form-row">
                        <div class="form-group">
                            <input name="name" id="s_name" class="form-control" style="width: 15%; display: inline"
                                   placeholder="@lang('common.name')">
                            <select id="s_service_type_id" name="service_type_id" class="form-control"
                                    style="width: 15%; display: inline">
                                <option selected disabled>@lang('common.choose') @lang('common.service_type')</option>
                                @foreach($service_types as $service_type)
                                    <option value="{{ $service_type->id }}">{{ $service_type->name }}</option>
                                @endforeach
                            </select>
                            {{--                            <select id="s_status" name="status" class="form-control"--}}
                            {{--                                    style="width: 15%; display: inline">--}}
                            {{--                                <option selected disabled>@lang('common.choose') @lang('common.status')</option>--}}
                            {{--                                <option value="1">@lang('common.active')</option>--}}
                            {{--                                <option value="0">@lang('common.inactive')</option>--}}
                            {{--                            </select>--}}
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
                            <th>@lang('common.service_type')</th>
                            <th>@lang('common.service_category')</th>
                            <th>@lang('common.price')</th>
                            <th>@lang('common.discount_price')</th>
                            <th>@lang('common.reservation_completed')</th>
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
                        <h1 class="mb-1">@lang('common.add') @lang('common.service')</h1>
                    </div>
                    <form id="create_form" data-reset="true" method="POST" class="row gy-1 pt-75 ajax_form"
                          onsubmit="return false" enctype="multipart/form-data">
                        @csrf
                        @foreach(locales() as $key => $value)
                            <div class="col-12 col-md-6">
                                <label class="form-label"
                                       for="name_{{ $key }}">{{ __('common.name') . ' - ' . __('common.'.$value) }}</label>
                                <input type="text" id="name_{{ $key }}" name="name_{{ $key }}" class="form-control"
                                       placeholder="{{ __('common.name') . ' - ' . __('common.'.$value) }}"/>
                                @if($errors->has('name_'.$key))
                                    <div
                                        style="color: red">{{ $errors->first('name_'.$key) }}</div>
                                @endif
                            </div>
                        @endforeach
                        @foreach(locales() as $key => $value)
                            <div class="col-12 col-md-12">
                                <label class="form-label"
                                       for="bio_{{ $key }}">{{ __('common.bio') . ' - ' . __('common.'.$value) }}</label>
                                <textarea type="text" id="bio_{{ $key }}" name="description_{{ $key }}" class="form-control"
                                          style="height: 150px;"
                                          placeholder="{{ __('common.bio') . ' - ' . __('common.'.$value) }}"></textarea>
                                @if($errors->has('description_'.$key))
                                    <div
                                        style="color: red">{{ $errors->first('description_'.$key) }}</div>
                                @endif
                            </div>
                        @endforeach
                        <input type="hidden" name="service_category" value="2">
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="service_type_id">{{ __('common.service_type') }}</label>
                            <select id="service_type_id" name="service_type_id" class="form-control select2">
                                <option selected disabled>@lang('common.choose')</option>
                                @foreach($service_types as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('service_type_id'))
                                <div
                                    style="color: red">{{ $errors->first('service_type_id') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="execution_time">{{ __('common.execution_time') . ' (' . __('common.in_minutes') . ')' }}</label>
                            <input type="number" id="execution_time" name="execution_time" class="form-control" min="0"
                                   placeholder="{{ __('common.execution_time') }}"/>
                            @if($errors->has('execution_time'))
                                <div
                                    style="color: red">{{ $errors->first('execution_time') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="price">{{ __('common.price') }}</label>
                            <input type="number" id="price" name="price" class="form-control" min="0"
                                   placeholder="{{ __('common.price') }}"/>
                            @if($errors->has('price'))
                                <div
                                    style="color: red">{{ $errors->first('price') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-check form-check-inline" style="margin-top: 35px">
                                <input class="form-check-input" type="checkbox" name="has_discount" id="has_discount" value="0"/>
                                <label class="form-check-label" for="has_discount">{{ __('common.has_discount') }}</label>
                            </div>
                            @if($errors->has('has_discount'))
                                <div
                                    style="color: red">{{ $errors->first('has_discount') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6" id="discount_price_div" style="display: none">
                            <label class="form-label"
                                   for="discount_price">{{ __('common.discount_price') }}</label>
                            <input type="number" id="discount_price" name="discount_price" class="form-control" min="0"
                                   placeholder="{{ __('common.discount_price') }}"/>
                            @if($errors->has('discount_price'))
                                <div
                                    style="color: red">{{ $errors->first('discount_price') }}</div>
                            @endif
                        </div>
                        <div class="col-12">
                            <label class="form-label"
                                   for="end_date">{{ __('common.image') }}</label>
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
                                            <input type="file" name="image" accept="image/*">
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
                        <h1 class="mb-1">@lang('common.edit') @lang('common.salon')</h1>
                    </div>
                    <form id="editUserForm" data-reset="true" method="POST" class="row gy-1 pt-75 ajax_form"
                          onsubmit="return false" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        @foreach(locales() as $key => $value)
                            <div class="col-12 col-md-6">
                                <label class="form-label"
                                       for="edit_name_{{ $key }}">{{ __('common.name') . ' - ' . __('common.'.$value) }}</label>
                                <input type="text" id="edit_name_{{ $key }}" name="name_{{ $key }}" class="form-control"
                                       placeholder="{{ __('common.name') . ' - ' . __('common.'.$value) }}"/>
                                @if($errors->has('name_'.$key))
                                    <div
                                        style="color: red">{{ $errors->first('name_'.$key) }}</div>
                                @endif
                            </div>
                        @endforeach
                        @foreach(locales() as $key => $value)
                            <div class="col-12 col-md-12">
                                <label class="form-label"
                                       for="edit_description_{{ $key }}">{{ __('common.bio') . ' - ' . __('common.'.$value) }}</label>
                                <textarea type="text" id="edit_description_{{ $key }}" name="description_{{ $key }}"
                                          class="form-control" style="height: 150px;"
                                          placeholder="{{ __('common.bio') . ' - ' . __('common.'.$value) }}"></textarea>
                                @if($errors->has('description_'.$key))
                                    <div
                                        style="color: red">{{ $errors->first('description_'.$key) }}</div>
                                @endif
                            </div>
                        @endforeach
                        <input type="hidden" name="service_category" value="2">
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="edit_service_type_id">{{ __('common.service_type') }}</label>
                            <select id="edit_service_type_id" name="service_type_id" class="form-control select2">
                                <option selected disabled>@lang('common.choose')</option>
                                @foreach($service_types as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('service_type_id'))
                                <div
                                    style="color: red">{{ $errors->first('service_type_id') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="edit_execution_time">{{ __('common.execution_time') . ' (' . __('common.in_minutes') . ')' }}</label>
                            <input type="number" id="edit_execution_time" name="execution_time" class="form-control" min="0"
                                   placeholder="{{ __('common.execution_time') }}"/>
                            @if($errors->has('execution_time'))
                                <div
                                    style="color: red">{{ $errors->first('execution_time') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="edit_price">{{ __('common.price') }}</label>
                            <input type="number" id="edit_price" name="price" class="form-control" min="0"
                                   placeholder="{{ __('common.price') }}"/>
                            @if($errors->has('price'))
                                <div
                                    style="color: red">{{ $errors->first('price') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-check form-check-inline" style="margin-top: 35px">
                                <input class="form-check-input" type="checkbox" name="has_discount" id="edit_has_discount"/>
                                <label class="form-check-label" for="edit_has_discount">{{ __('common.has_discount') }}</label>
                            </div>
                            @if($errors->has('has_discount'))
                                <div
                                    style="color: red">{{ $errors->first('has_discount') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6" id="edit_discount_price_div" style="display: none">
                            <label class="form-label"
                                   for="edit_discount_price">{{ __('common.discount_price') }}</label>
                            <input type="number" id="edit_discount_price" name="discount_price" class="form-control" min="0"
                                   placeholder="{{ __('common.discount_price') }}"/>
                            @if($errors->has('discount_price'))
                                <div
                                    style="color: red">{{ $errors->first('discount_price') }}</div>
                            @endif
                        </div>
                        <div class="col-12">
                            <label class="form-label"
                                   for="end_date">{{ __('common.image') }}</label>
                            <br>
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;"
                                     data-trigger="fileinput">
                                    <img src="{{ asset('placeholder.jpg') }}" id="edit_image" alt="...">
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail"
                                     style="max-width: 200px; max-height: 150px"></div>
                                <div>
                                        <span class="btn btn-white btn-file">
                                            <span class="fileinput-new">@lang('common.chooseImage')</span>
                                            <span class="fileinput-exists">@lang('common.change')</span>
                                            <input type="file" name="image" accept="image/*">
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

@endsection

@section('scripts')
    <script>
        $(document).on('change', '#has_discount', function (){
            var value = $(this).val();

            if (value == 0){
                $('#discount_price_div').show();
                $(this).val(1);
            }else{
                $('#discount_price_div').hide();
                $(this).val(0);
            }
        });

        $(document).on('change', '#edit_has_discount', function (){
            var value = $(this).val();

            if (value == 0){
                $('#edit_discount_price_div').show();
                $(this).val(1);
            }else{
                $('#edit_discount_price_div').hide();
                $(this).val(0);
            }
        });

        var url = '{{ url(app()->getLocale() . "/h-service/home_service_services/") }}/';

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
                    d.service_type_id = $('#s_service_type_id').val();
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
                {data: 'service_type_name', name: 'service_type_name', orderable: false, searchable: false},
                {data: 'service_category_name', name: 'service_category_name', orderable: false, searchable: false},
                {data: 'price', name: 'price', orderable: false, searchable: false},
                {data: 'discount_price', name: 'discount_price', orderable: false, searchable: false},
                {
                    "render": function (data, type, full, meta) {
                        var is_checked = '';
                        if(full.is_completed == 1){
                            is_checked = 'checked';
                        }
                        return '<div class="d-flex flex-column">'+
                            '<label class="form-check-label mb-50" for="is_completed_'+ full.id +'"></label>'+
                            '<div class="form-check form-check-success form-switch">'+
                            '<input type="checkbox" class="form-check-input is_completed" id="is_completed_'+ full.id +'" '+ is_checked +' data-id="'+ full.id +'" data-is_completed="'+ full.is_completed +'"/>'+
                            '</div>'+
                            '</div>';
                    }, orderable: false, searchable: false
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
                $('#edit_name_ar').val(button.data('name_ar'));
                $('#edit_name_en').val(button.data('name_en'));
                $('#edit_description_ar').val(button.data('description_ar'));
                $('#edit_description_en').val(button.data('description_en'));
                $('#edit_service_type_id').val(button.data('service_type_id'));
                $('#edit_service_type_id').trigger('change');
                $('#edit_service_category').val(button.data('service_category'));
                $('#edit_execution_time').val(button.data('execution_time'));
                $('#edit_price').val(button.data('price'));

                if (button.data('has_discount') == 1){
                    $('#edit_discount_price_div').show();
                    $('#edit_has_discount').val(1);
                    $('#edit_has_discount').attr('checked', true);
                }else{
                    $('#edit_discount_price_div').hide();
                    $('#edit_has_discount').val(0);
                }

                $('#edit_discount_price').val(button.data('discount_price'));

                $('#edit_mobile').val(button.data('mobile'));
                $('#edit_image').attr('src', button.data('image'));
            });

            $(document).on('click', '#create_btn', function (event) {
                $('#create_form').attr('action', '{{ route('home_service_services.store') }}');
            });
        });

        function add_remove(id) {

            $(this).removeClass('btn btn-dark');
            $(this).addClass('btn btn-warning');
            $.ajaxSetup({
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
            });

            $.ajax({
                url: '{{ url(app()->getLocale() . '/h-service/home_service_services/update_status/') }}/' + id,
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

        $(document).on('change', '.is_completed', function () {
            var id = $(this).data('id');
            var is_completed = $(this).data('is_completed');

            $.ajaxSetup({
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
            });

            $.ajax({
                url: '{{ url(app()->getLocale() . '/h-service/home_service_services/update_is_completed_status') }}',
                method: 'PUT',
                data: {
                    id: id,
                    is_completed: is_completed == 1 ? 0 : 1,
                },
                success: function (data) {
                    dt_adv_filter.draw();
                    toastr.success('@lang('common.done_successfully')');
                }
            });
        })
    </script>
@endsection

