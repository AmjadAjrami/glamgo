@extends('admin.layouts.app')

@section('main-content')
    <link type="text/css" rel="stylesheet"
          href="{{ asset('app-assets/image-uploader-master/dist/image-uploader.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
          integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>

    <style>
        select.form-control.reservation_status {
            width: 115px !important;
        }

        .dataTables_scrollBody,
        .dataTables_scrollHead {
            width: 130% !important;
        }

        .col-12.col-md-1.mt-5 {
            margin-top: 67px !important;
        }
    </style>

    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">@lang('common.orders')</h4>
                </div>
                <div class="card-header">
                    <h4 class="card-title" style="font-weight: bold">
                        <span style="margin-left: 20px !important;">@lang('common.total_sales') : {{ $total_sales }} @lang('common.rq')</span>
{{--                        <span style="margin-left: 20px !important;">@lang('common.admin_sales') : {{ $admin_sales }} @lang('common.rq')</span>--}}
{{--                        <span style="margin-left: 20px !important;">@lang('common.salons_sales') : {{ $salons_sales }} @lang('common.rq')</span>--}}
                    </h4>
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
                                    <option value="1">@lang('common.shipped')</option>
                                    <option value="2">@lang('common.completed')</option>
                                    <option value="3">@lang('common.rejected')</option>
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
                            <th>@lang('common.total_price')</th>
                            <th>@lang('common.total_price_after_code')</th>
                            <th style="width: 159px !important;">@lang('common.order_date')</th>
                            <th>@lang('common.order_status')</th>
                            <th>@lang('common.order_cancel_status')</th>
                            <th>@lang('common.order_cancel_reason')</th>
                            <th>@lang('common.order_details')</th>
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
                        <h1 class="mb-1">@lang('common.order_details')</h1>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="order_no">{{ __('common.order_no') }}</label>
                            <input type="text" id="order_no" class="form-control" readonly/>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="order_date">{{ __('common.order_date') }}</label>
                            <input type="text" id="order_date" class="form-control" readonly/>
                        </div>
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
                                   for="total_price">{{ __('common.total_price') }}</label>
                            <input type="text" id="total_price" class="form-control" readonly/>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="total_price_after_code">{{ __('common.total_price_after_code') }}</label>
                            <input type="text" id="total_price_after_code" class="form-control" readonly/>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="postal_number">{{ __('common.postal_number') }}</label>
                            <input type="text" id="postal_number" class="form-control" readonly/>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label class="form-label"
                                   for="payment_method">{{ __('common.payment_method') }}</label>
                            <input type="text" id="payment_method" class="form-control" readonly/>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="address_details">{{ __('common.address_details') }}</label>
                            <textarea type="text" id="address_details" class="form-control" readonly></textarea>
                        </div>
                        <div class="col-12 col-md-12 mt-3">
                            <label class="form-label"
                                   for="payment_method">{{ __('common.products') }}</label>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th class="py-1">@lang('common.product')</th>
                                        <th class="py-1">@lang('common.quantity')</th>
                                        <th class="py-1">@lang('common.price')</th>
                                        <th class="py-1">@lang('common.total_price')</th>
                                    </tr>
                                    </thead>
                                    <tbody id="products">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-12 col-md-11 mb-3 mt-3">
                            <a class="btn btn-success" id="order_invoice" target="_blank">@lang('common.invoice')</a>
                        </div>
                        <div class="col-12 col-md-11 mb-3 mt-3">
                            <label class="form-label"
                                   for="map_url">{{ __('common.map_url') }}</label>
                            <input type="text" id="map_url" class="form-control copy-link-input" readonly/>
                        </div>
                        <div class="col-12 col-md-1 mt-5">
                            <button type="button" class="copy-link-button" style="border: none" onclick="copy_url()">
                                <i class="fa-regular fa-copy" style="font-size: 23px;padding: 6px;"></i>
                            </button>
                        </div>
                        <div class="col-12">
                            <input id="edit_lat" hidden>
                            <input id="edit_lng" hidden>
                            <div class="map" id="map" style="width: 100%; height: 300px"></div>
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
                        <input name="order_id" id="order_id" type="hidden">
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
@endsection

@section('scripts')
    <script>
        var url = '{{ url(app()->getLocale() . "/tmg/orders") }}/';

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
                    d.salon_id = $('#s_salon_id').val();
                    d.artist_id = $('#s_artist_id').val();
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
                {data: 'address_mobile', name: 'address_mobile', orderable: false, searchable: false},
                {data: 'total_price', name: 'total_price', orderable: false, searchable: false},
                {data: 'total_price_after_code', name: 'total_price_after_code', orderable: false, searchable: false},
                {data: 'add_time', name: 'add_time', orderable: false, searchable: false},
                {
                    "render": function (data, type, full, meta) {
                        var shipped = '';
                        var completed = '';
                        var rejected = '';

                        if (full.status == 1) {
                            shipped = 'selected';
                        } else if (full.status == 2) {
                            completed = 'selected';
                        } else if (full.status == 4) {
                            rejected = 'selected';
                        }

                        return '<select class="form-control order_status" name="order_status" data-id="' + full.id + '">' +
                            '<option disabled>@lang('common.choose')</option>' +
                            '<option value="1" ' + shipped + '>@lang('common.shipped')</option>' +
                            '<option value="2" ' + completed + '>@lang('common.completed')</option>' +
                            '<option value="4" ' + rejected + '>@lang('common.rejected')</option>' +
                            '</select>';
                    }, orderable: false, searchable: false
                },
                {
                    "render": function (data, type, full, meta) {
                        return full.status == 3 ? '<span style="color: red">@lang('common.canceled')</span>' : '-';
                    }, orderable: false, searchable: false
                },
                {
                    "render": function (data, type, full, meta) {
                        return full.status == 3 ? full.cancel_reason : '-';
                    }, orderable: false, searchable: false
                },
                {
                    "render": function (data, type, full, meta) {
                        return '<button class="btn btn-success order_details" data-id="' + full.id + '" data-bs-placement="top" title="" data-bs-original-title="Edit" data-bs-toggle="modal" data-bs-target="#editUser">@lang('common.order_details')</button>';
                    }, orderable: false, searchable: false
                },
            ]
        });

        $(document).on('change', '.order_status', function () {
            var id = $(this).data('id');
            var status = $(this).val();

            if (status == 4) {
                $('#reject_reason').modal('toggle');
                $('#editUserForm').attr('action', '{{ url(app()->getLocale() . '/tmg/orders/update_status') }}');
                $('#order_id').val(id);
                $('#status').val(status);
            } else {
                $.ajaxSetup({
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                });

                $.ajax({
                    url: '{{ url(app()->getLocale() . '/tmg/orders/update_status') }}',
                    method: 'PUT',
                    data: {
                        order_id: id,
                        status: status,
                    },
                    success: function (data) {
                        toastr.success('@lang('common.done_successfully')');
                    }
                });
            }

        });

        function initMap() {
            get_address();
        }

        function get_address() {
            var lat = parseFloat($('#edit_lat').val());
            var lng = parseFloat($('#edit_lng').val());
            var myLatLng = {lat: lat || 25.2854, lng: lng || 51.5310};
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 11,
                center: myLatLng,
            });

            new google.maps.Marker({
                position: myLatLng,
                map,
                title: "Hello World!",
            });
        }

        $(document).on('click', '.order_details', function () {
            var id = $(this).data('id');

            $.ajaxSetup({
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
            });

            $.ajax({
                url: '{{ url(app()->getLocale() . '/tmg/orders/order_details') }}',
                method: 'GET',
                data: {
                    order_id: id,
                },
                success: function (data) {
                    $('#order_no').val(data.order.id);
                    $('#user_name').val(data.order.user_name);
                    $('#user_mobile').val(data.order.address_mobile);
                    $('#order_date').val(data.order.add_time);
                    $('#total_price').val(data.order.total_price);
                    $('#total_price_after_code').val(data.order.total_price_after_code);
                    $('#address_details').val(data.order.address_details);
                    $('#postal_number').val(data.order.address_postal_number);
                    $('#payment_method').val(data.order.payment_method_title);
                    $('#order_invoice').attr('href', url + 'invoice/' + data.order.id);
                    $('#edit_lat').attr('value', data.address.lat);
                    $('#edit_lng').attr('value', data.address.lng);
                    $('#map_url').val('https://www.google.com/maps/search/?api=1&query=' + data.address.lat + '%2C' + data.address.lng)

                    $('#products').html('');

                    var html = '';
                    var total_price = 0;
                    $.each(data.products, function (index, value) {
                        html += '<tr>' +
                            '<td class="py-1">' +
                            '<p class="card-text fw-bold mb-25">' + value.product_title + '</p>' +
                            '</td>' +
                            '<td class="py-1">' +
                            '<span class="fw-bold">' + value.quantity + '</span>' +
                            '</td>' +
                            '<td class="py-1">' +
                            '<span class="fw-bold">' + value.price + '</span>' +
                            '</td>' +
                            '<td class="py-1">' +
                            '<span class="fw-bold">' + value.price * value.quantity + '</span>' +
                            '</td>' +
                            '</tr>';

                        total_price += value.price * value.quantity;
                    });

                    html += '<tr>' +
                        '<td></td>' +
                        '<td></td>' +
                        '<td colspan="1" style="font-weight:bold">@lang('common.delivery_price')</td>' +
                        '<td style="font-weight:bold">'+ (parseFloat(data.order.total_price) - parseFloat(data.cart_items_price)) +'</td>' +
                        '</tr>';

                    if(isNaN(parseFloat(data.order.total_price_after_code)) == true){
                        html += '<tr>' +
                            '<td></td>' +
                            '<td></td>' +
                            '<td colspan="1" style="font-weight:bold">@lang('common.discount_price')</td>' +
                            '<td style="font-weight:bold">'+ 0 +'</td>' +
                            '</tr>';

                        html += '<tr>' +
                        '<td></td>' +
                        '<td></td>' +
                        '<td colspan="1" style="font-weight:bold">@lang('common.total_price')</td>' +
                        '<td style="font-weight:bold">'+ data.order.total_price +' @lang('common.rq')</td>' +
                            '</tr>';
                    }else{
                        html += '<tr>' +
                            '<td></td>' +
                            '<td></td>' +
                            '<td colspan="1" style="font-weight:bold">@lang('common.discount_price')</td>' +
                            '<td style="font-weight:bold">'+ ((parseFloat(data.order.total_price)) - parseFloat(data.order.total_price_after_code)) +'</td>' +
                            '</tr>';

                        html += '<tr>' +
                        '<td></td>' +
                        '<td></td>' +
                        '<td colspan="1" style="font-weight:bold">@lang('common.total_price')</td>' +
                        '<td style="font-weight:bold">'+  data.order.total_price_after_code +' @lang('common.rq')</td>' +
                            '</tr>';
                    }

                    get_address();

                    $('#products').append(html);
                }
            });
        });

        function copy_url() {
            // Get the text field
            var copyText = document.getElementById("map_url");

            // Select the text field
            copyText.select();
            copyText.setSelectionRange(0, 99999); // For mobile devices

            // Copy the text inside the text field
            navigator.clipboard.writeText(copyText.value);

            // Alert the copied text
            @if(app()->getLocale() == 'ar')
            toastr.success("تم نسخ الرابط بنجاح");
            @else
            toastr.success("Text Copied Successfully");
            @endif
        }
    </script>

    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCa_N3am8UImF1aoqlmC2lISfTxNfDeGmc&callback=initMap&v=weekly"
        defer
    ></script>
@endsection

