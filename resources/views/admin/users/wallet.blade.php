@extends('admin.layouts.app')

@section('main-content')
    <style>
        .alert{
            padding: 10px !important;
        }
    </style>
    @include('flash::message')
    <section class="app-user-view-account">
        <div class="row">
            <!-- User Sidebar -->
            <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
                <!-- User Card -->
                <div class="card">
                    <div class="card-body">
                        <div class="user-avatar-section">
                            <div class="d-flex align-items-center flex-column">
                                <img class="img-fluid rounded mt-3 mb-2" src="{{ $user->image }}" height="110" width="110" alt="User avatar" />
                                <div class="user-info text-center">
                                    <h4>{{ $user->name }}</h4>
                                </div>
                                <div class="user-info text-center">
                                    <h4 style="font-weight: bold">@lang('common.balance') : {{ $user->balance }} @lang('common.rial')</h4>
                                </div>
                            </div>
                        </div>
                        <div class="info-container">
                            <div class="d-flex justify-content-center pt-2">
                                <button class="btn btn-dark add_balance" style="margin-left: 15px;" data-bs-placement="top" title="" data-bs-original-title="Edit" data-bs-toggle="modal" data-bs-target="#add_balance"
                                        data-id="{{ $user->id }}">
                                    @lang('common.add_balance')
                                </button>
                                <button class="btn btn-danger remove_balance" data-bs-placement="top" title="" data-bs-original-title="Edit" data-bs-toggle="modal" data-bs-target="#remove_balance"
                                        data-id="{{ $user->id }}">
                                    @lang('common.remove_balance')
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
                <div class="card">
                    <h4 class="card-header">@lang('common.transactions')</h4>
                    <div class="card-body pt-1">
                        <ul class="timeline ms-50">
                            @foreach($transactions as $transaction)
                                <li class="timeline-item">
                                    <span class="timeline-point timeline-point-indicator"></span>
                                    <div class="timeline-event">
                                        <div class="d-flex justify-content-between flex-sm-row flex-column mb-sm-0 mb-1">
                                            <h6 style="font-weight: bold">{{ $transaction->title }}</h6>
                                            <span class="timeline-event-time me-1" style="font-weight: bold">{{ $transaction->date }}</span>
                                        </div>
                                        <p style="font-weight: bold">{{ $transaction->price . ' ' . __('common.rial') }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="add_balance" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 px-sm-5 pt-50">
                    <div class="text-center mb-2">
                        <h1 class="mb-1">@lang('common.add_balance')</h1>
                    </div>
                    <form id="add_balance_form" data-reset="true" method="POST" class="row gy-1 pt-75" enctype="multipart/form-data"
                    action="{{ route('users.update_balance', $user->id) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="type" value="1">
                        <div class="col-12 col-md-12">
                            <label class="form-label"
                                   for="balance">{{ __('common.balance') }}</label>
                            <input type="number" id="balance" name="balance" class="form-control" min="0"
                                   placeholder="{{ __('common.balance') }}"/>
                            @if($errors->has('balance'))
                                <div
                                    style="color: red">{{ $errors->first('balance') }}</div>
                            @endif
                        </div>
                        <div class="col-12 text-center mt-2 pt-50">
                            <button type="submit" class="btn btn-primary me-1"
                                    form="add_balance_form">@lang('common.save_changes')</button>
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

    <div class="modal fade" id="remove_balance" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 px-sm-5 pt-50">
                    <div class="text-center mb-2">
                        <h1 class="mb-1">@lang('common.remove_balance')</h1>
                    </div>
                    <form id="remove_balance_form" data-reset="true" method="POST" class="row gy-1 pt-75"
                          action="{{ route('users.update_balance', $user->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="type" value="2">
                        <div class="col-12 col-md-12">
                            <label class="form-label"
                                   for="balance">{{ __('common.balance') }}</label>
                            <input type="number" id="balance" name="balance" class="form-control" min="0"
                                   placeholder="{{ __('common.balance') }}"/>
                            @if($errors->has('balance'))
                                <div
                                    style="color: red">{{ $errors->first('balance') }}</div>
                            @endif
                        </div>
                        <div class="col-12 text-center mt-2 pt-50">
                            <button type="submit" class="btn btn-primary me-1"
                                    form="remove_balance_form">@lang('common.save_changes')</button>
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
