@extends('admin.layouts.app')

@section('main-content')
    <section class="app-user-view-account">
        <div class="row">
            <!-- User Sidebar -->
            <div class="col-xl-12 col-lg-5 col-md-5 order-1 order-md-0">
                <!-- User Card -->
                <div class="card">
                    <div class="card-body">
                        <div class="user-avatar-section">
                            <div class="d-flex align-items-center flex-column">
                                <img class="img-fluid rounded mt-3 mb-2" src="{{ auth('admin')->user()->image }}"
                                     height="110" width="110" alt="User avatar"/>
                                <div class="user-info text-center">
                                    <h4>{{ auth('admin')->user()->name }}</h4>
                                    <span class="badge bg-light-secondary">@lang('common.admin')</span>
                                </div>
                            </div>
                        </div>
                        <h4 class="fw-bolder border-bottom pb-50 mb-1">@lang('common.details')</h4>
                        <div class="info-container">
                            <ul class="list-unstyled">
                                <li class="mb-75">
                                    <span class="fw-bolder me-25">@lang('common.name'):</span>
                                    <span>{{ auth('admin')->user()->name }}</span>
                                </li>
                                <li class="mb-75">
                                    <span class="fw-bolder me-25">@lang('common.email')</span>
                                    <span>{{ auth('admin')->user()->email }}</span>
                                </li>
{{--                                <li class="mb-75">--}}
{{--                                    <span class="fw-bolder me-25">@lang('common.mobile'):</span>--}}
{{--                                    <span>{{ auth('admin')->user()->mobile }}</span>--}}
{{--                                </li>--}}
                            </ul>
                            <div class="d-flex justify-content-center pt-2">
                                <a href="javascript:;" class="btn btn-primary me-1 edit_btn" data-bs-target="#editUser"
                                   data-bs-toggle="modal">
                                    @lang('common.edit')
                                </a>
                                <a href="javascript:;" class="btn btn-warning me-1 edit_password_btn" data-bs-target="#editUserPassword"
                                   data-bs-toggle="modal">
                                    @lang('common.edit') @lang('common.password')
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /User Card -->
            </div>
        </div>
    </section>

    <div class="modal fade" id="editUser" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 px-sm-5 pt-50">
                    <div class="text-center mb-2">
                        <h1 class="mb-1">@lang('common.edit') @lang('common.info')</h1>
                    </div>
                    <form id="editUserForm" data-reset="true" method="POST" class="row gy-1 pt-75 ajax_form"
                          onsubmit="return false" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="modalEditUserFirstName">@lang('common.name')</label>
                            <input type="text" id="modalEditUserFirstName" name="name"
                                   class="form-control" placeholder="John" value="{{ auth('admin')->user()->name }}"
                                   data-msg="Please enter your first name"/>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="modalEditUserEmail">@lang('common.email')</label>
                            <input type="text" id="modalEditUserEmail" name="email" class="form-control"
                                   value="{{ auth('admin')->user()->email }}" placeholder="example@domain.com"/>
                        </div>
{{--                        <div class="col-12 col-md-12">--}}
{{--                            <label class="form-label" for="modalEditUserMobile">@lang('common.mobile')</label>--}}
{{--                            <input type="text" id="modalEditUserMobile" name="mobile" class="form-control"--}}
{{--                                   value="{{ auth('admin')->user()->mobile }}" placeholder="966xxxxxxxxx"/>--}}
{{--                        </div>--}}
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="end_date">{{ __('common.image') }}</label>
                            <br>
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;"
                                     data-trigger="fileinput">
                                    <img src="{{ auth('admin')->user()->image }}" alt="..." id="edit_image">
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
                            <button type="submit"
                                    class="btn btn-primary me-1 submit_btn">@lang('common.save_changes')</button>
                            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                                    aria-label="Close">
                                @lang('common.cancel')
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editUserPassword" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 px-sm-5 pt-50">
                    <div class="text-center mb-2">
                        <h1 class="mb-1">@lang('common.edit') @lang('common.password')</h1>
                    </div>
                    <form id="editUserPasswordForm" data-reset="true" method="POST" class="row gy-1 pt-75 ajax_form"
                          onsubmit="return false" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="col-12 col-md-12">
                            <label class="form-label" for="modalEditUserFirstName">@lang('common.current_password')</label>
                            <input type="password" id="modalEditUserFirstName" name="current_password" class="form-control" autocomplete="new-password" placeholder="*******"/>
                        </div>
                        <div class="col-12 col-md-12">
                            <label class="form-label" for="modalEditUserEmail">@lang('common.new_password')</label>
                            <input type="password" id="modalEditUserEmail" name="new_password" class="form-control" autocomplete="new-password" placeholder="*******"/>
                        </div>
                        <div class="col-12 col-md-12">
                            <label class="form-label" for="modalEditUserEmail">@lang('common.confirm_password')</label>
                            <input type="password" id="modalEditUserEmail" name="confirm_password" class="form-control" autocomplete="new-password" placeholder="*******"/>
                        </div>
                        <div class="col-12 text-center mt-2 pt-50">
                            <button type="submit"
                                    class="btn btn-primary me-1 submit_btn">@lang('common.save_changes')</button>
                            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                                    aria-label="Close">
                                @lang('common.cancel')
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
        var url = '{{ url(app()->getLocale() . "/tmg/profile") }}/';

        $(document).ready(function () {
            $(document).on('click', '.edit_btn', function (event) {
                var button = $(this);
                var id = button.data('id');
                $('#editUserForm').attr('action', url + 'update');
            });

            $(document).on('click', '.edit_password_btn', function (event) {
                var button = $(this);
                var id = button.data('id');
                $('#editUserPasswordForm').attr('action', url + 'update_password');
            });
        });
    </script>
@endsection
