@extends('salon.layouts.app')

@section('main-content')
    <link type="text/css" rel="stylesheet"
          href="{{ asset('app-assets/image-uploader-master/dist/image-uploader.min.css') }}">

    <style>
        select.form-control.reservation_status {
            width: 115px !important;
        }

        .dataTables_scrollBody,
        .dataTables_scrollHead {
            width: 130% !important;
        }
    </style>

    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">@lang('common.reservations')</h4>
                </div>
                <form id="search_form" style="margin-right: 25px;margin-top: 30px">
                    <h6>@lang('common.search')</h6>
                    <div class="form-row">
                        <div class="row">
                            <div class="col-2">
                                <input id="s_name" name="name" class="form-control mb-1"
                                       placeholder="@lang('common.user_name')">
                            </div>
                            <div class="col-2">
                                <input id="s_email" name="email" class="form-control"
                                       placeholder="@lang('common.email')">
                            </div>
                            <div class="col-2">
                                <input id="s_mobile" name="mobile" class="form-control"
                                       placeholder="@lang('common.mobile')">
                            </div>
                            <div class="col-2">
                                <input id="s_date" type="date" name="date" class="form-control"
                                       placeholder="@lang('common.date')">
                            </div>
                            <div class="col-2">
                                <select id="s_status" name="status" class="form-control">
                                    <option selected disabled>@lang('common.choose') @lang('common.status')</option>
                                    <option value="1">@lang('common.under_confirmation')</option>
                                    <option value="2">@lang('common.confirmed')</option>
                                    <option value="3">@lang('common.completed')</option>
                                    <option value="4">@lang('common.canceled')</option>
                                    <option value="5">@lang('common.rejected')</option>
                                </select>
                            </div>
                            <div class="col-2">
                                <input type="button" id="search_btn"
                                       class="btn btn-info" value="@lang('common.search')">
                                <input type="button" id="clear_btn"
                                       class="btn btn-secondary" value="@lang('common.clear_search')">
                            </div>
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
                            <th style="width: 159px !important;">@lang('common.reservation_date')</th>
                            <th style="width: 159px !important;">@lang('common.reservation_time')</th>
                            <th>@lang('common.payment_method')</th>
                            <th>@lang('common.services')</th>
                            <th>@lang('common.reservation_status')</th>
                            <th>@lang('common.cancel_reason')</th>
                            <th>@lang('common.chat')</th>
                            <th>@lang('common.reservation_ticket')</th>
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
                        <h1 class="mb-1">@lang('common.reservation_ticket')</h1>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="user_name">{{ __('common.user_name') }}</label>
                            <input type="text" id="user_name" class="form-control" readonly/>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="user_mobile">{{ __('common.mobile') }}</label>
                            <input type="text" id="user_mobile" class="form-control" readonly/>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="reservation_number">{{ __('common.reservation_number') }}</label>
                            <input type="text" id="reservation_number" class="form-control" readonly/>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="reservation_date">{{ __('common.reservation_date') }}</label>
                            <input type="text" id="reservation_date" class="form-control" readonly/>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="reservation_time">{{ __('common.reservation_time') }}</label>
                            <input type="text" id="reservation_time" class="form-control" readonly/>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="provider_name">{{ __('common.provider_name') }}</label>
                            <input type="text" id="provider_name" class="form-control" readonly/>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="provider_type">{{ __('common.provider_type') }}</label>
                            <input type="text" id="provider_type" class="form-control" readonly/>
                        </div>
                    </div>
                    <div class="col-12 text-center mt-2 pt-50">
                        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                                aria-label="Close">
                            @lang('common.discard')
                        </button>
                        <a class="btn btn-info" id="print" target="_blank">
                            @lang('common.print')
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="cancel_reason" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 px-sm-5 pt-50">
                    <div class="text-center mb-2">
                        <h1 class="mb-1">@lang('common.cancel_reason')</h1>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-12">
                            <label class="form-label"
                                   for="cancel_reason_input">{{ __('common.cancel_reason') }}</label>
                            <textarea type="text" id="cancel_reason_input" class="form-control" readonly></textarea>
                        </div>
                    </div>
                    <div class="col-12 text-center mt-2 pt-50">
                        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                                aria-label="Close">
                            @lang('common.discard')
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="reject_reason" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 px-sm-5 pt-50">
                    <div class="text-center mb-2">
                        <h1 class="mb-1">@lang('common.reject_reason')</h1>
                    </div>
                    <form id="editUserForm" data-reset="true" method="POST" class="row gy-1 pt-75 ajax_form"
                          onsubmit="return false" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input name="reservation_id" id="reservation_id" type="hidden">
                        <input name="status" id="status" type="hidden">
                        <div class="row">
                            <div class="col-12 col-md-12">
                                <label class="form-label"
                                       for="reject_reason_input">{{ __('common.reject_reason') }}</label>
                                <textarea type="text" id="reject_reason_input" name="reject_reason"
                                          class="form-control"></textarea>
                            </div>
                        </div>
                    </form>
                    <div class="col-12 text-center mt-2 pt-50">
                        <button type="submit" class="btn btn-primary me-1 submit_btn"
                                form="editUserForm">@lang('common.save_changes')</button>
                        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                                aria-label="Close">
                            @lang('common.discard')
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="services_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 px-sm-5 pt-50">
                    <div class="text-center mb-2">
                        <h1 class="mb-1">@lang('common.services')</h1>
                    </div>
                    <div class="row">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>@lang('common.service')</th>
                                    <th>@lang('common.service_category')</th>
                                    <th>@lang('common.number_of_clients')</th>
                                    <th>@lang('common.price')</th>
                                    <th>@lang('common.total_price')</th>
                                </tr>
                            </thead>
                            <tbody id="services_body">

                            </tbody>
                        </table>
                    </div>
                    <div class="col-12 text-center mt-2 pt-50">
                        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                                aria-label="Close">
                            @lang('common.discard')
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        var url = '{{ url(app()->getLocale() . "/salon/reservations") }}/';

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
            scrollX: true,
            ajax: {
                url: url + 'indexTable',
                data: function (d) {
                    d.status = $('#s_status').val();
                    d.name = $('#s_name').val();
                    d.email = $('#s_email').val();
                    d.mobile = $('#s_mobile').val();
                    d.date = $('#s_date').val();
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
                {data: 'user_name', name: 'user_name', orderable: false, searchable: false},
                {data: 'user_mobile', name: 'user_mobile', orderable: false, searchable: false},
                {data: 'user_email', name: 'user_email', orderable: false, searchable: false},
                {data: 'date', name: 'date', orderable: false, searchable: false},
                {data: 'reservation_time', name: 'reservation_time', orderable: false, searchable: false},
                {data: 'payment_method_title', name: 'payment_method_title', orderable: false, searchable: false},
                {
                    "render": function (data, type, full, meta) {
                        return '<button class="btn btn-success reservation_services" data-id="' + full.id + '" data-bs-placement="top" title="" data-bs-original-title="Edit" data-bs-toggle="modal" data-bs-target="#services_modal">@lang('common.services')</button>';
                    }, orderable: false, searchable: false
                },
                {
                    "render": function (data, type, full, meta) {
                        var under_confirmation = '';
                        var confirmed = '';
                        var completed = '';
                        var canceled = '';
                        var rejected = '';

                        if (full.status == 1) {
                            under_confirmation = 'selected';
                        } else if (full.status == 2) {
                            confirmed = 'selected';
                        } else if (full.status == 3) {
                            completed = 'selected';
                        } else if (full.status == 4) {
                            canceled = 'selected';
                        } else if (full.status == 5) {
                            rejected = 'selected';
                        }

                        return '<select class="form-control reservation_status" name="reservation_status" data-id="' + full.id + '">' +
                            '<option disabled>@lang('common.choose')</option>' +
                            '<option value="1" ' + under_confirmation + '>@lang('common.under_confirmation')</option>' +
                            '<option value="2" ' + confirmed + '>@lang('common.confirmed')</option>' +
                            '<option value="3" ' + completed + '>@lang('common.completed')</option>' +
                            '<option value="4" ' + canceled + '>@lang('common.canceled')</option>' +
                            '<option value="5" ' + rejected + '>@lang('common.rejected')</option>' +
                            '</select>';
                    }, orderable: false, searchable: false
                },
                {
                    "render": function (data, type, full, meta) {
                        if (full.cancel_reason == null) {
                            return '-';
                        } else {
                            return '<button class="btn btn-danger cancel_reason" data-details="' + full.cancel_reason + '" data-bs-placement="top" title="" data-bs-original-title="Edit" data-bs-toggle="modal" data-bs-target="#cancel_reason">@lang('common.cancel_reason')</button>';
                        }
                    }, orderable: false, searchable: false
                },
                {
                    "render": function (data, type, full, meta) {
                        return '<a class="btn btn-info" href="{{ url(app()->getLocale() . '/salon/reservations/user_chat/') }}/'+ full.user_id +'">@lang('common.chat')</a>';
                    }, orderable: false, searchable: false
                },
                {
                    "render": function (data, type, full, meta) {
                        return '<button class="btn btn-success reservation_details" data-id="' + full.id + '" data-bs-placement="top" title="" data-bs-original-title="Edit" data-bs-toggle="modal" data-bs-target="#editUser">@lang('common.reservation_ticket')</button>';
                    }, orderable: false, searchable: false
                },
            ]
        });

        $(document).on('change', '.reservation_status', function () {
            var id = $(this).data('id');
            var status = $(this).val();

            if (status == 5) {
                $('#reject_reason').modal('toggle');
                $('#editUserForm').attr('action', '{{ url(app()->getLocale() . '/salon/reservations/update_status') }}');
                $('#reservation_id').val(id);
                $('#status').val(status);
            } else {
                $.ajaxSetup({
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                });

                $.ajax({
                    url: '{{ url(app()->getLocale() . '/salon/reservations/update_status') }}',
                    method: 'PUT',
                    data: {
                        reservation_id: id,
                        status: status,
                    },
                    success: function (data) {
                        toastr.success('@lang('common.done_successfully')');
                    }
                });
            }
        });

        $(document).on('click', '.cancel_reason', function () {
            var details = $(this).data('details');
            $('#cancel_reason_input').val(details);
        });

        $(document).on('click', '.reservation_details', function () {
            var id = $(this).data('id');

            $.ajaxSetup({
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
            });

            $.ajax({
                url: '{{ url(app()->getLocale() . '/salon/reservations/reservation_details') }}',
                method: 'GET',
                data: {
                    reservation_id: id,
                },
                success: function (data) {
                    $('#user_name').val(data.reservation.user_name);
                    $('#user_mobile').val(data.reservation.user_mobile);
                    $('#reservation_number').val(data.reservation.reservation_number);
                    $('#reservation_date').val(data.reservation.date_text);
                    $('#reservation_time').val(data.reservation.reservation_time);
                    $('#provider_name').val(data.reservation.provider_name);
                    $('#provider_type').val(data.reservation.provider_type);
                    $('#print').attr('href', '{{ url('/salon/reservations/ticket/') }}/' + data.reservation.id);
                }
            });
        });

        $(document).on('click', '.reservation_services', function () {
            $('#services_body').html('');

            var id = $(this).data('id');

            $.ajaxSetup({
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
            });

            $.ajax({
                url: '{{ url(app()->getLocale() . '/salon/reservations/reservation_details') }}',
                method: 'GET',
                data: {
                    reservation_id: id,
                },
                success: function (data) {
                    var html = '';
                    var total_price = 0;

                    if (data.reservation.offer_id == null) {
                        $.each(data.items, function (index, value) {
                            var price = 0;
                            if(value.discount_price != null){
                                price = value.discount_price;
                            }else{
                                price = value.price;
                            }
                            total_price += (price * value.quantity);

                            var service_category = '';
                            if(value.service_category == null){
                                service_category = '-';
                            }else if(value.service_category == 1){
                                service_category = '@lang('common.internal_services')';
                            }else if(value.service_category == 2){
                                service_category = '@lang('common.external_services')';
                            }

                            html += '<tr>' +
                                '<td>' + value.service_name + '</td>' +
                                '<td>' + service_category + '</td>' +
                                '<td>' + value.quantity + '</td>' +
                                '<td>' + price + ' @lang('common.rq')</td>' +
                                '<td>' + (price * value.quantity) + ' @lang('common.rq')</td>' +
                                '</tr>';
                        });
                    } else {
                        var price = 0;
                        if(data.items.discount_price != null){
                            price = data.items.discount_price;
                        }else{
                            price = data.items.price;
                        }

                        total_price += (price * data.reservation.quantity);
                        html += '<tr>' +
                            '<td>' + data.items.title + '</td>' +
                            '<td>-</td>' +
                            '<td>' + data.reservation.quantity + '</td>' +
                            '<td>' + price + ' @lang('common.rq')</td>' +
                            '<td>' + (price * data.reservation.quantity) + ' @lang('common.rq')</td>' +
                            '</tr>';
                    }

                    html += '<tr>' +
                        '<td colspan="4" style="text-align:center;font-weight:bold">@lang('common.total_price')</td>' +
                        '<td>'+ total_price +' @lang('common.rq')</td>' +
                        '</tr>';

                    $('#services_body').append(html);
                }
            });
        });
    </script>
@endsection

