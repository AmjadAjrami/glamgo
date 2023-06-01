@extends('admin.layouts.app')

@section('main-content')
    <style>
        th {
            width: 230px !important;
        }

        table {
            display: block;
            /*overflow-x: auto;*/
            white-space: nowrap;
        }
        .alert.alert-warning,
        .alert.alert-success{
            padding: 10px;
        }
    /*    /////////////////////////////////////*/

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
            width: auto;
            /*max-width: 700px;*/
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
        table.dataTable thead .sorting, table.dataTable thead .sorting_asc, table.dataTable thead .sorting_desc{
            padding-left: 25px !important;
        }
    </style>
    @include('flash::message')
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger" style="padding: 10px">{{$error}}</div>
        @endforeach
    @endif
    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">@lang('common.financial_accounts') - @lang('common.women_salons')</h4>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" id="datatable">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>ID</th>
                            <th>@lang('common.name')</th>
                            <th>@lang('common.mobile')</th>
                            <th>@lang('common.type')</th>
                            <th>@lang('common.orders_price')</th>
                            <th>@lang('common.app_percentage_store')</th>
                            <th>@lang('common.reservations_price')</th>
                            <th>@lang('common.app_percentage_reservations')</th>
                            <th>@lang('common.total_amount')</th>
                            <th>@lang('common.total_amount_after_app_percentage')</th>
                            <th>@lang('common.cash_total')</th>
                            <th>@lang('common.app_cash_percentage')</th>
                            <th>@lang('common.withdrawn_amount_2')</th>
                            <th>@lang('common.remaining_amount_2')</th>
                            <th>@lang('common.bank_account_details')</th>
                            <th>@lang('common.make_withdraw')</th>
                            <th>@lang('common.withdraws')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user['id'] }}</td>
                                <td>{{ $user['id'] }}</td>
                                <td>{{ $user['name'] }}</td>
                                <td>{{ $user['mobile'] }}</td>
                                <td>{{ $user['type_text'] }}</td>
                                <td>{{ $user['orders_price'] }}</td>
                                <td>{{ $user['app_percentage_store'] }}</td>
                                <td>{{ $user['reservations_price'] }}</td>
                                <td>{{ $user['app_percentage_reservations'] }}</td>
                                <td>{{ $user['total_amount'] }}</td>
                                <td>{{ $user['total_amount_after_app_percentage'] }}</td>
                                <td>{{ $user['cash_total'] }}</td>
                                <td>{{ $user['app_percentage_from_cash'] }}</td>
                                <td>{{ $user['withdrawn_amount'] }}</td>
                                <td>{{ $user['remaining_amount'] }}</td>
                                <td>
                                    <button class="btn btn-warning bank_account_details" data-bs-placement="top" title="" data-bs-original-title="Edit" data-bs-toggle="modal" data-bs-target="#bank_account_details"
                                            data-bank_name="{{ $user['bank_name'] }}"  data-bank_account_number="{{ $user['bank_account_number'] }}"
                                            data-bank_account_name="{{ $user['bank_account_name'] }}" data-iban="{{ $user['iban'] }}">
                                        @lang('common.bank_account_details')
                                    </button>
                                </td>
                                <td>
                                    <button class="btn btn-success make_withdraw" data-bs-placement="top" title="" data-bs-original-title="Edit" data-bs-toggle="modal" data-bs-target="#editUser"
                                            data-id="{{ $user['id'] }}" data-type="{{ $user['type'] }}" data-remaining="{{ $user['remaining_amount'] }}">
                                        @lang('common.make_withdraw')
                                    </button>
                                </td>
                                <td>
                                    <button class="btn btn-warning withdraws" data-bs-placement="top" title="" data-bs-original-title="Edit" data-bs-toggle="modal" data-bs-target="#withdraws"
                                            data-id="{{ $user['id'] }}" data-type="{{ $user['type'] }}">
                                        @lang('common.withdraws')
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
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
                        <h1 class="mb-1">@lang('common.make_withdraw')</h1>
                    </div>
                    <form id="editUserForm" data-reset="true" method="POST" class="row gy-1 pt-75" action="{{ route('financial_accounts.make_withdraw') }}"
                          enctype="multipart/form-data">
                        @csrf
                        <input name="salon_id" id="salon_id" hidden>
                        <input name="artist_id" id="artist_id" hidden>
                        <input name="remaining_amount" id="remaining_amount" hidden>
                        <div class="col-12 col-md-12">
                            <label class="form-label"
                                   for="amount">{{ __('common.amount') }}</label>
                            <input type="text" id="amount" name="amount" class="form-control"
                                      placeholder="{{ __('common.amount') }}"/>
                            @if($errors->has('amount'))
                                <div
                                    style="color: red">{{ $errors->first('amount') }}</div>
                            @endif
                        </div>
                        <div class="col-12">
                            <label class="form-label"
                                   for="end_date">{{ __('common.transfer_image') }}</label>
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
                            <button type="submit" class="btn btn-primary me-1"
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

    <div class="modal fade" id="withdraws" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 px-sm-5 pt-50">
                    <div class="text-center mb-2">
                        <h1 class="mb-1">@lang('common.withdraws')</h1>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('common.amount')</th>
                                <th>@lang('common.transfer_image')</th>
                                <th>@lang('common.status')</th>
                                <th>@lang('common.created_at')</th>
                            </tr>
                        </thead>
                        <tbody class="withdraws_body">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="bank_account_details" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 px-sm-5 pt-50">
                    <div class="text-center mb-2">
                        <h1 class="mb-1">@lang('common.bank_account_details')</h1>
                    </div>
                    <table class="table table-bordered">
                        <thead class="account_details">

                        </thead>
                    </table>
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
        var dt_adv_filter = $('#datatable').DataTable({
            "oLanguage": {
                "oPaginate": {
                    "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>',
                    "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>'
                },
                "sInfo": "@lang('common.showing') _START_ @lang('common.to') _END_ @lang('common.from') @lang('common.origin') _TOTAL_ @lang('common.entered')",
                "sSearch": '',
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
        });

        $(document).ready(function () {
            $(document).on('click', '.make_withdraw', function (event) {
                var button = $(this);
                var id = button.data('id');
                var type = button.data('type');
                var remaining_amount = button.data('remaining');

                $('#remaining_amount').attr('value', remaining_amount);

                if(type == 'salon'){
                    $('#salon_id').attr('value', id);
                    $('#artist_id').removeAttr('value');
                }else{
                    $('#artist_id').attr('value', id);
                    $('#salon_id').removeAttr('value');
                }
            });

            $(document).on('click', '.bank_account_details', function (event) {
                var button = $(this);
                var bank_name = button.data('bank_name');
                var bank_account_name = button.data('bank_account_name');
                var iban = button.data('iban');
                var bank_account_number = button.data('bank_account_number');

                $('.account_details').html('');

                var html = '';
                html += '<tr>' +
                    '<th>@lang('common.bank_name')</th>' +
                    '<td class="py-1">' +
                    '<span class="fw-bold">'+ bank_name +'</span>'+
                    '</td>'+
                    '</tr>' +
                    '<tr>' +
                    '<th>@lang('common.bank_account_name')</th>' +
                    '<td class="py-1">'+
                    '<span class="fw-bold">'+ bank_account_name +'</span>'+
                    '</td>'+
                    '</tr>' +
                    '<tr>' +
                    '<th>@lang('common.bank_account_number')</th>' +
                    '<td class="py-1">'+
                    '<span class="fw-bold">'+ bank_account_number +'</span>'+
                    '</td>'+
                    '</tr>' +
                    '<tr>' +
                    '<th>@lang('common.iban')</th>' +
                    '<td class="py-1">'+
                    '<span class="fw-bold">'+ iban +'</span>'+
                    '</td>'+
                    '</tr>';

                $('.account_details').append(html);
            });
        });

        $(document).on('click', '.withdraws', function (){
            var button = $(this);
            var id = button.data('id');
            var type = button.data('type');

            $.ajaxSetup({
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
            });

            $.ajax({
                url: '{{ url(app()->getLocale() . '/tmg/financial_accounts/withdrawals') }}',
                method: 'GET',
                data: {
                    type: type,
                    id: id,
                },
                success: function (data) {
                    $('.withdraws_body').html('');

                    var html = '';
                    $.each(data.withdrawals, function (index, value){
                        var pending = '{{ app()->getLocale() == 'ar' ? 'لم يتم الموافقة او الرفض' : 'Not approved or rejected' }}';
                        var accepted = '{{ app()->getLocale() == 'ar' ? 'تم الموافقة' : 'Approved' }}';
                        var rejected = '{{ app()->getLocale() == 'ar' ? 'تم الرفض' : 'Not Approved' }}';
                        if (value.status == 0){
                            var status = pending;
                        }else if(value.status == 1){
                            var status = accepted;
                        }else if(value.status == 2){
                            var status = rejected;
                        }

                        html += '<tr>' +
                            '<td class="py-1">' +
                            '<p class="card-text fw-bold mb-25">'+ value.id +'</p>'+
                            '</td>'+
                            '<td class="py-1">'+
                            '<span class="fw-bold">'+ value.amount +'</span>'+
                            '</td>'+
                            '<td class="py-1">'+
                            '<span class="fw-bold"><img class="image_view" style="width: 150px" src="'+ value.image +'"></span>'+
                            '</td>'+
                            '<td class="py-1">'+
                            '<span class="fw-bold">'+ status +'</span>'+
                            '</td>'+
                            '<td class="py-1">'+
                            '<span class="fw-bold">'+ value.add_time +'</span>'+
                            '</td>'+
                            '</tr>';
                    });

                    $('.withdraws_body').append(html);
                }
            });
        });

        $(document).on('click', '.image_view', function () {
            $("#full-image").attr("src", $(this).attr("src"));
            $('#image-viewer').show();
        });

        $(document).on('click', '.close', function () {
            $('#image-viewer').hide();
        });

    </script>
@endsection
