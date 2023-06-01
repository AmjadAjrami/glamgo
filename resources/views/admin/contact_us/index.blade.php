@extends('admin.layouts.app')

@section('main-content')
    <link type="text/css" rel="stylesheet"
          href="{{ asset('app-assets/image-uploader-master/dist/image-uploader.min.css') }}">

    <style>
        /* Style the Image Used to Trigger the Modal */
        img {
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        img:hover {
            opacity: 0.7;
        }

        /* The Modal (background) */
        #image-viewer {
            display: none;
            position: fixed;
            z-index: 9999;
            padding-top: 100px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.9);
        }

        .modal-content {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
        }

        .modal-content {
            animation-name: zoom;
            animation-duration: 0.6s;
        }

        @keyframes zoom {
            from {
                transform: scale(0)
            }
            to {
                transform: scale(1)
            }
        }

        #image-viewer .close {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            transition: 0.3s;
        }

        #image-viewer .close:hover,
        #image-viewer .close:focus {
            color: #bbb;
            text-decoration: none;
            cursor: pointer;
        }

        @media only screen and (max-width: 700px) {
            .modal-content {
                width: 100%;
            }
        }
    </style>

    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">@lang('common.contact_us')</h4>
                </div>
                <form id="search_form" style="margin-right: 25px;margin-top: 30px">
                    <h6>@lang('common.search')</h6>
                    <div class="form-row">
                        <div class="form-group">
                            <input type="text" class="form-control" name="title" id="s_title"
                                   placeholder="@lang('common.title')" style="width: 150px;display: inline">
                            <input type="text" class="form-control" name="user_name" id="s_user_name"
                                   placeholder="@lang('common.user_name')" style="width: 150px;display: inline">
                            <input type="text" class="form-control" name="user_mobile" id="s_user_mobile"
                                   placeholder="@lang('common.mobile')" style="width: 150px;display: inline">
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
                            <th>@lang('common.user_name')</th>
                            <th>@lang('common.mobile')</th>
                            <th>@lang('common.email')</th>
                            <th>@lang('common.title')</th>
                            <th>@lang('common.message')</th>
                            <th>@lang('common.image')</th>
                            <th>@lang('common.created_at')</th>
                            <th>@lang('common.details')</th>
                            <th>@lang('common.reply')</th>
                        </tr>
                        </thead>
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
                        <h1 class="mb-1">@lang('common.reply')</h1>
                    </div>
                    <form id="editUserForm" data-reset="true" method="POST" class="row gy-1 pt-75 ajax_form"
                          onsubmit="return false" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="col-12 col-md-12">
                            <label class="form-label"
                                   for="message">{{ __('common.message') }}</label>
                            <textarea type="text" id="message" name="message" class="form-control"
                                      placeholder="{{ __('common.message') }}" style="height: 150px"></textarea>
                            @if($errors->has('message'))
                                <div
                                    style="color: red">{{ $errors->first('message') }}</div>
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

    <div class="modal fade" id="item_details" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 px-sm-5 pt-50">
                    <div class="text-center mb-2">
                        <h1 class="mb-1">@lang('common.details')</h1>
                    </div>
                    <form id="editUserForm" data-reset="true" method="POST" class="row gy-1 pt-75 ajax_form"
                          onsubmit="return false" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="col-6 col-md-6">
                            <label class="form-label"
                                   for="user_name">{{ __('common.user_name') }}</label>
                            <input type="text" id="user_name" name="user_name" class="form-control" readonly>
                        </div>
                        <div class="col-6 col-md-6">
                            <label class="form-label"
                                   for="email">{{ __('common.email') }}</label>
                            <input type="text" id="email" name="email" class="form-control" readonly>
                        </div>
                        <div class="col-12 col-md-12">
                            <label class="form-label"
                                   for="mobile">{{ __('common.mobile') }}</label>
                            <input type="text" id="mobile" name="mobile" class="form-control" readonly>
                        </div>
                        <div class="col-6 col-md-12">
                            <label class="form-label"
                                   for="title">{{ __('common.title') }}</label>
                            <input type="text" id="title" name="title" class="form-control" readonly>
                        </div>
                        <div class="col-12 col-md-12">
                            <label class="form-label"
                                   for="text_message">{{ __('common.message') }}</label>
                            <textarea type="text" id="text_message" name="message" class="form-control"
                                      placeholder="{{ __('common.message') }}" readonly></textarea>
                        </div>
                        <div class="col-12 col-md-12">
                            <label class="form-label"
                                   for="image">{{ __('common.image') }}</label>
                            <br>
                            <img src="" id="image" class="image_view" style="width: 300px">
                        </div>
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

    <div id="image-viewer">
        <span class="close">&times;</span>
        <img class="modal-content" id="full-image">
    </div>
@endsection

@section('scripts')
    <script>
        var url = '{{ url(app()->getLocale() . "/tmg/contact_us") }}/';

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
                {data: 'name', name: 'name', orderable: false, searchable: false},
                {data: 'mobile', name: 'mobile', orderable: false, searchable: false},
                {data: 'email', name: 'email', orderable: false, searchable: false},
                {data: 'title', name: 'title', orderable: false, searchable: false},
                {data: 'message', name: 'message', orderable: false, searchable: false},
                {
                    "render": function (data, type, full, meta) {
                        return '<span><img src="' + full.image + '" class="rounded-circle profile-img" alt="avatar" style="width: 50px;height: 50px"></span>';
                    }, orderable: false, searchable: false
                },
                {data: 'add_time', name: 'add_time', orderable: false, searchable: false},
                {
                    "render": function (data, type, full, meta) {
                        return '<button class="btn btn-success details" data-name="' + full.name + '" data-email="' + full.email + '" data-mobile="' + full.mobile + '"' +
                            'data-title="' + full.title + '" data-message="' + full.message + '" data-image="' + full.image + '" data-bs-placement="top" title="" data-bs-original-title="Edit" data-bs-toggle="modal" data-bs-target="#item_details">@lang('common.details')</button>'
                    }, orderable: false, searchable: false
                },
                {
                    "render": function (data, type, full, meta) {
                        if (full.reply_message != null) {
                            return '<span>@lang('common.replied')</span>';
                        } else {
                            return '<button class="btn btn-info reply" data-id="' + full.id + '" data-bs-placement="top" title="" data-bs-original-title="Edit" data-bs-toggle="modal" data-bs-target="#editUser">@lang('common.reply')</button>'
                        }
                    }, orderable: false, searchable: false
                },
            ]
        });

        $(document).on('click', '.reply', function (event) {
            var button = $(this);
            var id = button.data('id');

            $('#editUserForm').attr('action', url + id + '/reply');
        });

        $(document).on('click', '.details', function (event) {
            var button = $(this);
            var user_name = button.data('name');
            var user_email = button.data('email');
            var user_mobile = button.data('mobile');
            var title = button.data('title');
            var message = button.data('message');
            var image = button.data('image');

            $('#user_name').val(user_name);
            $('#email').val(user_email);
            $('#mobile').val(user_mobile);
            $('#title').val(title);
            $('#text_message').val(message);
            $('#image').attr('src', image);
        });

        $(document).on('click', '.image_view', function () {
            console.log('asdasd');
            $("#full-image").attr("src", $(this).attr("src"));
            $('#image-viewer').show();
        });

        $(document).on('click', '.close', function () {
            $('#image-viewer').hide();
        });
    </script>
@endsection

