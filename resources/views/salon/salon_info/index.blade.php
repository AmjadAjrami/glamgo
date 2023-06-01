@extends('salon.layouts.app')

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
                    <h4 class="card-title">@lang('common.salon_info')</h4>
                </div>
                @include('flash::message')
                <div class="card-body">
                    <form data-reset="true" method="POST" class="row gy-1 pt-75" enctype="multipart/form-data" action="{{ route('salon_info.update') }}">
                        @csrf
                        @method('PUT')

                        @foreach(locales() as $key => $value)
                            <div class="col-12 col-md-6">
                                <label class="form-label"
                                       for="name_{{ $key }}">{{ __('common.name') . ' - ' . __('common.'.$value) }}</label>
                                <input type="text" id="name_{{ $key }}" name="name_{{ $key }}" class="form-control" value="{{ $salon->translate($key)->name }}"
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
                                          placeholder="{{ __('common.bio') . ' - ' . __('common.'.$value) }}">{{ $salon->translate($key)->bio }}</textarea>
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
                                   placeholder="{{ __('common.email') }}" value="{{ $salon->email }}"/>
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
                                   placeholder="{{ __('common.mobile') }}" value="{{ $salon->mobile }}"/>
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
                                    <option value="{{ $category->id }}" {{ in_array($category->id, $salon->salon_categories->pluck('category_id')->toArray()) ? 'selected' : '' }}>{{ $category->name }}</option>
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
                            <input type="text" id="bank_name" name="bank_name" class="form-control" value="{{ $salon->bank_name }}"
                                   placeholder="{{ __('common.bank_name') }}"/>
                            @if($errors->has('bank_name'))
                                <div
                                    style="color: red">{{ $errors->first('bank_name') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label"
                                   for="bank_account_name">{{ __('common.bank_account_name') }}</label>
                            <input type="text" id="bank_account_name" name="bank_account_name" class="form-control" value="{{ $salon->bank_account_name }}"
                                   placeholder="{{ __('common.bank_account_name') }}"/>
                            @if($errors->has('bank_account_name'))
                                <div
                                    style="color: red">{{ $errors->first('bank_account_name') }}</div>
                            @endif
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label"
                                   for="iban">{{ __('common.iban') }}</label>
                            <input type="text" id="iban" name="iban" class="form-control" value="{{ $salon->iban }}"
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
                                    <img src="{{ $salon->image }}" alt="...">
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
                                    <img src="{{ $salon->cover_image }}" alt="...">
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
                                    <source src="{{ $salon->video }}" id="edit_video_here">
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
                                    <img src="{{ $salon->thumbnail }}" alt="...">
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
                                   for="end_date">{{ __('common.salon_images') }}</label>
                            <div class="input-images_2"></div>
                        </div>
                        <div class="col-12">
                            <label class="form-label"
                                   for="address">{{ __('common.address') }}</label>
                            <input id="edit_address" name="address" class="form-control  mb-2" value="{{ $salon->address_text }}">
                            <div class="map" id="edit_map" style="width: 100%; height: 300px"></div>
                            <div class="form-group">
                                <div class="col-md-6">
                                    <input type="hidden" class="form-control" name="lat"
                                           value="{{ $salon->lat }}"
                                           id="edit_lat" placeholder=" {{__('common.lat')}}" required>
                                    @if($errors->has('lat'))
                                        <p class="error">
                                            <small>{{ $errors->first('lat') }}</small>
                                        </p>
                                    @endif
                                    <input type="hidden" class="form-control" name="lng"
                                           value="{{ $salon->lng }}"
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

        var images = @json($salon->gallery);

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

        function initMap(lat, lng) {
            address_map_2();
        }

        function address_map_2() {
            var input = document.getElementById('edit_address');
            var lat = parseFloat(document.getElementById('edit_lat').value);
            var lng = parseFloat(document.getElementById('edit_lng').value);
            var autocomplete = new google.maps.places.Autocomplete(input);
            var geocoder = new google.maps.Geocoder;
            var infowindow = new google.maps.InfoWindow;
            var uluru = {lat: lat || 24.7253981, lng: lng || 46.2620201};
            var map = new google.maps.Map(document.getElementById('edit_map'), {
                zoom: 5,
                center: uluru
            });
            autocomplete.bindTo('bounds', map);

            // Set the data fields to return when the user selects a place.
            autocomplete.setFields(
                ['address_components', 'geometry', 'icon', 'name']);

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
                placeMarker(event.latLng);
                getAddress(event.latLng);
            });

            function placeMarker(location) {
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

                getAddress(location);
            }

            function getAddress(latLng) {
                geocoder.geocode({'latLng': latLng},
                    function (results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            if (results[0]) {
                                console.log(results[0].formatted_address);
                                document.getElementById("edit_address").value = results[0].formatted_address;
                            } else {
                                document.getElementById("edit_address").value = "No results";
                            }
                        } else {
                            document.getElementById("edit_address").value = status;
                        }
                    }
                );
            }
        }
    </script>

    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCa_N3am8UImF1aoqlmC2lISfTxNfDeGmc&libraries=places&callback=initMap"
        async defer></script>
@endsection

