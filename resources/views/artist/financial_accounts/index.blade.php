@extends('artist.layouts.app')
<style>
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
    .alert.alert-warning,
    .alert.alert-danger,
    .alert.alert-success{
        padding: 10px;
    }
</style>
@section('main-content')
    <style>
        button.bs-tooltip.edit_btn,
        button.delete-btn.bs-tooltip, button.user-delete-btn.bs-tooltip{
            display: none !important;
        }
    </style>
    @include('flash::message')
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger">{{$error}}</div>
        @endforeach
    @endif
    <div class="row match-height">
        <div class="col-xl-12 col-md-6 col-12">
            <div class="card card-statistics">
                <div class="card-body statistics-body">
                    <h4 class="card-title">@lang('common.financial_accounts')</h4>
                    <div class="row">
                        <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                            <div class="d-flex flex-row">
                                <div class="avatar bg-light-info me-2">
                                    <div class="avatar-content">
                                        <i data-feather="dollar-sign" class="avatar-icon"></i>
                                    </div>
                                </div>
                                <div class="my-auto">
                                    <h4 class="fw-bolder mb-0">{{ $details['reservations_price'] }} @lang('common.rq')</h4>
                                    <p class="card-text font-small-3 mb-0">@lang('common.reservations_price')</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-sm-0">
                            <div class="d-flex flex-row">
                                <div class="avatar bg-light-danger me-2">
                                    <div class="avatar-content">
                                        <i data-feather="dollar-sign" class="avatar-icon"></i>
                                    </div>
                                </div>
                                <div class="my-auto">
                                    <h4 class="fw-bolder mb-0">{{ $details['total_amount'] }} @lang('common.rq')</h4>
                                    <p class="card-text font-small-3 mb-0">@lang('common.total_amount')</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-sm-0">
                            <div class="d-flex flex-row">
                                <div class="avatar bg-light-secondary me-2">
                                    <div class="avatar-content">
                                        <i data-feather="percent" class="avatar-icon"></i>
                                    </div>
                                </div>
                                <div class="my-auto">
                                    <h4 class="fw-bolder mb-0">{{ $details['app_percentage'] }} @lang('common.rq')</h4>
                                    <p class="card-text font-small-3 mb-0">@lang('common.app_percentage')</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-sm-0 mt-4">
                        </div>
                        <hr style="height: 3px; !important;margin-top: 50px !important;">
                        <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-sm-0 mt-4">
                            <div class="d-flex flex-row">
                                <div class="avatar bg-light-warning me-2">
                                    <div class="avatar-content">
                                        <i data-feather="dollar-sign" class="avatar-icon"></i>
                                    </div>
                                </div>
                                <div class="my-auto">
                                    <h4 class="fw-bolder mb-0">{{ $details['total_amount_after_app_percentage'] }} @lang('common.rq')</h4>
                                    <p class="card-text font-small-3 mb-0">@lang('common.total_amount_after_app_percentage')</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-sm-0 mt-4">
                            <div class="d-flex flex-row">
                                <div class="avatar bg-light-success me-2">
                                    <div class="avatar-content">
                                        <i data-feather="dollar-sign" class="avatar-icon"></i>
                                    </div>
                                </div>
                                <div class="my-auto">
                                    <h4 class="fw-bolder mb-0">{{ $details['withdrawn_amount'] }} @lang('common.rq')</h4>
                                    <p class="card-text font-small-3 mb-0">@lang('common.withdrawn_amount')</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-sm-0 mt-4">
                            <div class="d-flex flex-row">
                                <div class="avatar bg-light-danger me-2">
                                    <div class="avatar-content">
                                        <i data-feather="dollar-sign" class="avatar-icon"></i>
                                    </div>
                                </div>
                                <div class="my-auto">
                                    <h4 class="fw-bolder mb-0">{{ $details['remaining_amount'] }} @lang('common.rq')</h4>
                                    <p class="card-text font-small-3 mb-0">@lang('common.remaining_amount')</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-sm-0 mt-4">
                        </div>
                        <hr style="height: 3px; !important;margin-top: 50px !important;">
                        <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-sm-0 mt-4">
                            <div class="d-flex flex-row">
                                <div class="avatar bg-light-primary me-2">
                                    <div class="avatar-content">
                                        <i data-feather="dollar-sign" class="avatar-icon"></i>
                                    </div>
                                </div>
                                <div class="my-auto">
                                    <h4 class="fw-bolder mb-0">{{ $details['cash_total'] }} @lang('common.rq')</h4>
                                    <p class="card-text font-small-3 mb-0">@lang('common.cash_total')</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-sm-0 mt-4">
                            <div class="d-flex flex-row">
                                <div class="avatar bg-light-secondary me-2">
                                    <div class="avatar-content">
                                        <i data-feather="percent" class="avatar-icon"></i>
                                    </div>
                                </div>
                                <div class="my-auto">
                                    <h4 class="fw-bolder mb-0">{{ $details['app_percentage_from_cash'] }} @lang('common.rq')</h4>
                                    <p class="card-text font-small-3 mb-0">@lang('common.total_app_cash_percentage')</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-sm-0 mt-4">
                            <div class="d-flex flex-row">
                                <div class="avatar bg-light-secondary me-2">
                                    <div class="avatar-content">
                                        <i data-feather="percent" class="avatar-icon"></i>
                                    </div>
                                </div>
                                <div class="my-auto">
                                    <h4 class="fw-bolder mb-0">{{ $details['remaining_cash'] }} @lang('common.rq')</h4>
                                    <p class="card-text font-small-3 mb-0">@lang('common.app_cash_percentage')</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-sm-0 mt-4">
                            <div class="d-flex flex-row">
                                <div class="my-auto">
                                    <button class="btn btn-success make_withdraw" data-bs-placement="top" title="" data-bs-original-title="Edit" data-bs-toggle="modal" data-bs-target="#editUser"
                                            data-remaining="{{ $details['remaining_cash'] }}">
                                        @lang('common.make_withdraw')
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="image-viewer">
        <span class="close">&times;</span>
        <img class="modal-content" id="full-image">
    </div>

    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">@lang('common.withdraws')</h4>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" id="datatable">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>@lang('common.amount')</th>
                            <th>@lang('common.transfer_image')</th>
                            <th>@lang('common.status')</th>
                            <th>@lang('common.actions')</th>
                            <th>@lang('common.created_at')</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($artist_withdraws as $withdraw)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $withdraw->amount }} @lang('common.rq')</td>
                                    <td><img src="{{ $withdraw->image }}" class="image_view" style="width: 150px"></td>
                                    <td id="span_text_{{$withdraw->id}}">
                                        @if($withdraw->status == 0)
                                            <span
                                                style="color: #e1bb77">{{ app()->getLocale() == 'ar' ? 'لم يتم الموافقة او الرفض' : 'Not approved or rejected' }}</span>
                                        @elseif($withdraw->status == 1)
                                            <span
                                                style="color: #48bf3a">{{ app()->getLocale() == 'ar' ? 'تم الموافقة' : 'Approved' }}</span>
                                        @elseif($withdraw->status == 2)
                                            <span
                                                style="color: #f92828">{{ app()->getLocale() == 'ar' ? 'تم الرفض' : 'Not Approved' }}</span>
                                        @endif
                                    </td>
                                    @if($withdraw->status == 1 || $withdraw->status == 2)
                                        <td>
                                            -
                                        </td>
                                    @else
                                        <td id="actions_{{ $withdraw->id }}">
                                            <button class="btn btn-success change_status" data-id="{{ $withdraw->id }}"
                                                    data-type="1">{{ app()->getLocale() == 'ar' ? 'الموافقة' : 'Approve' }}</button>
                                            <button class="btn btn-danger change_status" data-id="{{ $withdraw->id }}"
                                                    data-type="2">{{ app()->getLocale() == 'ar' ? 'الرفض' : 'Not Approve' }}</button>
                                        </td>
                                    @endif
                                    <td>{{ $withdraw->add_time }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

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
                            <th>@lang('common.amount')</th>
                            <th>@lang('common.transfer_image')</th>
                            <th>@lang('common.status')</th>
                            <th>@lang('common.created_at')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($artist_cash_transfers as $transfer)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $transfer->amount }} @lang('common.rq')</td>
                                <td><img src="{{ $transfer->image }}" class="image_view" style="width: 150px"></td>
                                <td id="span_text_{{$transfer->id}}">
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
                                <td>{{ $transfer->add_time }}</td>
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
                    <form id="editUserForm" data-reset="true" method="POST" class="row gy-1 pt-75" action="{{ route('artist_financial_accounts.make_withdraw') }}"
                          enctype="multipart/form-data">
                        @csrf
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

@endsection

@section('scripts')
    <script>
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
                url: '{{ url(app()->getLocale() . '/artist/financial_accounts/update_status') }}',
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

        $(document).on('click', '.make_withdraw', function (event) {
            var button = $(this);
            var id = button.data('id');
            var remaining_amount = button.data('remaining');

            $('#remaining_amount').attr('value', remaining_amount);
        });
    </script>
@endsection
