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
                    <h4 class="card-title">@lang('common.products')</h4>
                </div>
                <div class="card-body">
                    @if(auth()->user()->hasAnyPermission(['add_product']))
                        <button type="button" class="btn btn-outline-primary add_btn" style="float: left"
                                id="create_btn"
                                data-bs-original-title="Edit" data-bs-toggle="modal"
                                data-bs-target=".create_modal">+ @lang('common.add')</button>
                    @endif
                    @if(auth()->user()->hasAnyPermission(['edit_product_status']))
                        <button value="1" disabled="disabled" id="status_btn" class="status_btn btn btn-dark">
                            @lang('common.activate')
                        </button>
                        <button value="0" disabled="disabled" id="status_btn" class="status_btn btn btn-warning">
                            @lang('common.deactivate')
                        </button>
                    @endif
                    @if(auth()->user()->hasAnyPermission(['delete_product']))
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
                            <th>@lang('common.image')</th>
                            <th>@lang('common.title')</th>
                            <th>@lang('common.price')</th>
                            <th>@lang('common.discount_price')</th>
                            <th>@lang('common.stock')</th>
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
                        <h1 class="mb-1">@lang('common.add') @lang('common.product')</h1>
                    </div>
                    <form id="create_form" data-reset="true" method="POST" class="row gy-1 pt-75 ajax_form"
                          onsubmit="return false" enctype="multipart/form-data">
                        @csrf
                        @foreach(locales() as $key => $value)
                            <div class="col-12 col-md-6">
                                <label class="form-label"
                                       for="title_{{ $key }}">{{ __('common.title') . ' - ' . __('common.'.$value) }}</label>
                                <input type="text" id="title_{{ $key }}" name="title_{{ $key }}" class="form-control"
                                       placeholder="{{ __('common.title') . ' - ' . __('common.'.$value) }}"/>
                                @if($errors->has('title_'.$key))
                                    <div
                                        style="color: red">{{ $errors->first('title_'.$key) }}</div>
                                @endif
                            </div>
                        @endforeach
                        @foreach(locales() as $key => $value)
                            <div class="col-12 col-md-12">
                                <label class="form-label"
                                       for="description_{{ $key }}">{{ __('common.description') . ' - ' . __('common.'.$value) }}</label>
                                <textarea type="text" id="description_{{ $key }}" name="description_{{ $key }}"
                                          class="form-control"
                                          style="height: 150px;"
                                          placeholder="{{ __('common.description') . ' - ' . __('common.'.$value) }}"></textarea>
                                @if($errors->has('description_'.$key))
                                    <div
                                        style="color: red">{{ $errors->first('name_'.$key) }}</div>
                                @endif
                            </div>
                        @endforeach
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="type">{{ __('common.product_type') }}</label>
                            <select id="type" name="type" class="form-control select2">
                                <option selected disabled>@lang('common.choose')</option>
                                <option value="1">@lang('common.men_products')</option>
                                <option value="2">@lang('common.women_products')</option>
                            </select>
                            @if($errors->has('type'))
                                <div
                                    style="color: red">{{ $errors->first('type') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="category_id">{{ __('common.category') }}</label>
                            <select id="category_id" name="category_id" class="form-control select2">
                                <option selected disabled>@lang('common.choose')</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('category_id'))
                                <div
                                    style="color: red">{{ $errors->first('category_id') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-12">
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
                                <input class="form-check-input" type="checkbox" id="has_discount" name="has_discount"
                                       value="2"/>
                                <label class="form-check-label"
                                       for="has_discount">{{ __('common.has_discount') }}</label>
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
                        <div class="col-12 col-md-12">
                            <label class="form-label"
                                   for="stock">{{ __('common.stock') }}</label>
                            <input type="number" id="stock" name="stock" class="form-control" min="0"
                                   placeholder="{{ __('common.stock') }}"/>
                            @if($errors->has('stock'))
                                <div
                                    style="color: red">{{ $errors->first('stock') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6 mt-2">
                            <div class="demo-inline-spacing">
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" id="add_to_salon" value="0"/>
                                    <label class="form-check-label"
                                           for="add_to_salon">@lang('common.add_product_to_salon')</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6" id="salons_div" style="display: none">
                            <label class="form-label"
                                   for="salon_id">{{ __('common.salon') }}</label>
                            <select id="salon_id" name="salon_id" class="form-control select2">
                            </select>
                            @if($errors->has('salon_id'))
                                <div
                                    style="color: red">{{ $errors->first('salon_id') }}</div>
                            @endif
                        </div>
                        <div class="col-12">
                            <label class="form-label"
                                   for="end_date">{{ __('common.cover_image') }}</label>
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
                                            <input type="file" name="cover_image">
                                        </span>
                                    <a href="#" class="btn btn-orange fileinput-exists"
                                       data-dismiss="fileinput">@lang('common.remove')</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label"
                                   for="end_date">{{ __('common.product_images') }}</label>
                            <div class="input-images_2"></div>
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
                        <h1 class="mb-1">@lang('common.edit') @lang('common.product')</h1>
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
                                       placeholder="{{ __('common.title') . ' - ' . __('common.'.$value) }}"/>
                                @if($errors->has('title_'.$key))
                                    <div
                                        style="color: red">{{ $errors->first('title_'.$key) }}</div>
                                @endif
                            </div>
                        @endforeach
                        @foreach(locales() as $key => $value)
                            <div class="col-12 col-md-12">
                                <label class="form-label"
                                       for="edit_description_{{ $key }}">{{ __('common.description') . ' - ' . __('common.'.$value) }}</label>
                                <textarea type="text" id="edit_description_{{ $key }}" name="description_{{ $key }}"
                                          class="form-control"
                                          style="height: 150px;"
                                          placeholder="{{ __('common.description') . ' - ' . __('common.'.$value) }}"></textarea>
                                @if($errors->has('description_'.$key))
                                    <div
                                        style="color: red">{{ $errors->first('name_'.$key) }}</div>
                                @endif
                            </div>
                        @endforeach
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="edit_type">{{ __('common.product_type') }}</label>
                            <select id="edit_type" name="type" class="form-control select2">
                                <option disabled>@lang('common.choose')</option>
                                <option value="1">@lang('common.men_products')</option>
                                <option value="2">@lang('common.women_products')</option>
                            </select>
                            @if($errors->has('type'))
                                <div
                                    style="color: red">{{ $errors->first('type') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="edit_category_id">{{ __('common.category') }}</label>
                            <select id="edit_category_id" name="category_id" class="form-control select2">
                                <option selected disabled>@lang('common.choose')</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('category_id'))
                                <div
                                    style="color: red">{{ $errors->first('category_id') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-12">
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
                                <input class="form-check-input" type="checkbox" id="edit_has_discount"
                                       name="has_discount"/>
                                <label class="form-check-label"
                                       for="edit_has_discount">{{ __('common.has_discount') }}</label>
                            </div>
                            @if($errors->has('has_discount'))
                                <div
                                    style="color: red">{{ $errors->first('has_discount') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6" id="edit_discount_price_div" style="display: none">
                            <label class="form-label"
                                   for="edit_discount_price">{{ __('common.discount_price') }}</label>
                            <input type="number" id="edit_discount_price" name="discount_price" class="form-control"
                                   min="0"
                                   placeholder="{{ __('common.discount_price') }}"/>
                            @if($errors->has('discount_price'))
                                <div
                                    style="color: red">{{ $errors->first('discount_price') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-12">
                            <label class="form-label" for="edit_stock">{{ __('common.stock') }}</label>
                            <input type="number" id="edit_stock" name="stock" class="form-control" min="0"
                                   placeholder="{{ __('common.stock') }}"/>
                            @if($errors->has('stock'))
                                <div
                                    style="color: red">{{ $errors->first('stock') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6 mt-2">
                            <div class="demo-inline-spacing">
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" name="add_to_salon"
                                           id="edit_add_to_salon" value="0"/>
                                    <label class="form-check-label"
                                           for="edit_add_to_salon">@lang('common.add_product_to_salon')</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6" id="edit_salons_div" style="display: none">
                            <label class="form-label"
                                   for="edit_salon_id">{{ __('common.salon') }}</label>
                            <select id="edit_salon_id" name="salon_id" class="form-control select2">
                            </select>
                            @if($errors->has('salon_id'))
                                <div
                                    style="color: red">{{ $errors->first('salon_id') }}</div>
                            @endif
                        </div>
                        <div class="col-12">
                            <label class="form-label"
                                   for="end_date">{{ __('common.cover_image') }}</label>
                            <br>
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;"
                                     data-trigger="fileinput">
                                    <img src="{{ asset('placeholder.jpg') }}" alt="..." id="edit_cover_image">
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail"
                                     style="max-width: 200px; max-height: 150px"></div>
                                <div>
                                        <span class="btn btn-white btn-file">
                                            <span class="fileinput-new">@lang('common.chooseImage')</span>
                                            <span class="fileinput-exists">@lang('common.change')</span>
                                            <input type="file" name="cover_image">
                                        </span>
                                    <a href="#" class="btn btn-orange fileinput-exists"
                                       data-dismiss="fileinput">@lang('common.remove')</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label"
                                   for="end_date">{{ __('common.product_images') }}</label>
                            <div class="input-images_4"></div>
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
    <script type="text/javascript"
            src="{{ asset('app-assets/image-uploader-master/dist/image-uploader_2.min.js') }}"></script>

    <script>
        $(document).on('change', '#has_discount', function () {
            var value = $(this).val();

            if (value == 2) {
                $('#discount_price_div').show();
                $(this).val(1);
            } else {
                $('#discount_price_div').hide();
                document.getElementById("discount_price").value = '';
                $(this).val(2);
            }
        });

        $(document).on('change', '#edit_has_discount', function () {
            var value = $(this).val();

            if (value == 2) {
                $('#edit_discount_price_div').show();
                $(this).val(1);
            } else {
                $('#edit_discount_price_div').hide();
                document.getElementById("edit_discount_price").value = '';
                $(this).val(2);
            }
        });

        $('.input-images_2').imageUploader_2({
            imagesInputName: 'images',
        });

        var url = '{{ url(app()->getLocale() . "/tmg/products/") }}/';

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
                {
                    "render": function (data, type, full, meta) {
                        return '<span><img src="' + full.image + '" class="rounded-circle profile-img" alt="avatar" style="width: 50px;height: 50px"></span>';
                    }, orderable: false, searchable: false
                },
                {data: 'title', name: 'title', orderable: false, searchable: false},
                {data: 'price', name: 'price', orderable: false, searchable: false},
                {data: 'discount_price', name: 'discount_price', orderable: false, searchable: false},
                {data: 'stock', name: 'stock', orderable: false, searchable: false},
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
                $('.input-images_4').html('');

                var button = $(this);
                var id = button.data('id');

                $('#editUserForm').attr('action', url + id + '/update');
                $('#edit_title_ar').val(button.data('title_ar'));
                $('#edit_title_en').val(button.data('title_en'));
                $('#edit_description_ar').val(button.data('description_ar'));
                $('#edit_description_en').val(button.data('description_en'));

                $('#edit_type').val(button.data('type'));
                $('#edit_type').trigger('change');

                $('#edit_category_id').val(button.data('category_id'));
                $('#edit_category_id').trigger('change');

                $('#edit_price').val(button.data('price'));

                if (button.data('has_discount') == 1) {
                    $('#edit_discount_price_div').show();
                    $('#edit_has_discount').val(1);
                    $('#edit_has_discount').attr('checked', true);
                } else {
                    $('#edit_discount_price_div').hide();
                    $('#edit_has_discount').val(2);
                }

                $('#edit_discount_price').val(button.data('discount_price'));
                $('#edit_stock').val(button.data('stock'));
                $('#edit_cover_image').attr('src', button.data('cover_image'));

                $.ajax({
                    url: '{{ url(app()->getLocale() . '/tmg/products/') }}/' + button.data('type') + '/categories_and_salons',
                    method: 'GET',
                    success: function (data) {
                        var categories = data.categories;
                        var data_append = "<option selected disabled>@lang('common.choose')</option>";
                        $.each(categories, function (index, value) {
                            if (value.id == button.data('category_id')) {
                                data_append += '<option value=' + value.id + ' selected>' + value.name + '</option>';
                            } else {
                                data_append += '<option value=' + value.id + '>' + value.name + '</option>';
                            }
                        });

                        $('#edit_category_id').empty();
                        $('#edit_category_id').append(data_append);
                        $('#edit_category_id').trigger('change');

                        var salons = data.salons;
                        var data_append_2 = "<option selected disabled>@lang('common.choose')</option>";
                        $.each(salons, function (index, value) {
                            if (value.id == button.data('salon_id')) {
                                data_append_2 += '<option value=' + value.id + ' selected>' + value.name + '</option>';
                            } else {
                                data_append_2 += '<option value=' + value.id + '>' + value.name + '</option>';
                            }
                        });

                        $('#edit_salon_id').empty();
                        $('#edit_salon_id').append(data_append_2);
                        $('#edit_salon_id').trigger('change');
                    }
                });

                if (button.data('salon_id') != '') {
                    $('#edit_salons_div').show();
                    $('#edit_add_to_salon').val(1);
                    $('#edit_add_to_salon').attr('checked', true);
                } else {
                    $('#edit_salons_div').hide();
                    $('#edit_add_to_salon').val(0);
                    $('#edit_add_to_salon').removeAttr('checked');
                }

                let preloaded = [];
                $.each(button.data('images'), function (index, value) {
                    preloaded.push({id: value.id, src: value.image});
                });

                $('.input-images_4').imageUploader_2({
                    preloaded: preloaded,
                    imagesInputName: 'images',
                    maxSize: 2 * 1024 * 1024,
                    maxFiles: 10
                });
            });

            $(document).on('click', '#create_btn', function (event) {
                $('#type').val($('#type option:first-child').val()).trigger('change');
                $('#category_id').val($('#category_id option:first-child').val()).trigger('change');
                $('#create_form').attr('action', '{{ route('products.store') }}');
            });
        });

        $('#type').on('change', function () {
            var value = $(this).val();

            $.ajax({
                url: '{{ url(app()->getLocale() . '/tmg/products/') }}/' + value + '/categories_and_salons',
                method: 'GET',
                success: function (data) {
                    var categories = data.categories;
                    var data_append = "<option selected disabled>@lang('common.choose')</option>";
                    $.each(categories, function (index, value) {
                        data_append += '<option value=' + value.id + '>' + value.name + '</option>';
                    });

                    $('#category_id').empty();
                    $('#category_id').append(data_append);
                    $('#category_id').trigger('change');

                    var salons = data.salons;
                    var data_append_2 = "<option selected disabled>@lang('common.choose')</option>";
                    $.each(salons, function (index, value) {
                        data_append_2 += '<option value=' + value.id + '>' + value.name + '</option>';
                    });

                    $('#salon_id').empty();
                    $('#salon_id').append(data_append_2);
                    $('#salon_id').trigger('change');
                }
            });
        });

        $('#add_to_salon').on('change', function () {
            var value = $(this).val();
            if (value == 0) {
                $('#salons_div').show();
                $(this).val(1);
            } else {
                $('#salons_div').hide();
                $(this).val(0);
            }
        });

        $('#edit_add_to_salon').on('change', function () {
            var value = $(this).val();
            if (value == 0) {
                $('#edit_salons_div').show();
                $(this).val(1);
            } else {
                $('#edit_salons_div').hide();
                $(this).val(0);
            }
        });

        function add_remove(id) {

            $(this).removeClass('btn btn-dark');
            $(this).addClass('btn btn-warning');
            $.ajaxSetup({
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
            });

            $.ajax({
                url: '{{ url(app()->getLocale() . '/tmg/products/update_status/') }}/' + id,
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
