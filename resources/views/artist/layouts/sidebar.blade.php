<!-- BEGIN: Main Menu-->
<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item me-auto">
                <a class="navbar-brand" href="{{ route('artist.dashboard') }}">
                    <img src="{{ asset('logo.png') }}" id="logo" style="width: 130px !important;">
                </a>
            </li>
            <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pe-0" data-bs-toggle="collapse"><i
                        class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i><i
                        class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc"
                        data-ticon="disc"></i></a></li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class=" nav-item {{ strtok(\Illuminate\Support\Facades\Route::currentRouteName(),'.') == 'artist' ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{ route('artist.dashboard') }}"><i
                        data-feather="home"></i><span class="menu-title text-truncate"
                                                      data-i18n="Email">@lang('common.home')</span></a>
            </li>
            <li class=" nav-item {{ strtok(\Illuminate\Support\Facades\Route::currentRouteName(),'.') == 'artist_info' ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{ route('artist_info.index') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                         xmlns:svgjs="http://svgjs.com/svgjs" width="512" height="512" x="0" y="0" viewBox="0 0 512 512"
                         style="enable-background:new 0 0 512 512" xml:space="preserve"><g>
                            <g xmlns="http://www.w3.org/2000/svg">
                                <g>
                                    <path
                                        d="M414.007,148.75c5.522,0,10-4.477,10-10V30c0-16.542-13.458-30-30-30h-364c-16.542,0-30,13.458-30,30v452    c0,16.542,13.458,30,30,30h364c16.542,0,30-13.458,30-30v-73.672c0-5.523-4.478-10-10-10c-5.522,0-10,4.477-10,10V482    c0,5.514-4.486,10-10,10h-364c-5.514,0-10-4.486-10-10V30c0-5.514,4.486-10,10-10h364c5.514,0,10,4.486,10,10v108.75    C404.007,144.273,408.485,148.75,414.007,148.75z"
                                        fill="{{ strtok(\Illuminate\Support\Facades\Route::currentRouteName(),'.') == 'artist_info' ? '#ffffff' : '#000000' }}"
                                        data-original="#000000"></path>
                                </g>
                            </g>
                            <g xmlns="http://www.w3.org/2000/svg">
                                <g>
                                    <path
                                        d="M212.007,54c-50.729,0-92,41.271-92,92c0,26.317,11.11,50.085,28.882,66.869c0.333,0.356,0.687,0.693,1.074,1    c16.371,14.979,38.158,24.13,62.043,24.13c23.885,0,45.672-9.152,62.043-24.13c0.387-0.307,0.741-0.645,1.074-1    c17.774-16.784,28.884-40.552,28.884-66.869C304.007,95.271,262.736,54,212.007,54z M212.007,218    c-16.329,0-31.399-5.472-43.491-14.668c8.789-15.585,25.19-25.332,43.491-25.332c18.301,0,34.702,9.747,43.491,25.332    C243.405,212.528,228.336,218,212.007,218z M196.007,142v-6.5c0-8.822,7.178-16,16-16s16,7.178,16,16v6.5c0,8.822-7.178,16-16,16    S196.007,150.822,196.007,142z M269.947,188.683c-7.375-10.938-17.596-19.445-29.463-24.697c4.71-6.087,7.523-13.712,7.523-21.986    v-6.5c0-19.851-16.149-36-36-36s-36,16.149-36,36v6.5c0,8.274,2.813,15.899,7.523,21.986    c-11.867,5.252-22.088,13.759-29.463,24.697c-8.829-11.953-14.06-26.716-14.06-42.683c0-39.701,32.299-72,72-72s72,32.299,72,72    C284.007,161.967,278.776,176.73,269.947,188.683z"
                                        fill="{{ strtok(\Illuminate\Support\Facades\Route::currentRouteName(),'.') == 'artist_info' ? '#ffffff' : '#000000' }}"
                                        data-original="#000000"></path>
                                </g>
                            </g>
                            <g xmlns="http://www.w3.org/2000/svg">
                                <g>
                                    <path
                                        d="M266.007,438h-54c-5.522,0-10,4.477-10,10s4.478,10,10,10h54c5.522,0,10-4.477,10-10S271.529,438,266.007,438z"
                                        fill="{{ strtok(\Illuminate\Support\Facades\Route::currentRouteName(),'.') == 'artist_info' ? '#ffffff' : '#000000' }}"
                                        data-original="#000000"></path>
                                </g>
                            </g>
                            <g xmlns="http://www.w3.org/2000/svg">
                                <g>
                                    <path
                                        d="M266.007,382h-142c-5.522,0-10,4.477-10,10s4.478,10,10,10h142c5.522,0,10-4.477,10-10S271.529,382,266.007,382z"
                                        fill="{{ strtok(\Illuminate\Support\Facades\Route::currentRouteName(),'.') == 'artist_info' ? '#ffffff' : '#000000' }}"
                                        data-original="#000000"></path>
                                </g>
                            </g>
                            <g xmlns="http://www.w3.org/2000/svg">
                                <g>
                                    <path
                                        d="M266.007,326h-142c-5.522,0-10,4.477-10,10s4.478,10,10,10h142c5.522,0,10-4.477,10-10S271.529,326,266.007,326z"
                                        fill="{{ strtok(\Illuminate\Support\Facades\Route::currentRouteName(),'.') == 'artist_info' ? '#ffffff' : '#000000' }}"
                                        data-original="#000000"></path>
                                </g>
                            </g>
                            <g xmlns="http://www.w3.org/2000/svg">
                                <g>
                                    <path
                                        d="M88.366,272.93c-1.859-1.86-4.439-2.93-7.079-2.93c-2.631,0-5.211,1.07-7.07,2.93c-1.86,1.86-2.93,4.44-2.93,7.07    s1.069,5.21,2.93,7.07c1.87,1.86,4.439,2.93,7.07,2.93c2.64,0,5.21-1.07,7.079-2.93c1.86-1.86,2.931-4.44,2.931-7.07    S90.227,274.79,88.366,272.93z"
                                        fill="{{ strtok(\Illuminate\Support\Facades\Route::currentRouteName(),'.') == 'artist_info' ? '#ffffff' : '#000000' }}"
                                        data-original="#000000"></path>
                                </g>
                            </g>
                            <g xmlns="http://www.w3.org/2000/svg">
                                <g>
                                    <path
                                        d="M88.366,328.93c-1.869-1.86-4.439-2.93-7.079-2.93c-2.631,0-5.2,1.07-7.07,2.93c-1.86,1.86-2.93,4.44-2.93,7.07    s1.069,5.21,2.93,7.07c1.87,1.86,4.439,2.93,7.07,2.93c2.64,0,5.21-1.07,7.079-2.93c1.86-1.86,2.931-4.44,2.931-7.07    S90.227,330.79,88.366,328.93z"
                                        fill="{{ strtok(\Illuminate\Support\Facades\Route::currentRouteName(),'.') == 'artist_info' ? '#ffffff' : '#000000' }}"
                                        data-original="#000000"></path>
                                </g>
                            </g>
                            <g xmlns="http://www.w3.org/2000/svg">
                                <g>
                                    <path
                                        d="M88.366,384.93c-1.869-1.86-4.439-2.93-7.079-2.93c-2.631,0-5.2,1.07-7.07,2.93c-1.86,1.86-2.93,4.44-2.93,7.07    s1.069,5.21,2.93,7.07c1.859,1.86,4.439,2.93,7.07,2.93c2.64,0,5.22-1.07,7.079-2.93c1.86-1.86,2.931-4.44,2.931-7.07    S90.227,386.79,88.366,384.93z"
                                        fill="{{ strtok(\Illuminate\Support\Facades\Route::currentRouteName(),'.') == 'artist_info' ? '#ffffff' : '#000000' }}"
                                        data-original="#000000"></path>
                                </g>
                            </g>
                            <g xmlns="http://www.w3.org/2000/svg">
                                <g>
                                    <path
                                        d="M266.007,270h-142c-5.522,0-10,4.477-10,10s4.478,10,10,10h142c5.522,0,10-4.477,10-10S271.529,270,266.007,270z"
                                        fill="{{ strtok(\Illuminate\Support\Facades\Route::currentRouteName(),'.') == 'artist_info' ? '#ffffff' : '#000000' }}"
                                        data-original="#000000"></path>
                                </g>
                            </g>
                            <g xmlns="http://www.w3.org/2000/svg">
                                <g>
                                    <path
                                        d="M491.002,130.32c-9.715-5.609-21.033-7.099-31.871-4.196c-10.836,2.904-19.894,9.854-25.502,19.569L307.787,363.656    c-0.689,1.195-1.125,2.52-1.278,3.891l-8.858,79.344c-0.44,3.948,1.498,7.783,4.938,9.77c1.553,0.896,3.278,1.34,4.999,1.34    c2.092,0,4.176-0.655,5.931-1.948l64.284-47.344c1.111-0.818,2.041-1.857,2.73-3.052l125.841-217.963    C517.954,167.638,511.058,141.9,491.002,130.32z M320.063,426.394l4.626-41.432l28.942,16.71L320.063,426.394z M368.213,386.996    l-38.105-22l100.985-174.91l38.105,22L368.213,386.996z M489.054,177.693l-9.857,17.073l-38.105-22l9.857-17.073    c2.938-5.089,7.682-8.729,13.358-10.25c5.678-1.522,11.606-0.74,16.694,2.198c5.089,2.938,8.729,7.682,10.25,13.358    C492.772,166.675,491.992,172.604,489.054,177.693z"
                                        fill="{{ strtok(\Illuminate\Support\Facades\Route::currentRouteName(),'.') == 'artist_info' ? '#ffffff' : '#000000' }}"
                                        data-original="#000000"></path>
                                </g>
                            </g>
                            <g xmlns="http://www.w3.org/2000/svg">
                            </g>
                            <g xmlns="http://www.w3.org/2000/svg">
                            </g>
                            <g xmlns="http://www.w3.org/2000/svg">
                            </g>
                            <g xmlns="http://www.w3.org/2000/svg">
                            </g>
                            <g xmlns="http://www.w3.org/2000/svg">
                            </g>
                            <g xmlns="http://www.w3.org/2000/svg">
                            </g>
                            <g xmlns="http://www.w3.org/2000/svg">
                            </g>
                            <g xmlns="http://www.w3.org/2000/svg">
                            </g>
                            <g xmlns="http://www.w3.org/2000/svg">
                            </g>
                            <g xmlns="http://www.w3.org/2000/svg">
                            </g>
                            <g xmlns="http://www.w3.org/2000/svg">
                            </g>
                            <g xmlns="http://www.w3.org/2000/svg">
                            </g>
                            <g xmlns="http://www.w3.org/2000/svg">
                            </g>
                            <g xmlns="http://www.w3.org/2000/svg">
                            </g>
                            <g xmlns="http://www.w3.org/2000/svg">
                            </g>
                        </g></svg>
                    <span class="menu-title text-truncate" data-i18n="Email">@lang('common.artist_info')</span>
                </a>
            </li>
            <li class=" nav-item {{ strtok(\Illuminate\Support\Facades\Route::currentRouteName(),'.') == 'artist_service_types' ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{ route('artist_service_types.index') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                         xmlns:svgjs="http://svgjs.com/svgjs" width="512" height="512" x="0" y="0"
                         viewBox="0 0 64 64"
                         style="{{ strtok(\Illuminate\Support\Facades\Route::currentRouteName(),'.') == 'artist_service_types' ? 'fill: rgb(231 231 231) !important;' : 'fill: rgb(98 95 110) !important' }}"
                         xml:space="preserve" class=""><g>
                            <g xmlns="http://www.w3.org/2000/svg">
                                <g>
                                    <path
                                        d="m55.3 62h-13.6c-3.7 0-6.7-3-6.7-6.7v-18.3c0-1.1.9-2 2-2h18.3c3.7 0 6.7 3 6.7 6.7v13.6c0 3.7-3 6.7-6.7 6.7zm-16.3-23v16.3c0 1.5 1.2 2.7 2.7 2.7h13.6c1.5 0 2.7-1.2 2.7-2.7v-13.6c0-1.5-1.2-2.7-2.7-2.7z"
                                        fill="{{ strtok(\Illuminate\Support\Facades\Route::currentRouteName(),'.') == 'artist_service_types' ? '#ffffff' : '#000000' }}"
                                        data-original="#000000" class=""></path>
                                </g>
                                <g>
                                    <path
                                        d="m22.3 62h-13.6c-3.7 0-6.7-3-6.7-6.7v-13.6c0-3.7 3-6.7 6.7-6.7h18.3c1.1 0 2 .9 2 2v18.3c0 3.7-3 6.7-6.7 6.7zm-13.6-23c-1.5 0-2.7 1.2-2.7 2.7v13.6c0 1.5 1.2 2.7 2.7 2.7h13.6c1.5 0 2.7-1.2 2.7-2.7v-16.3z"
                                        fill="{{ strtok(\Illuminate\Support\Facades\Route::currentRouteName(),'.') == 'artist_service_types' ? '#ffffff' : '#000000' }}"
                                        data-original="#000000" class=""></path>
                                </g>
                                <g>
                                    <path
                                        d="m55.3 29h-18.3c-1.1 0-2-.9-2-2v-18.3c0-3.7 3-6.7 6.7-6.7h13.6c3.7 0 6.7 3 6.7 6.7v13.6c0 3.7-3 6.7-6.7 6.7zm-16.3-4h16.3c1.5 0 2.7-1.2 2.7-2.7v-13.6c0-1.5-1.2-2.7-2.7-2.7h-13.6c-1.5 0-2.7 1.2-2.7 2.7z"
                                        fill="{{ strtok(\Illuminate\Support\Facades\Route::currentRouteName(),'.') == 'artist_service_types' ? '#ffffff' : '#000000' }}"
                                        data-original="#000000" class=""></path>
                                </g>
                                <g>
                                    <path
                                        d="m27 29h-18.3c-3.7 0-6.7-3-6.7-6.7v-13.6c0-3.7 3-6.7 6.7-6.7h13.6c3.7 0 6.7 3 6.7 6.7v18.3c0 1.1-.9 2-2 2zm-18.3-23c-1.5 0-2.7 1.2-2.7 2.7v13.6c0 1.5 1.2 2.7 2.7 2.7h16.3v-16.3c0-1.5-1.2-2.7-2.7-2.7z"
                                        fill="{{ strtok(\Illuminate\Support\Facades\Route::currentRouteName(),'.') == 'artist_service_types' ? '#ffffff' : '#000000' }}"
                                        data-original="#000000" class=""></path>
                                </g>
                            </g>
                        </g></svg>
                    <span class="menu-title text-truncate" data-i18n="Email">@lang('common.service_types')</span>
                </a>
            </li>
            <li class=" nav-item {{ strtok(\Illuminate\Support\Facades\Route::currentRouteName(),'.') == 'artist_services' ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{ route('artist_services.index') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                         xmlns:svgjs="http://svgjs.com/svgjs" width="512" height="512" x="0" y="0"
                         viewBox="0 0 64 64"
                         style="{{ strtok(\Illuminate\Support\Facades\Route::currentRouteName(),'.') == 'artist_service_types' ? 'fill: rgb(231 231 231) !important;' : 'fill: rgb(98 95 110) !important' }}"
                         xml:space="preserve" class=""><g>
                            <g xmlns="http://www.w3.org/2000/svg">
                                <g>
                                    <path
                                        d="m55.3 62h-13.6c-3.7 0-6.7-3-6.7-6.7v-18.3c0-1.1.9-2 2-2h18.3c3.7 0 6.7 3 6.7 6.7v13.6c0 3.7-3 6.7-6.7 6.7zm-16.3-23v16.3c0 1.5 1.2 2.7 2.7 2.7h13.6c1.5 0 2.7-1.2 2.7-2.7v-13.6c0-1.5-1.2-2.7-2.7-2.7z"
                                        fill="{{ strtok(\Illuminate\Support\Facades\Route::currentRouteName(),'.') == 'artist_services' ? '#ffffff' : '#000000' }}"
                                        data-original="#000000" class=""></path>
                                </g>
                                <g>
                                    <path
                                        d="m22.3 62h-13.6c-3.7 0-6.7-3-6.7-6.7v-13.6c0-3.7 3-6.7 6.7-6.7h18.3c1.1 0 2 .9 2 2v18.3c0 3.7-3 6.7-6.7 6.7zm-13.6-23c-1.5 0-2.7 1.2-2.7 2.7v13.6c0 1.5 1.2 2.7 2.7 2.7h13.6c1.5 0 2.7-1.2 2.7-2.7v-16.3z"
                                        fill="{{ strtok(\Illuminate\Support\Facades\Route::currentRouteName(),'.') == 'artist_services' ? '#ffffff' : '#000000' }}"
                                        data-original="#000000" class=""></path>
                                </g>
                                <g>
                                    <path
                                        d="m55.3 29h-18.3c-1.1 0-2-.9-2-2v-18.3c0-3.7 3-6.7 6.7-6.7h13.6c3.7 0 6.7 3 6.7 6.7v13.6c0 3.7-3 6.7-6.7 6.7zm-16.3-4h16.3c1.5 0 2.7-1.2 2.7-2.7v-13.6c0-1.5-1.2-2.7-2.7-2.7h-13.6c-1.5 0-2.7 1.2-2.7 2.7z"
                                        fill="{{ strtok(\Illuminate\Support\Facades\Route::currentRouteName(),'.') == 'artist_services' ? '#ffffff' : '#000000' }}"
                                        data-original="#000000" class=""></path>
                                </g>
                                <g>
                                    <path
                                        d="m27 29h-18.3c-3.7 0-6.7-3-6.7-6.7v-13.6c0-3.7 3-6.7 6.7-6.7h13.6c3.7 0 6.7 3 6.7 6.7v18.3c0 1.1-.9 2-2 2zm-18.3-23c-1.5 0-2.7 1.2-2.7 2.7v13.6c0 1.5 1.2 2.7 2.7 2.7h16.3v-16.3c0-1.5-1.2-2.7-2.7-2.7z"
                                        fill="{{ strtok(\Illuminate\Support\Facades\Route::currentRouteName(),'.') == 'artist_services' ? '#ffffff' : '#000000' }}"
                                        data-original="#000000" class=""></path>
                                </g>
                            </g>
                        </g></svg>
                    <span class="menu-title text-truncate" data-i18n="Email">@lang('common.services')</span>
                </a>
            </li>
            <li class="nav-item {{ request()->type == 1 ? 'open' : '' }}">
                <a class="d-flex align-items-center" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                         width="512" height="512" x="0" y="0"
                         viewBox="0 0 511.992 511.992" style="enable-background:new 0 0 512 512" xml:space="preserve"
                         class=""><g>
                            <g id="XMLID_806_">
                                <g id="XMLID_386_">
                                    <path id="XMLID_389_"
                                          d="M511.005,279.646c-4.597-46.238-25.254-89.829-58.168-122.744    c-28.128-28.127-62.556-46.202-98.782-54.239V77.255c14.796-3.681,25.794-17.074,25.794-32.993c0-18.748-15.252-34-34-34h-72    c-18.748,0-34,15.252-34,34c0,15.918,10.998,29.311,25.793,32.993v25.479c-36.115,8.071-70.429,26.121-98.477,54.169    c-6.138,6.138-11.798,12.577-16.979,19.269c-0.251-0.019-0.502-0.038-0.758-0.038H78.167c-5.522,0-10,4.477-10,10s4.478,10,10,10    h58.412c-7.332,12.275-13.244,25.166-17.744,38.436H10c-5.522,0-10,4.477-10,10s4.478,10,10,10h103.184    c-2.882,12.651-4.536,25.526-4.963,38.437H64c-5.522,0-10,4.477-10,10s4.478,10,10,10h44.54    c0.844,12.944,2.925,25.82,6.244,38.437H50c-5.522,0-10,4.477-10,10s4.478,10,10,10h71.166    c9.81,25.951,25.141,50.274,45.999,71.132c32.946,32.946,76.582,53.608,122.868,58.181c6.606,0.652,13.217,0.975,19.819,0.975    c39.022,0,77.548-11.293,110.238-32.581c4.628-3.014,5.937-9.209,2.923-13.837s-9.209-5.937-13.837-2.923    c-71.557,46.597-167.39,36.522-227.869-23.957c-70.962-70.962-70.962-186.425,0-257.388c70.961-70.961,186.424-70.961,257.387,0    c60.399,60.4,70.529,156.151,24.086,227.673c-3.008,4.632-1.691,10.826,2.94,13.833c4.634,3.008,10.826,1.691,13.833-2.941    C504.367,371.396,515.537,325.241,511.005,279.646z M259.849,44.263c0-7.72,6.28-14,14-14h72c7.72,0,14,6.28,14,14s-6.28,14-14,14    h-1.794h-68.413h-1.793C266.129,58.263,259.849,51.982,259.849,44.263z M285.642,99.296V78.263h48.413v20.997    C317.979,97.348,301.715,97.36,285.642,99.296z"
                                          fill="#000000"
                                          data-original="#000000" class=""></path>
                                    <path id="XMLID_391_"
                                          d="M445.77,425.5c-2.64,0-5.21,1.07-7.069,2.93c-1.87,1.86-2.931,4.44-2.931,7.07    c0,2.63,1.061,5.21,2.931,7.07c1.859,1.87,4.43,2.93,7.069,2.93c2.63,0,5.2-1.06,7.07-2.93c1.86-1.86,2.93-4.44,2.93-7.07    c0-2.63-1.069-5.21-2.93-7.07C450.97,426.57,448.399,425.5,445.77,425.5z"
                                          fill="#000000"
                                          data-original="#000000" class=""></path>
                                    <path id="XMLID_394_"
                                          d="M310.001,144.609c-85.538,0-155.129,69.59-155.129,155.129s69.591,155.129,155.129,155.129    s155.129-69.59,155.129-155.129S395.539,144.609,310.001,144.609z M310.001,434.867c-74.511,0-135.129-60.619-135.129-135.129    s60.618-135.129,135.129-135.129S445.13,225.228,445.13,299.738S384.512,434.867,310.001,434.867z"
                                          fill="#000000"
                                          data-original="#000000" class=""></path>
                                    <path id="XMLID_397_"
                                          d="M373.257,222.34l-49.53,49.529c-4.142-2.048-8.801-3.205-13.726-3.205c-4.926,0-9.584,1.157-13.726,3.205    l-22.167-22.167c-3.906-3.905-10.236-3.905-14.143,0c-3.905,3.905-3.905,10.237,0,14.142l22.167,22.167    c-2.049,4.142-3.205,8.801-3.205,13.726c0,17.134,13.939,31.074,31.074,31.074s31.074-13.94,31.074-31.074    c0-4.925-1.157-9.584-3.205-13.726l48.076-48.076v0l1.453-1.453c3.905-3.905,3.905-10.237,0-14.142    S377.164,218.435,373.257,222.34z M310.001,310.812c-6.106,0-11.074-4.968-11.074-11.074s4.968-11.074,11.074-11.074    s11.074,4.968,11.074,11.074S316.107,310.812,310.001,310.812z"
                                          fill="#000000"
                                          data-original="#000000" class=""></path>
                                    <path id="XMLID_398_"
                                          d="M416.92,289.86h-9.265c-5.522,0-10,4.477-10,10s4.478,10,10,10h9.265c5.522,0,10-4.477,10-10    S422.442,289.86,416.92,289.86z"
                                          fill="#000000"
                                          data-original="#000000" class=""></path>
                                    <path id="XMLID_399_"
                                          d="M212.346,289.616h-9.264c-5.522,0-10,4.477-10,10s4.478,10,10,10h9.264c5.522,0,10-4.477,10-10    S217.868,289.616,212.346,289.616z"
                                          fill="#000000"
                                          data-original="#000000" class=""></path>
                                    <path id="XMLID_400_"
                                          d="M310.123,212.083c5.522,0,10-4.477,10-10v-9.264c0-5.523-4.478-10-10-10s-10,4.477-10,10v9.264    C300.123,207.606,304.601,212.083,310.123,212.083z"
                                          fill="#000000"
                                          data-original="#000000" class=""></path>
                                    <path id="XMLID_424_"
                                          d="M309.879,387.393c-5.522,0-10,4.477-10,10v9.264c0,5.523,4.478,10,10,10s10-4.477,10-10v-9.264    C319.879,391.87,315.401,387.393,309.879,387.393z"
                                          fill="#000000"
                                          data-original="#000000" class=""></path>
                                    <path id="XMLID_425_"
                                          d="M10,351.44c-2.63,0-5.21,1.07-7.07,2.93c-1.86,1.86-2.93,4.44-2.93,7.07c0,2.64,1.069,5.21,2.93,7.07    s4.44,2.93,7.07,2.93s5.21-1.07,7.069-2.93c1.86-1.86,2.931-4.44,2.931-7.07s-1.07-5.21-2.931-7.07    C15.21,352.51,12.63,351.44,10,351.44z"
                                          fill="#000000"
                                          data-original="#000000" class=""></path>
                                </g>
                            </g>
                        </g></svg>
                    <span class="menu-title text-truncate" data-i18n="Invoice">@lang('common.booking_times')</span></a>
                <ul class="menu-content">
                    <li class="nav-item {{ request()->type == 1 && strtok(\Illuminate\Support\Facades\Route::currentRouteName(),'.') == 'artist_booking_times' ? 'active' : '' }}">
                        <a class="d-flex align-items-center"
                           href="{{ url(app()->getLocale() . '/artist/booking_times?type=1') }}"><i
                                data-feather="circle"></i><span class="menu-item text-truncate"
                                                                data-i18n="List">@lang('common.booking_times_in_salon')</span></a>
                    </li>
                    <li class="nav-item {{ request()->type == 2 && strtok(\Illuminate\Support\Facades\Route::currentRouteName(),'.') == 'artist_booking_times' ? 'active' : '' }}">
                        <a class="d-flex align-items-center"
                           href="{{ url(app()->getLocale() . '/artist/booking_times?type=2') }}"><i
                                data-feather="circle"></i><span class="menu-item text-truncate"
                                                                data-i18n="List">@lang('common.booking_times_out_salon')</span></a>
                    </li>
                </ul>
            </li>
{{--            <li class=" nav-item {{ strtok(\Illuminate\Support\Facades\Route::currentRouteName(),'.') == 'artist_booking_times' ? 'active' : '' }}">--}}
{{--                <a class="d-flex align-items-center" href="{{ route('artist_booking_times.index') }}">--}}
{{--                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"--}}
{{--                         xmlns:svgjs="http://svgjs.com/svgjs" width="512" height="512" x="0" y="0"--}}
{{--                         viewBox="0 0 511.992 511.992" style="enable-background:new 0 0 512 512" xml:space="preserve"--}}
{{--                         class=""><g>--}}
{{--                            <g id="XMLID_806_">--}}
{{--                                <g id="XMLID_386_">--}}
{{--                                    <path id="XMLID_389_"--}}
{{--                                          d="M511.005,279.646c-4.597-46.238-25.254-89.829-58.168-122.744    c-28.128-28.127-62.556-46.202-98.782-54.239V77.255c14.796-3.681,25.794-17.074,25.794-32.993c0-18.748-15.252-34-34-34h-72    c-18.748,0-34,15.252-34,34c0,15.918,10.998,29.311,25.793,32.993v25.479c-36.115,8.071-70.429,26.121-98.477,54.169    c-6.138,6.138-11.798,12.577-16.979,19.269c-0.251-0.019-0.502-0.038-0.758-0.038H78.167c-5.522,0-10,4.477-10,10s4.478,10,10,10    h58.412c-7.332,12.275-13.244,25.166-17.744,38.436H10c-5.522,0-10,4.477-10,10s4.478,10,10,10h103.184    c-2.882,12.651-4.536,25.526-4.963,38.437H64c-5.522,0-10,4.477-10,10s4.478,10,10,10h44.54    c0.844,12.944,2.925,25.82,6.244,38.437H50c-5.522,0-10,4.477-10,10s4.478,10,10,10h71.166    c9.81,25.951,25.141,50.274,45.999,71.132c32.946,32.946,76.582,53.608,122.868,58.181c6.606,0.652,13.217,0.975,19.819,0.975    c39.022,0,77.548-11.293,110.238-32.581c4.628-3.014,5.937-9.209,2.923-13.837s-9.209-5.937-13.837-2.923    c-71.557,46.597-167.39,36.522-227.869-23.957c-70.962-70.962-70.962-186.425,0-257.388c70.961-70.961,186.424-70.961,257.387,0    c60.399,60.4,70.529,156.151,24.086,227.673c-3.008,4.632-1.691,10.826,2.94,13.833c4.634,3.008,10.826,1.691,13.833-2.941    C504.367,371.396,515.537,325.241,511.005,279.646z M259.849,44.263c0-7.72,6.28-14,14-14h72c7.72,0,14,6.28,14,14s-6.28,14-14,14    h-1.794h-68.413h-1.793C266.129,58.263,259.849,51.982,259.849,44.263z M285.642,99.296V78.263h48.413v20.997    C317.979,97.348,301.715,97.36,285.642,99.296z"--}}
{{--                                          fill="{{ strtok(\Illuminate\Support\Facades\Route::currentRouteName(),'.') == 'artist_booking_times' ? '#ffffff' : '#000000' }}" data-original="#000000" class=""></path>--}}
{{--                                    <path id="XMLID_391_"--}}
{{--                                          d="M445.77,425.5c-2.64,0-5.21,1.07-7.069,2.93c-1.87,1.86-2.931,4.44-2.931,7.07    c0,2.63,1.061,5.21,2.931,7.07c1.859,1.87,4.43,2.93,7.069,2.93c2.63,0,5.2-1.06,7.07-2.93c1.86-1.86,2.93-4.44,2.93-7.07    c0-2.63-1.069-5.21-2.93-7.07C450.97,426.57,448.399,425.5,445.77,425.5z"--}}
{{--                                          fill="{{ strtok(\Illuminate\Support\Facades\Route::currentRouteName(),'.') == 'artist_booking_times' ? '#ffffff' : '#000000' }}" data-original="#000000" class=""></path>--}}
{{--                                    <path id="XMLID_394_"--}}
{{--                                          d="M310.001,144.609c-85.538,0-155.129,69.59-155.129,155.129s69.591,155.129,155.129,155.129    s155.129-69.59,155.129-155.129S395.539,144.609,310.001,144.609z M310.001,434.867c-74.511,0-135.129-60.619-135.129-135.129    s60.618-135.129,135.129-135.129S445.13,225.228,445.13,299.738S384.512,434.867,310.001,434.867z"--}}
{{--                                          fill="{{ strtok(\Illuminate\Support\Facades\Route::currentRouteName(),'.') == 'artist_booking_times' ? '#ffffff' : '#000000' }}" data-original="#000000" class=""></path>--}}
{{--                                    <path id="XMLID_397_"--}}
{{--                                          d="M373.257,222.34l-49.53,49.529c-4.142-2.048-8.801-3.205-13.726-3.205c-4.926,0-9.584,1.157-13.726,3.205    l-22.167-22.167c-3.906-3.905-10.236-3.905-14.143,0c-3.905,3.905-3.905,10.237,0,14.142l22.167,22.167    c-2.049,4.142-3.205,8.801-3.205,13.726c0,17.134,13.939,31.074,31.074,31.074s31.074-13.94,31.074-31.074    c0-4.925-1.157-9.584-3.205-13.726l48.076-48.076v0l1.453-1.453c3.905-3.905,3.905-10.237,0-14.142    S377.164,218.435,373.257,222.34z M310.001,310.812c-6.106,0-11.074-4.968-11.074-11.074s4.968-11.074,11.074-11.074    s11.074,4.968,11.074,11.074S316.107,310.812,310.001,310.812z"--}}
{{--                                          fill="{{ strtok(\Illuminate\Support\Facades\Route::currentRouteName(),'.') == 'artist_booking_times' ? '#ffffff' : '#000000' }}" data-original="#000000" class=""></path>--}}
{{--                                    <path id="XMLID_398_"--}}
{{--                                          d="M416.92,289.86h-9.265c-5.522,0-10,4.477-10,10s4.478,10,10,10h9.265c5.522,0,10-4.477,10-10    S422.442,289.86,416.92,289.86z"--}}
{{--                                          fill="{{ strtok(\Illuminate\Support\Facades\Route::currentRouteName(),'.') == 'artist_booking_times' ? '#ffffff' : '#000000' }}" data-original="#000000" class=""></path>--}}
{{--                                    <path id="XMLID_399_"--}}
{{--                                          d="M212.346,289.616h-9.264c-5.522,0-10,4.477-10,10s4.478,10,10,10h9.264c5.522,0,10-4.477,10-10    S217.868,289.616,212.346,289.616z"--}}
{{--                                          fill="{{ strtok(\Illuminate\Support\Facades\Route::currentRouteName(),'.') == 'artist_booking_times' ? '#ffffff' : '#000000' }}" data-original="#000000" class=""></path>--}}
{{--                                    <path id="XMLID_400_"--}}
{{--                                          d="M310.123,212.083c5.522,0,10-4.477,10-10v-9.264c0-5.523-4.478-10-10-10s-10,4.477-10,10v9.264    C300.123,207.606,304.601,212.083,310.123,212.083z"--}}
{{--                                          fill="{{ strtok(\Illuminate\Support\Facades\Route::currentRouteName(),'.') == 'artist_booking_times' ? '#ffffff' : '#000000' }}" data-original="#000000" class=""></path>--}}
{{--                                    <path id="XMLID_424_"--}}
{{--                                          d="M309.879,387.393c-5.522,0-10,4.477-10,10v9.264c0,5.523,4.478,10,10,10s10-4.477,10-10v-9.264    C319.879,391.87,315.401,387.393,309.879,387.393z"--}}
{{--                                          fill="{{ strtok(\Illuminate\Support\Facades\Route::currentRouteName(),'.') == 'artist_booking_times' ? '#ffffff' : '#000000' }}" data-original="#000000" class=""></path>--}}
{{--                                    <path id="XMLID_425_"--}}
{{--                                          d="M10,351.44c-2.63,0-5.21,1.07-7.07,2.93c-1.86,1.86-2.93,4.44-2.93,7.07c0,2.64,1.069,5.21,2.93,7.07    s4.44,2.93,7.07,2.93s5.21-1.07,7.069-2.93c1.86-1.86,2.931-4.44,2.931-7.07s-1.07-5.21-2.931-7.07    C15.21,352.51,12.63,351.44,10,351.44z"--}}
{{--                                          fill="{{ strtok(\Illuminate\Support\Facades\Route::currentRouteName(),'.') == 'artist_booking_times' ? '#ffffff' : '#000000' }}" data-original="#000000" class=""></path>--}}
{{--                                </g>--}}
{{--                            </g>--}}
{{--                        </g></svg>--}}
{{--                    <span class="menu-title text-truncate" data-i18n="Email">@lang('common.booking_times')</span>--}}
{{--                </a>--}}
{{--            </li>--}}
            <li class=" nav-item {{ strtok(\Illuminate\Support\Facades\Route::currentRouteName(),'.') == 'artist_promo_codes' ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{ route('artist_promo_codes.index') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="512" height="512" x="0" y="0"
                         viewBox="0 0 24 24" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g>
                            <path
                                d="m15.846 23.999c-.384 0-.769-.081-1.124-.243l-2.012-.915c-.45-.204-.968-.205-1.419 0l-2.012.914c-.684.31-1.471.323-2.163.037-.692-.287-1.24-.854-1.504-1.555l-.776-2.07c-.174-.464-.54-.83-1.003-1.003l-2.07-.776c-.702-.264-1.268-.812-1.555-1.504s-.273-1.48.037-2.163l.915-2.012c.204-.451.204-.968 0-1.419l-.916-2.012c-.31-.682-.324-1.471-.037-2.163s.854-1.24 1.555-1.504l2.07-.776c.464-.174.83-.54 1.003-1.003l.776-2.07c.264-.702.812-1.268 1.504-1.555.693-.287 1.48-.273 2.163.037l2.012.915c.45.204.968.205 1.419 0l2.012-.914c.682-.311 1.471-.324 2.163-.037s1.24.854 1.504 1.555l.776 2.07c.174.464.54.83 1.003 1.003l2.07.776c.702.264 1.268.812 1.555 1.504s.273 1.48-.037 2.163l-.915 2.012c-.204.451-.204.968 0 1.419l.914 2.012c.31.682.324 1.471.037 2.163s-.854 1.24-1.555 1.504l-2.07.776c-.464.174-.83.54-1.003 1.003l-.776 2.07c-.264.702-.812 1.268-1.504 1.555-.33.137-.684.206-1.037.206zm-3.846-2.311c.383 0 .766.081 1.123.243l2.013.915c.438.2.923.208 1.366.023.444-.184.781-.533.95-.982l.776-2.07c.275-.734.854-1.313 1.588-1.588l2.07-.776c.45-.168.799-.506.982-.95.184-.444.176-.929-.023-1.366l-.915-2.012c-.324-.714-.324-1.533 0-2.247l.915-2.013c.199-.437.207-.922.023-1.366s-.533-.781-.982-.95l-2.07-.776c-.734-.275-1.313-.854-1.588-1.588l-.776-2.07c-.168-.45-.506-.799-.95-.982-.444-.185-.928-.176-1.366.023l-2.012.915c-.714.323-1.532.323-2.246 0l-2.014-.917c-.438-.199-.922-.207-1.366-.023s-.781.533-.95.982l-.776 2.07c-.275.734-.855 1.314-1.588 1.589l-2.07.776c-.45.168-.799.506-.982.95-.185.444-.176.929.022 1.366l.915 2.012c.324.714.324 1.533 0 2.247l-.915 2.013c-.199.437-.207.922-.023 1.366s.533.781.982.95l2.07.776c.734.275 1.313.854 1.588 1.588l.776 2.07c.168.45.506.799.95.982.444.184.928.176 1.366-.023l2.012-.915c.359-.161.742-.242 1.125-.242zm.917-20.074h.01z"
                                fill="{{ strtok(\Illuminate\Support\Facades\Route::currentRouteName(),'.') == 'artist_promo_codes' ? '#ffffff' : '#000000' }}" data-original="#000000" class=""></path>
                            <path
                                d="m8.5 10c-1.378 0-2.5-1.122-2.5-2.5s1.122-2.5 2.5-2.5 2.5 1.122 2.5 2.5-1.122 2.5-2.5 2.5zm0-4c-.827 0-1.5.673-1.5 1.5s.673 1.5 1.5 1.5 1.5-.673 1.5-1.5-.673-1.5-1.5-1.5z"
                                fill="{{ strtok(\Illuminate\Support\Facades\Route::currentRouteName(),'.') == 'artist_promo_codes' ? '#ffffff' : '#000000' }}" data-original="#000000" class=""></path>
                            <path
                                d="m15.5 19c-1.378 0-2.5-1.122-2.5-2.5s1.122-2.5 2.5-2.5 2.5 1.122 2.5 2.5-1.122 2.5-2.5 2.5zm0-4c-.827 0-1.5.673-1.5 1.5s.673 1.5 1.5 1.5 1.5-.673 1.5-1.5-.673-1.5-1.5-1.5z"
                                fill="{{ strtok(\Illuminate\Support\Facades\Route::currentRouteName(),'.') == 'artist_promo_codes' ? '#ffffff' : '#000000' }}" data-original="#000000" class=""></path>
                            <path
                                d="m8.5 18c-.092 0-.185-.025-.268-.078-.233-.148-.302-.458-.153-.69l7-11c.149-.232.457-.301.69-.153s.302.458.153.69l-7 11c-.095.149-.257.231-.422.231z"
                                fill="{{ strtok(\Illuminate\Support\Facades\Route::currentRouteName(),'.') == 'artist_promo_codes' ? '#ffffff' : '#000000' }}" data-original="#000000" class=""></path>
                        </g></svg>
                    <span class="menu-title text-truncate" data-i18n="Email">@lang('common.promo_codes')</span>
                </a>
            </li>
            <li class=" nav-item {{ strtok(\Illuminate\Support\Facades\Route::currentRouteName(),'.') == 'artist_reservations' ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{ route('artist_reservations.index') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                         xmlns:svgjs="http://svgjs.com/svgjs" width="512" height="512" x="0" y="0"
                         viewBox="0 0 512 512"
                         style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g>
                            <g>
                                <path
                                    d="m456.832 32.133h-41.232v-17.133c0-8.284-6.716-15-15-15s-15 6.716-15 15v17.133h-66.4v-17.133c0-8.284-6.716-15-15-15s-15 6.716-15 15v17.133h-66.4v-17.133c0-8.284-6.716-15-15-15s-15 6.716-15 15v17.133h-66.4v-17.133c0-8.284-6.716-15-15-15s-15 6.716-15 15v17.133h-41.234c-30.418 0-55.166 24.747-55.166 55.167v369.533c0 30.419 24.748 55.167 55.166 55.167h401.666c30.419 0 55.168-24.748 55.168-55.167v-369.533c0-30.42-24.749-55.167-55.168-55.167zm-401.666 30h41.234v17.134c0 8.284 6.716 15 15 15s15-6.716 15-15v-17.134h66.398v17.134c0 8.284 6.716 15 15 15s15-6.716 15-15v-17.134h66.4v17.134c0 8.284 6.716 15 15 15s15-6.716 15-15v-17.134h66.4v17.134c0 8.284 6.716 15 15 15s15-6.716 15-15v-17.134h41.232c13.879 0 25.17 11.29 25.17 25.167v41.233h-452v-41.233c0-13.877 11.29-25.167 25.166-25.167zm401.666 419.867h-401.666c-13.876 0-25.166-11.29-25.166-25.167v-298.3h452v298.3c0 13.877-11.291 25.167-25.168 25.167z"
                                    fill="{{ strtok(\Illuminate\Support\Facades\Route::currentRouteName(),'.') == 'artist_reservations' ? '#ffffff' : '#000000' }}"
                                    data-original="#000000" class=""></path>
                                <path
                                    d="m151.566 208.867h-64.267c-8.284 0-15 6.716-15 15v64.266c0 8.284 6.716 15 15 15h64.268c8.284 0 15-6.716 15-15v-64.266c-.001-8.284-6.716-15-15.001-15zm-15 64.266h-34.268v-34.266h34.268z"
                                    fill="{{ strtok(\Illuminate\Support\Facades\Route::currentRouteName(),'.') == 'artist_reservations' ? '#ffffff' : '#000000' }}"
                                    data-original="#000000" class=""></path>
                                <path
                                    d="m424.699 208.867h-64.266c-8.284 0-15 6.716-15 15v64.266c0 8.284 6.716 15 15 15h64.266c8.284 0 15-6.716 15-15v-64.266c0-8.284-6.716-15-15-15zm-15 64.266h-34.266v-34.266h34.266z"
                                    fill="{{ strtok(\Illuminate\Support\Facades\Route::currentRouteName(),'.') == 'artist_reservations' ? '#ffffff' : '#000000' }}"
                                    data-original="#000000" class=""></path>
                                <path
                                    d="m151.566 337.4h-64.267c-8.284 0-15 6.716-15 15v64.266c0 8.284 6.716 15 15 15h64.268c8.284 0 15-6.716 15-15v-64.266c-.001-8.284-6.716-15-15.001-15zm-15 64.266h-34.268v-34.266h34.268z"
                                    fill="{{ strtok(\Illuminate\Support\Facades\Route::currentRouteName(),'.') == 'artist_reservations' ? '#ffffff' : '#000000' }}"
                                    data-original="#000000" class=""></path>
                                <path
                                    d="m424.699 337.4h-64.266c-8.284 0-15 6.716-15 15v64.266c0 8.284 6.716 15 15 15h64.266c8.284 0 15-6.716 15-15v-64.266c0-8.284-6.716-15-15-15zm-15 64.266h-34.266v-34.266h34.266z"
                                    fill="{{ strtok(\Illuminate\Support\Facades\Route::currentRouteName(),'.') == 'artist_reservations' ? '#ffffff' : '#000000' }}"
                                    data-original="#000000" class=""></path>
                                <path
                                    d="m288.133 337.4h-64.268c-8.284 0-15 6.716-15 15v64.266c0 8.284 6.716 15 15 15h64.268c8.284 0 15-6.716 15-15v-64.266c0-8.284-6.716-15-15-15zm-15 64.266h-34.268v-34.266h34.268z"
                                    fill="{{ strtok(\Illuminate\Support\Facades\Route::currentRouteName(),'.') == 'artist_reservations' ? '#ffffff' : '#000000' }}"
                                    data-original="#000000" class=""></path>
                                <path
                                    d="m279.475 222.673-34.834 34.836-12.116-12.116c-5.857-5.858-15.355-5.858-21.213 0s-5.858 15.355 0 21.213l22.723 22.723c2.813 2.813 6.628 4.394 10.606 4.394 3.979 0 7.794-1.581 10.607-4.394l45.441-45.443c5.857-5.858 5.857-15.355 0-21.213-5.859-5.858-15.356-5.858-21.214 0z"
                                    fill="{{ strtok(\Illuminate\Support\Facades\Route::currentRouteName(),'.') == 'artist_reservations' ? '#ffffff' : '#000000' }}"
                                    data-original="#000000" class=""></path>
                            </g>
                        </g></svg>
                    <span class="menu-title text-truncate" data-i18n="Email">@lang('common.reservations')</span>
                </a>
            </li>
            <li class=" nav-item {{ strtok(\Illuminate\Support\Facades\Route::currentRouteName(),'.') == 'artist_chats' ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{ route('artist_chats.index') }}">
                    <i class="ficon" data-feather="message-square"></i>
                    <span class="menu-title text-truncate" data-i18n="Email">@lang('common.chats')</span>
                </a>
            </li>
            <li class=" nav-item {{ strtok(\Illuminate\Support\Facades\Route::currentRouteName(),'.') == 'artist_financial_accounts' ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{ route('artist_financial_accounts.index') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="512" height="512" x="0" y="0"
                         viewBox="0 0 64 64" style="enable-background:new 0 0 512 512" xml:space="preserve"><g>
                            <path
                                d="M46.707 6.293A1 1 0 0 0 46 6H18a5 5 0 0 0-4.842 3.751l1.936.5A3 3 0 0 1 18 8h27v5a3 3 0 0 0 3 3h5v41a3 3 0 0 1-3 3H18a3 3 0 0 1-3-3V34h-2v23a5.006 5.006 0 0 0 5 5h32a5.006 5.006 0 0 0 5-5V15a1 1 0 0 0-.293-.707zM47 13V9.414L51.586 14H48a1 1 0 0 1-1-1z"
                                fill="#000000" data-original="#000000"></path>
                            <path
                                d="M54 2H22a5 5 0 0 0-3.308 1.25l1.325 1.5A2.989 2.989 0 0 1 22 4h32a3 3 0 0 1 3 3v46a2.989 2.989 0 0 1-.75 1.983l1.5 1.325A5 5 0 0 0 59 53V7a5.006 5.006 0 0 0-5-5zM16 28v-1a3 3 0 0 0 0-6h-2a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1h2a3 3 0 0 0-3-3v-1h-2v1a3 3 0 0 0 0 6h2a1 1 0 0 1 0 2h-2a1 1 0 0 1-1-1h-2a3 3 0 0 0 3 3v1z"
                                fill="#000000" data-original="#000000"></path>
                            <path
                                d="M25 22a10 10 0 1 0-10 10 10.011 10.011 0 0 0 10-10zM7 22a8 8 0 1 1 8 8 8.009 8.009 0 0 1-8-8zM28 16h12v2H28zM28 12h2v2h-2zM32 12h8v2h-8zM24 31a1 1 0 0 0-1 1v5h-2v2h26v-2h-2V24a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v13h-2v-9a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v9h-2v-5a1 1 0 0 0-1-1zm17-6h2v12h-2zm-8 4h2v8h-2zm-8 8v-4h2v4zM22 43a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V44a1 1 0 0 0-1-1zm9 10h-8v-8h8zM37 43h12v2H37zM37 51h12v2H37zM37 47h12v2H37z"
                                fill="#000000" data-original="#000000"></path>
                        </g></svg>
                    <span class="menu-title text-truncate" data-i18n="Invoice">@lang('common.financial_accounts')</span>
                </a>
            </li>
        </ul>
    </div>
</div>
<!-- END: Main Menu-->
