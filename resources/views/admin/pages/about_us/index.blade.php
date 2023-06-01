@extends('admin.layouts.app')

@section('main-content')
    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">@lang('common.about_us')</h4>
                </div>
                <div class="card-body">
                    @if(!$page)
                        <button type="button" class="btn btn-outline-primary add_btn" style="float: left"
                                id="create_btn"
                                data-bs-original-title="Edit" data-bs-toggle="modal"
                                data-bs-target=".create_modal">+ @lang('common.add')</button>
                    @endif
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
                        <h1 class="mb-1">@lang('common.add') @lang('common.about_us')</h1>
                    </div>
                    <form id="create_form" data-reset="true" method="POST" class="row gy-1 pt-75 ajax_form"
                          onsubmit="return false" enctype="multipart/form-data">
                        @csrf
                        @foreach(locales() as $key => $value)
                            <div class="form-group">
                                <label
                                    for="title_{{ $key }}">{{ __('common.title') . ' - ' . __('common.'.$value) }}</label>
                                <input type="text" name="title_{{ $key }}" class="form-control"
                                       id="title_{{ $key }}"
                                       placeholder="{{ __('common.title') . ' - ' . __('common.'.$value) }}">
                                @if($errors->has('title_'.$key))
                                    <div
                                        style="color: red">{{ $errors->first('title_'.$key) }}</div>
                                @endif
                            </div>
                        @endforeach
                        @foreach(locales() as $key => $value)
                            <div class="form-group">
                                <label
                                    for="description_{{ $key }}">{{ __('common.description') . ' - ' . __('common.'.$value) }}</label>
                                <textarea type="text" name="description_{{ $key }}" class="form-control"
                                          id="description_{{ $key }}"
                                          placeholder="{{ __('common.description') . ' - ' . __('common.'.$value) }}"></textarea>
                                @if($errors->has('description_'.$key))
                                    <div
                                        style="color: red">{{ $errors->first('description_'.$key) }}</div>
                                @endif
                            </div>
                        @endforeach
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
                        <h1 class="mb-1">@lang('common.edit') @lang('common.about_us')</h1>
                    </div>
                    <form id="editUserForm" data-reset="true" method="POST" class="row gy-1 pt-75 ajax_form"
                          onsubmit="return false" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        @foreach(locales() as $key => $value)
                            <div class="form-group">
                                <label
                                    for="edit_title_{{ $key }}">{{ __('common.title') . ' - ' . __('common.'.$value) }}</label>
                                <input type="text" name="title_{{ $key }}" class="form-control"
                                       id="edit_title_{{ $key }}"
                                       placeholder="{{ __('common.title') . ' - ' . __('common.'.$value) }}">
                                @if($errors->has('title_'.$key))
                                    <div
                                        style="color: red">{{ $errors->first('title_'.$key) }}</div>
                                @endif
                            </div>
                        @endforeach
                        @foreach(locales() as $key => $value)
                            <div class="form-group">
                                <label
                                    for="edit_description_{{ $key }}">{{ __('common.description') . ' - ' . __('common.'.$value) }}</label>
                                <textarea type="text" name="description_{{ $key }}" class="form-control"
                                          id="edit_description_{{ $key }}"
                                          placeholder="{{ __('common.description') . ' - ' . __('common.'.$value) }}"></textarea>
                                @if($errors->has('description_'.$key))
                                    <div
                                        style="color: red">{{ $errors->first('description_'.$key) }}</div>
                                @endif
                            </div>
                        @endforeach
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
        var url = '{{ url(app()->getLocale() . "/tmg/about_us") }}/';

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
                $('#edit_title_ar').val(button.data('title_ar'));
                $('#edit_title_en').val(button.data('title_en'));
                $('#edit_description_ar').val($('#description_ar_' + id).html());
                $('#edit_description_en').val($('#description_en_' + id).html());
            });

            $(document).on('click', '#create_btn', function (event) {
                $('#create_form').attr('action', '{{ route('about_us.store') }}');
            });
        });

        {{--function add_remove(id) {--}}

        {{--    $(this).removeClass('btn btn-dark');--}}
        {{--    $(this).addClass('btn btn-warning');--}}
        {{--    $.ajaxSetup({--}}
        {{--        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}--}}
        {{--    });--}}

        {{--    $.ajax({--}}
        {{--        url: '{{ url(app()->getLocale() . '/admin/offers/update_status/') }}/' + id,--}}
        {{--        method: 'PUT',--}}
        {{--        success: function (data) {--}}
        {{--            $('#btn_' + id).removeClass(data.remove);--}}
        {{--            $('#btn_' + id).addClass(data.add);--}}
        {{--            $('#btn_' + id).text(data.text);--}}
        {{--            $('#msg').html(data.message).show();--}}
        {{--            setTimeout(function () {--}}
        {{--                $("#msg").hide();--}}
        {{--            }, 5000);--}}
        {{--        }--}}
        {{--    });--}}
        {{--}--}}

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
