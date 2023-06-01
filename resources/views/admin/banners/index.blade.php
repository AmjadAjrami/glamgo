@extends('admin.layouts.app')

@section('main-content')
    <link type="text/css" rel="stylesheet"
          href="{{ asset('app-assets/image-uploader-master/dist/image-uploader.min.css') }}">

    {{--    <style>--}}
    {{--        tbody > tr > td:hover {--}}
    {{--            cursor: grab !important;--}}
    {{--        }--}}
    {{--    </style>--}}

    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">@lang('common.banners')</h4>
                </div>
                {{--                <div id="google_translate_element"></div>--}}
                <div class="card-body">
                    @if(auth()->user()->hasAnyPermission(['add_banner']))
                        <button type="button" class="btn btn-outline-primary add_btn" style="float: left"
                                data-bs-original-title="Edit" data-bs-toggle="modal" id="create_btn"
                                data-bs-target=".create_modal">+ @lang('common.add')</button>
                    @endif
                    @if(auth()->user()->hasAnyPermission(['edit_banner_status']))
                        <button value="1" disabled="disabled" id="status_btn" class="status_btn btn btn-dark">
                            @lang('common.activate')
                        </button>
                        <button value="0" disabled="disabled" id="status_btn" class="status_btn btn btn-warning">
                            @lang('common.deactivate')
                        </button>
                    @endif
                    @if(auth()->user()->hasAnyPermission(['delete_banner']))
                        <button disabled="disabled" id="delete_btn" class="delete-btn btn btn-danger"><i
                                class="fa fa-lg fa-trash-o"></i> @lang('common.delete')</button>
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
                        <h1 class="mb-1">@lang('common.add') @lang('common.banner')</h1>
                    </div>
                    <form id="create_form" data-reset="true" method="POST" class="row gy-1 pt-75 ajax_form"
                          onsubmit="return false" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="type" value="{{ $type }}">
                        @foreach(locales() as $key => $value)
                            <div class="col-12 col-md-6">
                                <label class="form-label"
                                       for="title_{{ $key }}">{{ __('common.title') . ' - ' . __('common.'.$value) }}</label>
                                <input type="text" id="title_{{ $key }}" name="title_{{ $key }}" class="form-control"
                                       placeholder="{{ __('common.title') . ' - ' . __('common.'.$value) }}"
                                       data-msg="Please enter country name"/>
                                @if($errors->has('title_'.$key))
                                    <div
                                        style="color: red">{{ $errors->first('title_'.$key) }}</div>
                                @endif
                            </div>
                        @endforeach
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="start_date">{{ __('common.start_date') }}</label>
                            <input type="date" id="start_date" name="start_date" class="form-control"
                                   placeholder="{{ __('common.start_date') }}"/>
                            @if($errors->has('start_date'))
                                <div
                                    style="color: red">{{ $errors->first('start_date') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="end_date">{{ __('common.end_date') }}</label>
                            <input type="date" id="end_date" name="end_date" class="form-control"
                                   placeholder="{{ __('common.end_date') }}"/>
                            @if($errors->has('end_date'))
                                <div
                                    style="color: red">{{ $errors->first('end_date') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-12">
                            <label class="form-label"
                                   for="banner_type">{{ __('common.banner_type') }}</label>
                            <select class="form-control" id="banner_type" name="banner_type">
                                <option selected disabled>@lang('common.choose')</option>
                                <option value="1">@lang('common.internal')</option>
                                <option value="2">@lang('common.external')</option>
                            </select>
                            @if($errors->has('banner_type'))
                                <div
                                    style="color: red">{{ $errors->first('banner_type') }}</div>
                            @endif
                        </div>
                        @if($type == 3)
                            <div class="col-12 col-md-12">
                                <label class="form-label"
                                       for="banner_for">{{ __('common.banner_for') }}</label>
                                <select class="form-control" id="banner_for" name="banner_for">
                                    <option selected disabled>@lang('common.choose')</option>
                                    <option value="4">@lang('common.men')</option>
                                    <option value="5">@lang('common.women')</option>
                                </select>
                                @if($errors->has('banner_for'))
                                    <div
                                        style="color: red">{{ $errors->first('banner_for') }}</div>
                                @endif
                            </div>
                        @endif
                        @if($type == 2)
                            <div class="col-12 col-md-12" id="salon_artist_div" style="display: none">
                                <label class="form-label"
                                       for="user_type">{{ __('common.user_type') }}</label>
                                <select class="form-control" id="user_type" name="user_type">
                                    <option selected disabled>@lang('common.choose')</option>
                                    <option value="1">@lang('common.salon')</option>
                                    <option value="2">@lang('common.artist')</option>
                                </select>
                                @if($errors->has('banner_type'))
                                    <div
                                        style="color: red">{{ $errors->first('banner_type') }}</div>
                                @endif
                            </div>
                        @endif
                        @if($type == 1)
                            <div class="col-12 col-md-12" id="references_div" style="display: none">
                                <label class="form-label"
                                       for="reference_id">{{ __('common.salon') }}</label>
                                <select class="form-control" id="reference_id" name="reference_id">
                                    <option selected disabled>@lang('common.choose')</option>
                                    @foreach($references as $reference)
                                        <option
                                            value="{{ $reference->id }}">{{ $type == 3 ? $reference->title : $reference->name }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('reference_id'))
                                    <div
                                        style="color: red">{{ $errors->first('reference_id') }}</div>
                                @endif
                            </div>
                        @elseif($type == 2)
                            <div class="col-12 col-md-12" id="salons_div" style="display: none">
                                <label class="form-label"
                                       for="reference_id">{{ __('common.salon') }}</label>
                                <select class="form-control" id="reference_id" name="reference_id">
                                    <option selected disabled>@lang('common.choose')</option>
                                    @foreach($salons as $salon)
                                        <option
                                            value="{{ $salon->id }}">{{ $salon->name }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('reference_id'))
                                    <div
                                        style="color: red">{{ $errors->first('reference_id') }}</div>
                                @endif
                            </div>
                            <div class="col-12 col-md-12" id="artists_div" style="display: none">
                                <label class="form-label"
                                       for="reference_id">{{ __('common.artist') }}</label>
                                <select class="form-control" id="reference_id" name="reference_id">
                                    <option selected disabled>@lang('common.choose')</option>
                                    @foreach($artists as $artist)
                                        <option
                                            value="{{ $artist->id }}">{{ $artist->name }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('reference_id'))
                                    <div
                                        style="color: red">{{ $errors->first('reference_id') }}</div>
                                @endif
                            </div>
                        @elseif($type == 3)
                            <div class="col-12 col-md-12" id="references_div" style="display: none">
                                <label class="form-label"
                                       for="reference_id">{{ __('common.product') }}</label>
                                <select class="form-control" id="reference_id" name="reference_id">
                                    <option selected disabled>@lang('common.choose')</option>
                                    @foreach($references as $reference)
                                        <option
                                            value="{{ $reference->id }}">{{ $type == 3 ? $reference->title : $reference->name }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('reference_id'))
                                    <div
                                        style="color: red">{{ $errors->first('reference_id') }}</div>
                                @endif
                            </div>
                        @endif
                        <div class="col-12 col-md-12 external" id="link_div" style="display: none">
                            <label class="form-label"
                                   for="link">{{ __('common.link') }}</label>
                            <input type="text" id="link" name="link" class="form-control"
                                   placeholder="{{ __('common.link') }}"/>
                            @if($errors->has('link'))
                                <div
                                    style="color: red">{{ $errors->first('link') }}</div>
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
                        <h1 class="mb-1">@lang('common.edit') @lang('common.banner')</h1>
                    </div>
                    <form id="editUserForm" data-reset="true" method="POST" class="row gy-1 pt-75 ajax_form"
                          onsubmit="return false" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="type" value="{{ $type }}">
                        @foreach(locales() as $key => $value)
                            <div class="col-12 col-md-6">
                                <label class="form-label"
                                       for="edit_title_{{ $key }}">{{ __('common.title') . ' - ' . __('common.'.$value) }}</label>
                                <input type="text" id="edit_title_{{ $key }}" name="title_{{ $key }}"
                                       class="form-control"
                                       placeholder="{{ __('common.title') . ' - ' . __('common.'.$value) }}"
                                       data-msg="Please enter country name"/>
                                @if($errors->has('title_'.$key))
                                    <div
                                        style="color: red">{{ $errors->first('title_'.$key) }}</div>
                                @endif
                            </div>
                        @endforeach
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="edit_start_date">{{ __('common.start_date') }}</label>
                            <input type="date" id="edit_start_date" name="start_date" class="form-control"
                                   placeholder="{{ __('common.start_date') }}"/>
                            @if($errors->has('start_date'))
                                <div
                                    style="color: red">{{ $errors->first('start_date') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="edit_end_date">{{ __('common.end_date') }}</label>
                            <input type="date" id="edit_end_date" name="end_date" class="form-control"
                                   placeholder="{{ __('common.end_date') }}"/>
                            @if($errors->has('end_date'))
                                <div
                                    style="color: red">{{ $errors->first('end_date') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-12">
                            <label class="form-label"
                                   for="edit_banner_type">{{ __('common.banner_type') }}</label>
                            <select class="form-control" id="edit_banner_type" name="banner_type">
                                <option selected disabled>@lang('common.choose')</option>
                                <option value="1">@lang('common.internal')</option>
                                <option value="2">@lang('common.external')</option>
                            </select>
                            @if($errors->has('banner_type'))
                                <div
                                    style="color: red">{{ $errors->first('banner_type') }}</div>
                            @endif
                        </div>
                        @if($type == 3)
                            <div class="col-12 col-md-12">
                                <label class="form-label"
                                       for="banner_for">{{ __('common.banner_for') }}</label>
                                <select class="form-control" id="edit_banner_for" name="banner_for">
                                    <option selected disabled>@lang('common.choose')</option>
                                    <option value="4">@lang('common.men')</option>
                                    <option value="5">@lang('common.women')</option>
                                </select>
                                @if($errors->has('banner_for'))
                                    <div
                                        style="color: red">{{ $errors->first('banner_for') }}</div>
                                @endif
                            </div>
                        @endif
                        @if($type == 2)
                            <div class="col-12 col-md-12" id="edit_salon_artist_div" style="display: none">
                                <label class="form-label"
                                       for="edit_user_type">{{ __('common.user_type') }}</label>
                                <select class="form-control" id="edit_user_type" name="user_type">
                                    <option selected disabled>@lang('common.choose')</option>
                                    <option value="1">@lang('common.salon')</option>
                                    <option value="2">@lang('common.artist')</option>
                                </select>
                                @if($errors->has('user_type'))
                                    <div
                                        style="color: red">{{ $errors->first('user_type') }}</div>
                                @endif
                            </div>
                        @endif
                        @if($type == 1)
                            <div class="col-12 col-md-12" id="edit_references_div" style="display: none">
                                <label class="form-label"
                                       for="edit_reference_id">{{ __('common.salon') }}</label>
                                <select class="form-control" id="edit_reference_id" name="reference_id">
                                    <option selected disabled>@lang('common.choose')</option>
                                    @foreach($references as $reference)
                                        <option
                                            value="{{ $reference->id }}">{{ $type == 3 ? $reference->title : $reference->name }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('reference_id'))
                                    <div
                                        style="color: red">{{ $errors->first('reference_id') }}</div>
                                @endif
                            </div>
                        @elseif($type == 2)
                            <input type="hidden" name="reference_id" value="1">
                            <div class="col-12 col-md-12" id="edit_salons_div" style="display: none">
                                <label class="form-label"
                                       for="edit_reference_id_1">{{ __('common.salon') }}</label>
                                <select class="form-control" id="edit_reference_id_1" name="reference_id_1">
                                    <option selected disabled>@lang('common.choose')</option>
                                    @foreach($salons as $salon)
                                        <option
                                            value="{{ $salon->id }}">{{ $salon->name }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('reference_id_1'))
                                    <div
                                        style="color: red">{{ $errors->first('reference_id_1') }}</div>
                                @endif
                            </div>
                            <div class="col-12 col-md-12" id="edit_artists_div" style="display: none">
                                <label class="form-label"
                                       for="edit_reference_id_2">{{ __('common.artist') }}</label>
                                <select class="form-control" id="edit_reference_id_2" name="reference_id_2">
                                    <option selected disabled>@lang('common.choose')</option>
                                    @foreach($artists as $artist)
                                        <option
                                            value="{{ $artist->id }}">{{ $artist->name }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('reference_id_2'))
                                    <div
                                        style="color: red">{{ $errors->first('reference_id_2') }}</div>
                                @endif
                            </div>
                        @elseif($type == 3)
                            <div class="col-12 col-md-12" id="edit_references_div" style="display: none">
                                <label class="form-label"
                                       for="edit_reference_id">{{ __('common.product') }}</label>
                                <select class="form-control" id="edit_reference_id" name="reference_id">
                                    <option selected disabled>@lang('common.choose')</option>
                                    @foreach($references as $reference)
                                        <option
                                            value="{{ $reference->id }}">{{ $type == 3 ? $reference->title : $reference->name }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('reference_id'))
                                    <div
                                        style="color: red">{{ $errors->first('reference_id') }}</div>
                                @endif
                            </div>
                        @endif
                        <div class="col-12 col-md-12 external" id="edit_link_div" style="display: none">
                            <label class="form-label"
                                   for="edit_link">{{ __('common.link') }}</label>
                            <input type="text" id="edit_link" name="link" class="form-control"
                                   placeholder="{{ __('common.link') }}"/>
                            @if($errors->has('link'))
                                <div
                                    style="color: red">{{ $errors->first('link') }}</div>
                            @endif
                        </div>
                        <div class="col-12">
                            <label class="form-label"
                                   for="end_date">{{ __('common.image') }}</label>
                            <br>
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;"
                                     data-trigger="fileinput">
                                    <img src="{{ asset('placeholder.jpg') }}" alt="..." id="edit_flag">
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"
            integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA=="
            crossorigin="anonymous"
            referrerpolicy="no-referrer"></script>
    <script>

        $(document).ready(function (){
            $('#reference_id').select2({
                dropdownParent: $('#create_modal')
            });
            $('#edit_reference_id').select2({
                dropdownParent: $('#editUser')
            });
        })

        $('.input-images_2').imageUploader({
            imagesInputName: 'flag',
        });

        var url = '{{ url(app()->getLocale() . "/tmg/banners") }}/';

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
                url: url + '{{ $type }}' + '/indexTable',
                data: function (d) {
                    d.name = $('#s_name').val();
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

                $('#edit_title_ar').val(button.data('title_ar'));
                $('#edit_title_en').val(button.data('title_en'));
                $('#edit_start_date').val(button.data('start_date'));
                $('#edit_end_date').val(button.data('end_date'));

                if (button.data('banner_type') == 1) {
                    @if($type == 2)
                    $('#edit_salon_artist_div').show();
                    @else
                    $('#edit_references_div').show();
                    @endif
                    $('#edit_link_div').hide();
                } else if(button.data('banner_type') == 2) {
                    @if($type == 2)
                    $('#edit_salon_artist_div').hide();
                    @else
                    $('#edit_references_div').hide();
                    @endif
                    $('#edit_link_div').show();
                }

                $('#editUserForm').attr('action', url + id + '/update');
                $('#edit_banner_type').val(button.data('banner_type'));

                if (button.data('banner_type') == 1) {
                    if (button.data('type') == 1) {
                        $('#edit_reference_id').val(button.data('salon_id'));
                    } else if (button.data('type') == 2) {
                        if (button.data('salon_id') != '') {
                            $('#edit_user_type').val(1);
                            $('#edit_salons_div').show();
                            $('#edit_artists_div').hide();
                            $('#edit_reference_id_1').val(button.data('salon_id'));
                        } else {
                            $('#edit_user_type').val(2);
                            $('#edit_salons_div').hide();
                            $('#edit_artists_div').show();
                            $('#edit_reference_id_2').val(button.data('makeup_artist_id'));
                        }
                    } else if (button.data('type') == 3) {
                        console.log(button.data('product_id'));
                        $('#edit_reference_id').val(button.data('product_id'));
                    }
                }

                if ({{ $type }} == 3) {
                    $('#edit_banner_for').val(button.data('type'))
                }

                $('#edit_link').val(button.data('link'));
                $('#edit_flag').attr('src', button.data('image'));
            });

            $(document).on('click', '#create_btn', function (event) {
                $('#create_form').attr('action', '{{ route('banners.store') }}');
            });
        });

        function add_remove(id) {

            $(this).removeClass('btn btn-dark');
            $(this).addClass('btn btn-warning');
            $.ajaxSetup({
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
            });

            $.ajax({
                url: '{{ url(app()->getLocale() . '/tmg/banners/update_status/') }}/' + id,
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

        $(document).on('change', '#banner_type', function () {
            var value = $(this).val();

            if (value == 1) {
                @if($type == 2)
                $('#salon_artist_div').show();
                @else
                $('#references_div').show();
                @endif
                $('#link_div').hide();
            } else if (value == 2) {
                @if($type == 2)
                $('#salon_artist_div').hide();
                @else
                $('#references_div').hide();
                @endif
                $('#link_div').show();
            }
        });

        $(document).on('change', '#user_type', function () {
            var value = $(this).val();

            if (value == 1) {
                $('#salons_div').show();
                $('#artists_div').hide();
            } else if (value == 2) {
                $('#salons_div').hide();
                $('#artists_div').show();
            }
        });

        $(document).on('change', '#edit_banner_type', function () {
            var value = $(this).val();

            if (value == 1) {
                @if($type == 2)
                $('#edit_salon_artist_div').show();
                @else
                $('#edit_references_div').show();
                @endif
                $('#edit_link_div').hide();
            } else if (value == 2) {
                @if($type == 2)
                $('#edit_salon_artist_div').hide();
                @else
                $('#edit_references_div').hide();
                @endif
                $('#edit_link_div').show();
            }
        });

        $(document).on('change', '#edit_user_type', function () {
            var value = $(this).val();

            if (value == 1) {
                $('#edit_salons_div').show();
                $('#edit_artists_div').hide();
            } else if (value == 2) {
                $('#edit_salons_div').hide();
                $('#edit_artists_div').show();
            }
        });
    </script>
@endsection

