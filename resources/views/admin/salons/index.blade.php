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
                    <h4 class="card-title">@lang('common.salons')</h4>
                </div>
                <div class="card-body">
                    @if(auth()->user()->hasAnyPermission(['add_salon']))
                        <button type="button" class="btn btn-outline-primary add_btn" style="float: left"
                                id="create_btn"
                                data-bs-original-title="Edit" data-bs-toggle="modal"
                                data-bs-target=".create_modal">+ @lang('common.add')</button>
                    @endif
                    @if(auth()->user()->hasAnyPermission(['edit_salon_status']))
                        <button value="1" disabled="disabled" id="status_btn" class="status_btn btn btn-dark">
                            @lang('common.activate')
                        </button>
                        <button value="0" disabled="disabled" id="status_btn" class="status_btn btn btn-warning">
                            @lang('common.deactivate')
                        </button>
                    @endif
                    @if(auth()->user()->hasAnyPermission(['delete_salon']))
                        <button disabled="disabled" id="delete_btn" class="delete-btn btn btn-danger"><i
                                class="fa fa-lg fa-trash-o"></i> @lang('common.delete')</button>
                    @endif
                </div>
                <form id="search_form" style="margin-right: 25px;margin-top: 30px">
                    <h6>@lang('common.search')</h6>
                    <div class="form-row">
                        <div class="form-group">
                            <input name="name" id="s_name" class="form-control" style="width: 15%; display: inline"
                                   placeholder="@lang('common.name')">
                            <input name="email" id="s_email" class="form-control" style="width: 15%; display: inline"
                                   placeholder="@lang('common.email')">
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
                            <th>@lang('common.image')</th>
                            <th>@lang('common.name')</th>
                            <th>@lang('common.email')</th>
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
                        <h1 class="mb-1">@lang('common.add') @lang('common.salon')</h1>
                    </div>
                    <form id="create_form" data-reset="true" method="POST" class="row gy-1 pt-75 ajax_form"
                          onsubmit="return false" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="type" value="{{ $type }}">
                        @foreach(locales() as $key => $value)
                            <div class="col-12 col-md-6">
                                <label class="form-label"
                                       for="name_{{ $key }}">{{ __('common.name') . ' - ' . __('common.'.$value) }}</label>
                                <input type="text" id="name_{{ $key }}" name="name_{{ $key }}" class="form-control"
                                       placeholder="{{ __('common.name') . ' - ' . __('common.'.$value) }}"/>
                                @if($errors->has('name_'.$key))
                                    <div
                                        style="color: red">{{ $errors->first('name_'.$key) }}</div>
                                @endif
                            </div>
                        @endforeach
                        @foreach(locales() as $key => $value)
                            <div class="col-12 col-md-12">
                                <label class="form-label"
                                       for="bio_{{ $key }}">{{ __('common.bio') . ' - ' . __('common.'.$value) }}</label>
                                <textarea type="text" id="bio_{{ $key }}" name="bio_{{ $key }}" class="form-control"
                                          style="height: 150px;"
                                          placeholder="{{ __('common.bio') . ' - ' . __('common.'.$value) }}"></textarea>
                                @if($errors->has('bio_'.$key))
                                    <div
                                        style="color: red">{{ $errors->first('name_'.$key) }}</div>
                                @endif
                            </div>
                        @endforeach
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="email">{{ __('common.email') }}</label>
                            <input type="email" id="email" name="email" class="form-control"
                                   placeholder="{{ __('common.email') }}"/>
                            @if($errors->has('email'))
                                <div
                                    style="color: red">{{ $errors->first('email') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="password">{{ __('common.password') }}</label>
                            <input type="password" id="password" name="password" class="form-control"
                                   placeholder="{{ __('common.password') }}"/>
                            @if($errors->has('password'))
                                <div
                                    style="color: red">{{ $errors->first('password') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="mobile">{{ __('common.mobile') }}</label>
                            <input type="tel" id="mobile" name="mobile" class="form-control"
                                   placeholder="{{ __('common.mobile') }}"/>
                            @if($errors->has('mobile'))
                                <div
                                    style="color: red">{{ $errors->first('mobile') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="category_id">{{ __('common.sections') }}</label>
                            <select id="category_id" name="category_id[]" class="form-control select2" multiple>
                                {{--                                <option selected disabled>@lang('common.choose')</option>--}}
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('category_id'))
                                <div
                                    style="color: red">{{ $errors->first('category_id') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="bank_name">{{ __('common.bank_name') }}</label>
                            <input type="text" id="bank_name" name="bank_name" class="form-control"
                                   placeholder="{{ __('common.bank_name') }}"/>
                            @if($errors->has('bank_name'))
                                <div
                                    style="color: red">{{ $errors->first('bank_name') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="bank_account_name">{{ __('common.bank_account_name') }}</label>
                            <input type="text" id="bank_account_name" name="bank_account_name" class="form-control"
                                   placeholder="{{ __('common.bank_account_name') }}"/>
                            @if($errors->has('bank_account_name'))
                                <div
                                    style="color: red">{{ $errors->first('bank_account_name') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="bank_account_number">{{ __('common.bank_account_number') }}</label>
                            <input type="text" id="bank_account_number" name="bank_account_number" class="form-control"
                                   placeholder="{{ __('common.bank_account_number') }}"/>
                            @if($errors->has('bank_account_number'))
                                <div
                                    style="color: red">{{ $errors->first('bank_account_number') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="iban">{{ __('common.iban') }}</label>
                            <input type="text" id="iban" name="iban" class="form-control"
                                   placeholder="{{ __('common.iban') }}"/>
                            @if($errors->has('iban'))
                                <div
                                    style="color: red">{{ $errors->first('iban') }}</div>
                            @endif
                        </div>
                        <div class="col-6">
                            <label class="form-label"
                                   for="end_date">{{ __('common.logo') }}</label>
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
                        <div class="col-6">
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
                                            <input type="file" name="cover_image" accept="image/*">
                                        </span>
                                    <a href="#" class="btn btn-orange fileinput-exists"
                                       data-dismiss="fileinput">@lang('common.remove')</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <label class="form-label"
                                   for="end_date">{{ __('common.video') }}</label>
                            <br>
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <video style="width: 205px;height: 155px" controls>
                                    <source src="" id="video_here">
                                </video>
                                <br>
                                <span class="btn btn-white btn-file">
                                    <span class="fileinput-new">@lang('common.chooseVideo')</span>
                                    <span class="fileinput-exists">@lang('common.change')</span>
                                    <input type="file" name="video" class="file_video" accept="video/*">
                                </span>
                                {{--                                <a href="#" class="btn btn-orange fileinput-exists"--}}
                                {{--                                   data-dismiss="fileinput">@lang('common.remove')</a>--}}
                            </div>
                        </div>
                        <div class="col-6">
                            <label class="form-label"
                                   for="end_date">{{ __('common.thumbnail') }}</label>
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
                                            <input type="file" name="thumbnail" accept="image/*">
                                        </span>
                                    <a href="#" class="btn btn-orange fileinput-exists"
                                       data-dismiss="fileinput">@lang('common.remove')</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label"
                                   for="end_date">{{ __('common.salon_images') }}</label>
                            <div class="input-images_2"></div>
                        </div>
                        <label class="form-label"
                               for="address">{{ __('common.address') }}</label>
                        <div class="col-12">
                            <input id="address" name="address_text" class="form-control  mb-2" readonly placeholder="Address">
                        </div>
                        <div class="col-5">
                            <input id="search_address_lat" class="form-control mb-2" placeholder="Lat" name="lat">
                        </div>
                        <div class="col-5">
                            <input id="search_address_lng" class="form-control mb-2" placeholder="Long" name="lng">
                        </div>
                        <div class="col-2">
                            <button class="btn btn-info" type="button" id="search_location" style=";margin-right: -15px">@lang('common.search')</button>
                        </div>
                        <div class="col-12">
                            <div class="map" id="map" style="width: 100%; height: 300px"></div>
                            <div class="form-group">
                                <div class="col-md-6">
                                    <input type="hidden" class="form-control" name="lat"
                                           value="{{request('lat')}}"
                                           id="lat" placeholder=" {{__('common.lat')}}" required>
                                    @if($errors->has('lat'))
                                        <p class="error">
                                            <small>{{ $errors->first('lat') }}</small>
                                        </p>
                                    @endif
                                    <input type="hidden" class="form-control" name="lng"
                                           value="{{request('lng')}}"
                                           id="lng" placeholder=" {{__('common.lng')}}" required>
                                    @if($errors->has('lng'))
                                        <p class="error">
                                            <small>{{ $errors->first('lng') }}</small>
                                        </p>
                                    @endif
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
                        <h1 class="mb-1">@lang('common.edit') @lang('common.salon')</h1>
                    </div>
                    <form id="editUserForm" data-reset="true" method="POST" class="row gy-1 pt-75 ajax_form"
                          onsubmit="return false" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="type" value="{{ $type }}">
                        @foreach(locales() as $key => $value)
                            <div class="col-12 col-md-6">
                                <label class="form-label"
                                       for="edit_name_{{ $key }}">{{ __('common.name') . ' - ' . __('common.'.$value) }}</label>
                                <input type="text" id="edit_name_{{ $key }}" name="name_{{ $key }}" class="form-control"
                                       placeholder="{{ __('common.name') . ' - ' . __('common.'.$value) }}"/>
                                @if($errors->has('name_'.$key))
                                    <div
                                        style="color: red">{{ $errors->first('name_'.$key) }}</div>
                                @endif
                            </div>
                        @endforeach
                        @foreach(locales() as $key => $value)
                            <div class="col-12 col-md-12">
                                <label class="form-label"
                                       for="edit_bio_{{ $key }}">{{ __('common.bio') . ' - ' . __('common.'.$value) }}</label>
                                <textarea type="text" id="edit_bio_{{ $key }}" name="bio_{{ $key }}"
                                          class="form-control" style="height: 150px;"
                                          placeholder="{{ __('common.bio') . ' - ' . __('common.'.$value) }}"></textarea>
                                @if($errors->has('bio_'.$key))
                                    <div
                                        style="color: red">{{ $errors->first('name_'.$key) }}</div>
                                @endif
                            </div>
                        @endforeach
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="edit_email">{{ __('common.email') }}</label>
                            <input type="email" id="edit_email" name="email" class="form-control"
                                   placeholder="{{ __('common.email') }}"/>
                            @if($errors->has('email'))
                                <div
                                    style="color: red">{{ $errors->first('email') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="edit_password">{{ __('common.password') }}</label>
                            <input type="password" id="edit_password" name="password" class="form-control"
                                   autocomplete="new-password"
                                   placeholder="{{ __('common.password') }}"/>
                            @if($errors->has('password'))
                                <div
                                    style="color: red">{{ $errors->first('password') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="edit_mobile">{{ __('common.mobile') }}</label>
                            <input type="tel" id="edit_mobile" name="mobile" class="form-control"
                                   placeholder="{{ __('common.mobile') }}"/>
                            @if($errors->has('mobile'))
                                <div
                                    style="color: red">{{ $errors->first('mobile') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="edit_category_id">{{ __('common.sections') }}</label>
                            <select id="edit_category_id" name="category_id[]" class="form-control select2" multiple>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('category_id'))
                                <div
                                    style="color: red">{{ $errors->first('category_id') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="edit_bank_name">{{ __('common.bank_name') }}</label>
                            <input type="text" id="edit_bank_name" name="bank_name" class="form-control"
                                   placeholder="{{ __('common.bank_name') }}"/>
                            @if($errors->has('bank_name'))
                                <div
                                    style="color: red">{{ $errors->first('bank_name') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="edit_bank_account_name">{{ __('common.bank_account_name') }}</label>
                            <input type="text" id="edit_bank_account_name" name="bank_account_name" class="form-control"
                                   placeholder="{{ __('common.bank_account_name') }}"/>
                            @if($errors->has('bank_account_name'))
                                <div
                                    style="color: red">{{ $errors->first('bank_account_name') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="edit_bank_account_number">{{ __('common.bank_account_number') }}</label>
                            <input type="text" id="edit_bank_account_number" name="bank_account_number"
                                   class="form-control"
                                   placeholder="{{ __('common.bank_account_number') }}"/>
                            @if($errors->has('bank_account_number'))
                                <div
                                    style="color: red">{{ $errors->first('bank_account_number') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="edit_iban">{{ __('common.iban') }}</label>
                            <input type="text" id="edit_iban" name="iban" class="form-control"
                                   placeholder="{{ __('common.iban') }}"/>
                            @if($errors->has('iban'))
                                <div
                                    style="color: red">{{ $errors->first('iban') }}</div>
                            @endif
                        </div>
                        <div class="col-6">
                            <label class="form-label"
                                   for="end_date">{{ __('common.logo') }}</label>
                            <br>
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;"
                                     data-trigger="fileinput">
                                    <img src="{{ asset('placeholder.jpg') }}" alt="..." id="edit_image">
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
                        <div class="col-6">
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
                                            <input type="file" name="cover_image" accept="image/*">
                                        </span>
                                    <a href="#" class="btn btn-orange fileinput-exists"
                                       data-dismiss="fileinput">@lang('common.remove')</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <label class="form-label"
                                   for="edit_video_here">{{ __('common.video') }}</label>
                            <br>
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <video style="width: 205px;height: 155px" controls>
                                    <source src="" id="edit_video_here">
                                </video>
                                <br>
                                <span class="btn btn-white btn-file">
                                    <span class="fileinput-new">@lang('common.chooseVideo')</span>
                                    <span class="fileinput-exists">@lang('common.change')</span>
                                    <input type="file" name="video" class="edit_file_video" accept="video/*">
                                </span>
                                {{--                                <a href="#" class="btn btn-orange fileinput-exists"--}}
                                {{--                                   data-dismiss="fileinput">@lang('common.remove')</a>--}}
                            </div>
                        </div>
                        <div class="col-6">
                            <label class="form-label"
                                   for="edit_thumbnail">{{ __('common.thumbnail') }}</label>
                            <br>
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;"
                                     data-trigger="fileinput">
                                    <img src="{{ asset('placeholder.jpg') }}" id="edit_thumbnail" alt="...">
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail"
                                     style="max-width: 200px; max-height: 150px"></div>
                                <div>
                                        <span class="btn btn-white btn-file">
                                            <span class="fileinput-new">@lang('common.chooseImage')</span>
                                            <span class="fileinput-exists">@lang('common.change')</span>
                                            <input type="file" name="thumbnail" accept="image/*">
                                        </span>
                                    <a href="#" class="btn btn-orange fileinput-exists"
                                       data-dismiss="fileinput">@lang('common.remove')</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label"
                                   for="end_date">{{ __('common.salon_images') }}</label>
                            <div class="input-images_4"></div>
                        </div>
                        <label class="form-label"
                               for="address">{{ __('common.address') }}</label>
                        <div class="col-12">
                            <input id="edit_address" name="address_text" class="form-control  mb-2" readonly placeholder="Address">
                        </div>
                        <div class="col-5">
                            <input id="edit_search_address_lat" class="form-control mb-2" placeholder="Lat" name="lat">
                        </div>
                        <div class="col-5">
                            <input id="edit_search_address_lng" class="form-control mb-2" placeholder="Long" name="lng">
                        </div>
                        <div class="col-2">
                            <button class="btn btn-info" type="button" id="edit_search_location" style=";margin-right: -15px">@lang('common.search')</button>
                        </div>
                        <div class="col-12">
                            <div class="map" id="edit_map" style="width: 100%; height: 300px"></div>
                            <div class="form-group">
                                <div class="col-md-6">
                                    <input type="hidden" class="form-control" name="lat"
                                           value="{{request('lat')}}"
                                           id="edit_lat" placeholder=" {{__('common.lat')}}" required>
                                    @if($errors->has('lat'))
                                        <p class="error">
                                            <small>{{ $errors->first('lat') }}</small>
                                        </p>
                                    @endif
                                    <input type="hidden" class="form-control" name="lng"
                                           value="{{request('lng')}}"
                                           id="edit_lng" placeholder=" {{__('common.lng')}}" required>
                                    @if($errors->has('lng'))
                                        <p class="error">
                                            <small>{{ $errors->first('lng') }}</small>
                                        </p>
                                    @endif
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
    <script type="text/javascript"
            src="{{ asset('app-assets/image-uploader-master/dist/image-uploader_2.min.js') }}"></script>

    <script>
        $('.input-images_2').imageUploader_2({
            imagesInputName: 'images',
        });

        $(document).on("change", ".file_video", function (evt) {
            var $source = $('#video_here');
            $source[0].src = URL.createObjectURL(this.files[0]);
            $source.parent()[0].load();
        });

        $(document).on("change", ".edit_file_video", function (evt) {
            var $source = $('#edit_video_here');
            $source[0].src = URL.createObjectURL(this.files[0]);
            $source.parent()[0].load();
        });


        var url = '{{ url(app()->getLocale() . "/tmg/salons/") }}/';

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
                url: url + {{ $type }} + '/indexTable',
                data: function (d) {
                    d.name = $('#s_name').val();
                    d.email = $('#s_email').val();
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
                {data: 'name', name: 'name', orderable: false, searchable: false},
                {data: 'email', name: 'email', orderable: false, searchable: false},
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
                $('.services_2').html('');
                $('.input-images_4').html('');

                var button = $(this);
                var id = button.data('id');

                $('#editUserForm').attr('action', url + id + '/update');
                $('#edit_name_ar').val(button.data('name_ar'));
                $('#edit_name_en').val(button.data('name_en'));
                $('#edit_bio_ar').val(button.data('bio_ar'));
                $('#edit_bio_en').val(button.data('bio_en'));
                $('#edit_email').val(button.data('email'));
                $('#edit_lat').val(button.data('lat'));
                $('#edit_lng').val(button.data('lng'));
                $('#edit_address').val(button.data('address_text'));
                $('#edit_bank_name').val(button.data('bank_name'));
                $('#edit_bank_account_name').val(button.data('bank_account_name'));
                $('#edit_iban').val(button.data('iban'));
                $('#edit_bank_account_number').val(button.data('bank_account_number'));

                $('#edit_category_id').val(button.data('category_id'));
                $('#edit_category_id').trigger('change');

                $('#edit_mobile').val(button.data('mobile'));
                $('#edit_image').attr('src', button.data('image'));
                $('#edit_cover_image').attr('src', button.data('cover_image'));
                $('#edit_thumbnail').attr('src', button.data('thumbnail'));

                var $source = $('#edit_video_here');
                $source[0].src = button.data('video');
                $source.parent()[0].load();

                let preloaded = [];
                $.each(button.data('images'), function (index, value) {
                    if (value.type == 1) {
                        preloaded.push({id: value.id, src: value.item});
                    }
                });

                $('.input-images_4').imageUploader_2({
                    preloaded: preloaded,
                    imagesInputName: 'images',
                    maxSize: 2 * 1024 * 1024,
                    maxFiles: 10
                });

                address_map_2();
            });

            $(document).on('click', '#create_btn', function (event) {
                $('#create_form').attr('action', '{{ route('salons.store') }}');
            });
        });

        function add_remove(id) {

            $(this).removeClass('btn btn-dark');
            $(this).addClass('btn btn-warning');
            $.ajaxSetup({
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
            });

            $.ajax({
                url: '{{ url(app()->getLocale() . '/tmg/salons/update_status/') }}/' + id,
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

        function initMap(lat, lng) {
            address_map();
            address_map_2();
        }

        function address_map() {
            var geocoder = new google.maps.Geocoder;
            const infowindow = new google.maps.InfoWindow();
            var input = document.getElementById('address');
            var lat = parseFloat(document.getElementById('lat').value);
            var lng = parseFloat(document.getElementById('lng').value);
            var uluru = {lat: lat || 25.3548, lng: lng || 51.1839};
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 9,
                center: uluru
            });
            const autocomplete = new google.maps.places.Autocomplete(input, {
                fields: ["place_id", "geometry", "formatted_address", "name"],
            });
            autocomplete.bindTo('bounds', map);

            autocomplete.addListener('place_changed', function () {
                infowindow.close();
                marker_2.setVisible(false);
                var place = autocomplete.getPlace();
                if (!place.geometry) {
                    // User entered the name of a Place that was not suggested and
                    // pressed the Enter key, or the Place Details request failed.
                    return;
                }

                // If the place has a geometry, then present it on a map.
                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(17);  // Why 17? Because it looks good.
                }
                marker_2.setPosition(place.geometry.location);
                marker_2.setVisible(true);
                document.getElementById('lat').value = place.geometry.location.lat();
                document.getElementById('lng').value = place.geometry.location.lng();
            });

            if (isNaN(lat) && isNaN(lng)) {
                marker_2 = new google.maps.Marker({
                    map: map,
                });
            } else {
                marker_2 = new google.maps.Marker({
                    position: uluru,
                    map: map,
                });
            }

            google.maps.event.addListener(map, "click", function (event) {
                placeMarker(event.latLng);
                getAddress(event.latLng, map, infowindow, geocoder, marker_2);
            });

            function placeMarker(location) {
                if (marker_2 === null) {
                    marker_2 = new google.maps.Marker({
                        position: location,
                        map: map,
                    });
                } else {
                    marker_2.setPosition(location);
                }
                var latlng = {lat: parseFloat(location.lat()), lng: parseFloat(location.lng())};

                $('#lat').val(latlng.lat);
                $('#lng').val(latlng.lng);
                $('#latlngs').change();

                getAddress(location, map, infowindow, geocoder, marker_2);
            }

            document.getElementById("search_location").addEventListener("click", () => {
                geocodeLatLng(geocoder, map, infowindow, marker_2);
            });
        }

        function address_map_2() {
            var geocoder = new google.maps.Geocoder;
            var infowindow = new google.maps.InfoWindow;
            var input = document.getElementById('edit_address');
            var lat = parseFloat(document.getElementById('edit_lat').value);
            var lng = parseFloat(document.getElementById('edit_lng').value);
            var uluru = {lat: lat || 25.3548, lng: lng || 51.1839};
            var map = new google.maps.Map(document.getElementById('edit_map'), {
                zoom: 9,
                center: uluru
            });
            const autocomplete = new google.maps.places.Autocomplete(input, {
                fields: ["place_id", "geometry", "formatted_address", "name"],
            });
            autocomplete.bindTo('bounds', map);

            autocomplete.addListener('place_changed', function () {
                infowindow.close();
                marker.setVisible(false);
                var place = autocomplete.getPlace();
                if (!place.geometry) {
                    // User entered the name of a Place that was not suggested and
                    // pressed the Enter key, or the Place Details request failed.
                    return;
                }

                // If the place has a geometry, then present it on a map.
                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(17);  // Why 17? Because it looks good.
                }
                marker.setPosition(place.geometry.location);
                marker.setVisible(true);
                document.getElementById('edit_lat').value = place.geometry.location.lat();
                document.getElementById('edit_lng').value = place.geometry.location.lng();
            });

            if (isNaN(lat) && isNaN(lng)) {
                marker = new google.maps.Marker({
                    map: map,
                });
            } else {
                marker = new google.maps.Marker({
                    position: uluru,
                    map: map,
                });
            }

            google.maps.event.addListener(map, "click", function (event) {
                placeMarker2(event.latLng);
                getAddress2(event.latLng, map, infowindow, geocoder, marker);
            });

            function placeMarker2(location) {
                if (marker === null) {
                    marker = new google.maps.Marker({
                        position: location,
                        map: map,
                    });
                } else {
                    marker.setPosition(location);
                }
                var latlng = {lat: parseFloat(location.lat()), lng: parseFloat(location.lng())};

                $('#edit_lat').val(latlng.lat);
                $('#edit_lng').val(latlng.lng);
                $('#latlngs').change();

                getAddress2(location, map, infowindow, geocoder, marker);
            }

            document.getElementById("edit_search_location").addEventListener("click", () => {
                geocodeLatLng2(geocoder, map, infowindow, marker);
            });
        }

        function geocodeLatLng(geocoder, map, infowindow, marker) {
            const lat = document.getElementById("search_address_lat").value;
            const lng = document.getElementById("search_address_lng").value;
            const latlng = {
                lat: parseFloat(lat),
                lng: parseFloat(lng),
            };

            document.getElementById('lat').value = lat;
            document.getElementById('lng').value = lng;

            getAddress(latlng, map, infowindow, geocoder, marker);
        }

        function geocodeLatLng2(geocoder, map, infowindow, marker) {
            const lat = document.getElementById("edit_search_address_lat").value;
            const lng = document.getElementById("edit_search_address_lng").value;
            const latlng = {
                lat: parseFloat(lat),
                lng: parseFloat(lng),
            };

            document.getElementById('edit_lat').value = lat;
            document.getElementById('edit_lng').value = lng;

            getAddress2(latlng, map, infowindow, geocoder, marker);
        }

        function getAddress(latlng, map, infowindow, geocoder, marker) {
            geocoder.geocode({'latLng': latlng},
                function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[0]) {
                            document.getElementById("address").value = results[0].formatted_address;

                            marker.setPosition(latlng);

                            infowindow.setContent(results[0].formatted_address);
                            infowindow.open(map, marker);
                        } else {
                            document.getElementById("address").value = "No results";
                        }
                    } else {
                        document.getElementById("address").value = status;
                    }
                }
            );
        }

        function getAddress2(latlng, map, infowindow, geocoder, marker) {
            geocoder.geocode({'latLng': latlng},
                function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[0]) {
                            document.getElementById("edit_address").value = results[0].formatted_address;

                            marker.setPosition(latlng);

                            infowindow.setContent(results[0].formatted_address);
                            infowindow.open(map, marker);
                        } else {
                            document.getElementById("edit_address").value = "No results";
                        }
                    } else {
                        document.getElementById("edit_address").value = status;
                    }
                }
            );
        }

    </script>

    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCa_N3am8UImF1aoqlmC2lISfTxNfDeGmc&libraries=places&callback=initMap"
        async defer></script>
@endsection
