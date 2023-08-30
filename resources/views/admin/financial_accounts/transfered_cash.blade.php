@extends('admin.layouts.app')

@section('main-content')
    <style>
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
                    <h4 class="card-title">@lang('common.cash_transfers')</h4>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" id="datatable">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>#</th>
                            <th>@lang('common.salon')</th>
                            <th>@lang('common.artist')</th>
                            <th>@lang('common.mobile')</th>
                            <th>@lang('common.amount')</th>
                            <th>@lang('common.transfer_image')</th>
                            <th>@lang('common.status')</th>
                            <th>@lang('common.actions')</th>
                            <th>@lang('common.created_at')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($transfers as $transfer)
                            <tr>
                                <td>{{ $transfer->id }}</td>
                                <td>{{ $transfer->id }}</td>
                                <td>{{ @$transfer->salon->name == null ? '-' : @$transfer->salon->name }}</td>
                                <td>{{ @$transfer->artist->name == null ? '-' : @$transfer->artist->name }}</td>
                                <td>{{ @$transfer->artist->mobile == null ? @$transfer->salon->mobile : @$transfer->artist->mobile }}</td>
                                <td>{{ $transfer->amount }} @lang('common.rq')</td>
                                <td><img class="image_view" src="{{ $transfer->image }}" style="width: 150px"></td>
                                <td id="span_text_{{ $transfer->id }}">
                                    @if($transfer->status == 0)
                                        <span
                                            style="color: #e1bb77">{{ app()->getLocale() == 'ar' ? 'لم يتم الموافقة او الرفض' : 'Not approved or rejected' }}</span>
                                    @elseif($transfer->status == 1)
                                        <span
                                            style="color: #48bf3a">{{ app()->getLocale() == 'ar' ? 'تم الموافقة' : 'Approved' }}</span>
                                    @elseif($transfer->status == 2)
                                        <span
                                            style="color: #f92828">{{ app()->getLocale() == 'ar' ? 'تم الرفض' : 'Not Approved' }}</span>
                                    @endif
                                </td>
                                @if($transfer->status == 1 || $transfer->status == 2)
                                    <td>
                                        -
                                    </td>
                                @else
                                    <td id="actions_{{ $transfer->id }}">
                                        <button class="btn btn-success change_status" data-id="{{ $transfer->id }}"
                                                data-type="1">{{ app()->getLocale() == 'ar' ? 'الموافقة' : 'Approve' }}</button>
                                        <button class="btn btn-danger change_status" data-id="{{ $transfer->id }}"
                                                data-type="2">{{ app()->getLocale() == 'ar' ? 'الرفض' : 'Not Approve' }}</button>
                                    </td>
                                @endif
                                <td>{{ \Carbon\Carbon::parse($transfer->created_at)->format('Y-m-d A g:i') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
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
            // "order": [[1, 'asc']],
        });


        $(document).on('click', '.image_view', function () {
            $("#full-image").attr("src", $(this).attr("src"));
            $('#image-viewer').show();
        });

        $(document).on('click', '.close', function () {
            $('#image-viewer').hide();
        });

        $(document).on('click', '.change_status', function () {
            var id = $(this).data('id');
            var type = $(this).data('type');

            $.ajaxSetup({
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
            });

            $.ajax({
                url: '{{ url(app()->getLocale() . '/tmg/financial_accounts/update_status') }}',
                method: 'GET',
                data: {
                    id: id,
                    type: type,
                },
                success: function (data) {
                    $('#span_text_' + id).html('');
                    if (type == 1) {
                        var html = '<span style="color: #48bf3a">{{ app()->getLocale() == 'ar' ? 'تم الموافقة' : 'Approved' }}</span>';
                    } else {
                        var html = '<span style="color: #f92828">{{ app()->getLocale() == 'ar' ? 'تم الرفض' : 'Not Approved' }}</span>';
                    }

                    $('#span_text_' + id).append(html);
                    $('#actions_' + id).html('-');
                }
            });
        });
    </script>
@endsection
