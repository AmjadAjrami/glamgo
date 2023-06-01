@extends('admin.layouts.app')

@section('main-content')
    <link type="text/css" rel="stylesheet"
          href="{{ asset('app-assets/image-uploader-master/dist/image-uploader.min.css') }}">

    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">@lang('common.intros')</h4>
                </div>
                <div class="card-body">
                    @if(auth()->user()->hasAnyPermission(['add_intro']))
                        <button type="button" class="btn btn-outline-primary add_btn" style="float: left"
                                data-bs-original-title="Edit" data-bs-toggle="modal" id="create_btn"
                                data-bs-target=".create_modal">+ @lang('common.add')</button>
                    @endif
                    @if(auth()->user()->hasAnyPermission(['edit_intro_status']))
                        <button value="1" disabled="disabled" id="status_btn" class="status_btn btn btn-dark">
                            @lang('common.activate')
                        </button>
                        <button value="0" disabled="disabled" id="status_btn" class="status_btn btn btn-warning">
                            @lang('common.deactivate')
                        </button>
                    @endif
                    @if(auth()->user()->hasAnyPermission(['delete_intro']))
                        <button disabled="disabled" id="delete_btn" class="delete-btn btn btn-danger"><i
                                class="fa fa-lg fa-trash-o"></i> @lang('common.delete')</button>
                    @endif
                </div>
                <form id="search_form" style="margin-right: 25px;margin-top: 30px">
                    <h6>@lang('common.search')</h6>
                    <div class="form-row">
                        <div class="form-group">
                            <select id="s_status" name="status" class="form-control"
                                    style="width: 15%; display: inline">
                                <option selected disabled>@lang('common.choose') @lang('common.status')</option>
                                <option value="1">@lang('common.active')</option>
                                <option value="0">@lang('common.inactive')</option>
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
                            <th>@lang('common.title')</th>
                            <th>@lang('common.image')</th>
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
                        <h1 class="mb-1">@lang('common.add') @lang('common.intro')</h1>
                    </div>
                    <form id="create_form" data-reset="true" method="POST" class="row gy-1 pt-75 ajax_form"
                          onsubmit="return false" enctype="multipart/form-data">
                        @csrf
                        @foreach(locales() as $key => $value)
                            <div class="col-12 col-md-6">
                                <label class="form-label"
                                       for="title_{{ $key }}">{{ __('common.title') . ' - ' . __('common.'.$value) }}</label>
                                <input type="text" id="title_{{ $key }}" name="title_{{ $key }}" class="form-control"
                                       placeholder="{{ __('common.title') . ' - ' . __('common.'.$value) }}"
                                       data-msg="Please enter country title"/>
                                @if($errors->has('title_'.$key))
                                    <div
                                        style="color: red">{{ $errors->first('title_'.$key) }}</div>
                                @endif
                            </div>
                        @endforeach
                        @foreach(locales() as $key => $value)
                            <div class="col-12 col-md-6">
                                <label class="form-label"
                                       for="description_{{ $key }}">{{ __('common.description') . ' - ' . __('common.'.$value) }}</label>
                                <input type="text" id="description_{{ $key }}" name="description_{{ $key }}"
                                       class="form-control"
                                       placeholder="{{ __('common.description') . ' - ' . __('common.'.$value) }}"
                                       data-msg="Please enter country description"/>
                                @if($errors->has('description_'.$key))
                                    <div
                                        style="color: red">{{ $errors->first('description_'.$key) }}</div>
                                @endif
                            </div>
                        @endforeach
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
                        <h1 class="mb-1">@lang('common.edit') @lang('common.intro')</h1>
                    </div>
                    <form id="editUserForm" data-reset="true" method="POST" class="row gy-1 pt-75 ajax_form"
                          onsubmit="return false" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        @foreach(locales() as $key => $value)
                            <div class="col-12 col-md-6">
                                <label class="form-label"
                                       for="edit_title_{{ $key }}">{{ __('common.title') . ' - ' . __('common.'.$value) }}</label>
                                <input type="text" id="edit_title_{{ $key }}" name="title_{{ $key }}"
                                       class="form-control"
                                       placeholder="{{ __('common.title') . ' - ' . __('common.'.$value) }}"
                                       data-msg="Please enter country title"/>
                                @if($errors->has('title_'.$key))
                                    <div
                                        style="color: red">{{ $errors->first('title_'.$key) }}</div>
                                @endif
                            </div>
                        @endforeach
                        @foreach(locales() as $key => $value)
                            <div class="col-12 col-md-6">
                                <label class="form-label"
                                       for="edit_description_{{ $key }}">{{ __('common.description') . ' - ' . __('common.'.$value) }}</label>
                                <input type="text" id="edit_description_{{ $key }}" name="description_{{ $key }}"
                                       class="form-control"
                                       placeholder="{{ __('common.description') . ' - ' . __('common.'.$value) }}"
                                       data-msg="Please enter country description"/>
                                @if($errors->has('description_'.$key))
                                    <div
                                        style="color: red">{{ $errors->first('description_'.$key) }}</div>
                                @endif
                            </div>
                        @endforeach
                        <div class="col-12">
                            <label class="form-label"
                                   for="end_date">{{ __('common.image') }}</label>
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

@endsection

@section('scripts')
    <script type="text/javascript"
            src="{{ asset('app-assets/image-uploader-master/dist/image-uploader.min.js') }}"></script>
    <script>
        $('.input-images_2').imageUploader({
            imagesInputName: 'image',
        });

        var url = '{{ url(app()->getLocale() . "/tmg/intros") }}/';

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
                {
                    "render": function (data, type, full, meta) {
                        return '<span><img src="' + full.image + '" class="rounded-circle profile-img" alt="avatar" style="width: 50px;height: 50px"></span>';
                    }, orderable: false, searchable: false
                },
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
                $('#edit_title_ar').val(button.data('title_ar'));
                $('#edit_title_en').val(button.data('title_en'));
                $('#edit_description_ar').val(button.data('description_ar'));
                $('#edit_description_en').val(button.data('description_en'));
                $('#edit_image').attr('src', button.data('image'));
            });

            $(document).on('click', '#create_btn', function (event) {
                $('#create_form').attr('action', '{{ route('intros.store') }}');
            });
        });

        function add_remove(id) {

            $(this).removeClass('btn btn-dark');
            $(this).addClass('btn btn-warning');
            $.ajaxSetup({
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
            });

            $.ajax({
                url: '{{ url(app()->getLocale() . '/tmg/intros/update_status/') }}/' + id,
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

