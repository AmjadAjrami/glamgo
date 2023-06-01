@extends('artist.layouts.app')

@section('main-content')
    <style>
        .alert.alert-success{
            padding: 10px;
            width: 97%;
            margin-right: 21px;
        }
        .select2{
            visibility: visible !important;
        }
        .alert.alert-danger {
            padding: 10px;
        }
    </style>
    <link type="text/css" rel="stylesheet"
          href="{{ asset('app-assets/image-uploader-master/dist/image-uploader.min.css') }}">

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger">{{$error}}</div>
        @endforeach
    @endif

    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">@lang('common.artist_info')</h4>
                </div>
                @include('flash::message')
                <div class="card-body">
                    <form data-reset="true" method="POST" class="row gy-1 pt-75" enctype="multipart/form-data" action="{{ route('artist_info.update') }}">
                        @csrf
                        @method('PUT')

                        @foreach(locales() as $key => $value)
                            <div class="col-12 col-md-6">
                                <label class="form-label"
                                       for="name_{{ $key }}">{{ __('common.name') . ' - ' . __('common.'.$value) }}</label>
                                <input type="text" id="name_{{ $key }}" name="name_{{ $key }}" class="form-control" value="{{ $artist->translate($key)->name }}"
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
                                          placeholder="{{ __('common.bio') . ' - ' . __('common.'.$value) }}">{{ $artist->translate($key)->bio }}</textarea>
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
                                   placeholder="{{ __('common.email') }}" value="{{ $artist->email }}"/>
                            @if($errors->has('email'))
                                <div
                                    style="color: red">{{ $errors->first('email') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="password">{{ __('common.password') }}</label>
                            <input type="password" id="password" name="password" class="form-control" autocomplete="new-password"
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
                                   placeholder="{{ __('common.mobile') }}" value="{{ $artist->mobile }}"/>
                            @if($errors->has('mobile'))
                                <div
                                    style="color: red">{{ $errors->first('mobile') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label"
                                   for="category_id">{{ __('common.categories') }}</label>
                            <select id="category_id" name="category_id[]" class="form-control select2" multiple>
{{--                                <option selected disabled>@lang('common.choose')</option>--}}
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ in_array($category->id, $artist->makeup_artist_categories->pluck('category_id')->toArray()) ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('category_id'))
                                <div
                                    style="color: red">{{ $errors->first('category_id') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label"
                                   for="bank_name">{{ __('common.bank_name') }}</label>
                            <input type="text" id="bank_name" name="bank_name" class="form-control" value="{{ $artist->bank_name }}"
                                   placeholder="{{ __('common.bank_name') }}"/>
                            @if($errors->has('bank_name'))
                                <div
                                    style="color: red">{{ $errors->first('bank_name') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label"
                                   for="bank_account_name">{{ __('common.bank_account_name') }}</label>
                            <input type="text" id="bank_account_name" name="bank_account_name" class="form-control" value="{{ $artist->bank_account_name }}"
                                   placeholder="{{ __('common.bank_account_name') }}"/>
                            @if($errors->has('bank_account_name'))
                                <div
                                    style="color: red">{{ $errors->first('bank_account_name') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label"
                                   for="iban">{{ __('common.iban') }}</label>
                            <input type="text" id="iban" name="iban" class="form-control" value="{{ $artist->iban }}"
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
                                    <img src="{{ $artist->image }}" alt="...">
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
                                    <img src="{{ $artist->cover_image }}" alt="...">
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
                        <div class="col-6">
                            <label class="form-label"
                                   for="edit_video_here">{{ __('common.video') }}</label>
                            <br>
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <video style="width: 205px;height: 155px" controls>
                                    <source src="{{ $artist->video }}" id="edit_video_here">
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
                                   for="thumbnail">{{ __('common.thumbnail') }}</label>
                            <br>
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;"
                                     data-trigger="fileinput">
                                    <img src="{{ $artist->thumbnail }}" alt="...">
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail"
                                     style="max-width: 200px; max-height: 150px"></div>
                                <div>
                                        <span class="btn btn-white btn-file">
                                            <span class="fileinput-new">@lang('common.chooseImage')</span>
                                            <span class="fileinput-exists">@lang('common.change')</span>
                                            <input type="file" name="thumbnail">
                                        </span>
                                    <a href="#" class="btn btn-orange fileinput-exists"
                                       data-dismiss="fileinput">@lang('common.remove')</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label"
                                   for="end_date">{{ __('common.artist_images') }}</label>
                            <div class="input-images_2"></div>
                        </div>
                        <div class="col-12 text-center mt-2 pt-50">
                            <button type="submit" class="btn btn-primary me-1">@lang('common.save_changes')</button>
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

        var images = @json($artist->gallery);

        let preloaded = [];
        $.each(images, function (index, value){
            if (value.type == 1){
                preloaded.push({id: value.id, src: value.item});
            }
        });

        $('.input-images_2').imageUploader_2({
            preloaded: preloaded,
            imagesInputName: 'images',
            maxSize: 2 * 1024 * 1024,
            maxFiles: 10
        });

    </script>
@endsection

