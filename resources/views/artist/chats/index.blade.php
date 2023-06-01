@extends('artist.layouts.app')

@section('chat-main-content')
    <style>
        .chat-application .sidebar-content .chat-user-list-wrapper li.active {
            background-image: -webkit-linear-gradient(170deg, #d46676, #b74959) !important;
        }

        .chat-application .sidebar-content .chat-list-title {
            color: #d46676 !important;
        }

        .chat-app-window .chats .chat .chat-body .chat-content {
            background-image: none !important;
            color: #000000;
            background-color: #ffffff;
        }

        .chat-app-window .chats .chat-left .chat-body .chat-content {
            /*background-image: none !important;*/
            background-image: -webkit-linear-gradient(170deg, #d46676, #b74959) !important;
            color: #ffffff;
        }


        .audio-record {
            padding: 10px 10px 20px 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #recordButton {
            border: none;
            border-radius: 50%;
            transition: all 0.3s ease-in-out 0s;
            cursor: pointer;
            padding: 0;
        }

        .button-animate {
            animation: pulse 1s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0px 0px 3px 1px rgba(19, 31, 74, 0.2);
            }

            100% {
                box-shadow: 0px 0px 3px 20px rgba(19, 31, 74, 0.2);
            }
        }

        #stopButton {

            border: none;
            border-radius: 50%;
            padding: 0;
            cursor: pointer;
        }

        .playback {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        #audio-playback {
            width: 600px;
            height: 50px;
        }

        audio::-webkit-media-controls-panel,
        video::-webkit-media-controls-panel {
            background-color: #fff;
        }

        .download {
            display: flex;
            justify-content: center;
        }

        #downloadButton {
            text-decoration: none;
            color: #1E1014;
        }

        #downloadContainer {
            text-transform: uppercase;
            letter-spacing: 4px;
            font-size: 18px;
            font-weight: bold;
            color: #1E1014;
            background-color: #FBBA72;
            border: none;
            border-radius: 5px;
            padding: 20px;
            cursor: pointer;
            display: none !important;
        }

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

        @media only screen and (max-width: 700px) {
            .modal-content {
                width: 100%;
            }
        }
        .chat-application .sidebar-content .chat-user-list-wrapper,
        .chat-application .sidebar-content{
            width: 385px !important;
        }
    </style>
    <div class="app-content content chat-application">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-area-wrapper container-xxl p-0">
            <div class="sidebar-left">
                <div class="sidebar">
                    <!-- Chat Sidebar area -->
                    <div class="sidebar-content">
                        <span class="sidebar-close-icon">
                            <i data-feather="x"></i>
                        </span>
                        <!-- Sidebar header start -->
                        <div class="chat-fixed-search">
                            <div class="d-flex align-items-center w-100">
                                <div class="sidebar-profile-toggle">
                                    <div class="avatar avatar-border">
                                        <img src="{{ auth('artist')->user()->image }}" alt="user_avatar" height="42"
                                             width="42"/>
                                    </div>
                                    @lang('common.artist') : {{ auth('artist')->user()->name }}
                                </div>
                            </div>
                        </div>
                        <!-- Sidebar header end -->

                        <!-- Sidebar Users start -->
                        <div id="users-list" class="chat-user-list-wrapper list-group">
                            <h4 class="chat-list-title">@lang('common.chats')</h4>
                            <ul class="chat-users-list chat-list media-list">
                                @foreach($chats as $chat)
                                    <li data-key="{{ $chat->firebase_id }}"
                                        class="chat_item {{ $chat->user_id == request('user_id') ? 'active' : '' }}"
                                        data-user_id="{{ $chat->user_id }}">
                                        <span class="avatar"><img src="{{ @$chat->user->image }}"
                                                                  height="42" width="42"/>
                                        </span>
                                        <div class="chat-info flex-grow-1">
                                            <h5 class="mb-0">{{ @$chat->user->name }}</h5>
                                            <p class="card-text text-truncate">
                                                {{ $chat->last_message }}
                                            </p>
                                        </div>
                                        <div class="chat-meta text-nowrap">
                                            <small
                                                class="float-end mb-25 chat-time">{{ $chat->last_message_date }}</small>
                                            @if($chat->provider_unread_messages > 0)
                                                <span
                                                    class="badge bg-danger rounded-pill float-end">{{ $chat->provider_unread_messages }}</span>
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <!-- Sidebar Users end -->
                    </div>
                    <!--/ Chat Sidebar area -->
                </div>
            </div>
            <div class="content-right">
                <div class="content-wrapper container-xxl p-0">
                    <div class="content-header row">
                    </div>
                    <div class="content-body">
                        {{--                        <div class="body-content-overlay"></div>--}}
                        <!-- Main chat area -->
                        <section class="chat-app-window">
                            <div class="start-chat-area">
                                <div class="mb-1 start-chat-icon">
                                    <i data-feather="message-square"></i>
                                </div>
                            </div>
                            <!-- Active Chat -->
                            <div class="active-chat d-none">
                                <!-- Chat Header -->
                                <div class="chat-navbar">
                                    <header class="chat-header">
                                        <div class="d-flex align-items-center">
                                            <div class="sidebar-toggle d-block d-lg-none me-1">
                                                <i data-feather="menu" class="font-medium-5"></i>
                                            </div>
                                            <div class="avatar avatar-border m-0 me-1">
                                                <img id="user_image"
                                                     alt="avatar" height="36" width="36"/>
                                            </div>
                                            @lang('common.user') : <h6 class="mb-0" id="user_name"></h6>
                                        </div>
                                        <div class="sidebar-toggle d-block"
                                             style="width: 250px;margin-top: 13px;margin-left: -40px !important;">
                                            <span>@lang('common.translation_language') : </span>
                                            <select name="change_language" class="form-control" id="change_language"
                                                    style="display: inline !important;width: 55% !important;">
                                                <option selected disabled>@lang('common.choose')</option>
                                                <option value="1">@lang('common.Arabic')</option>
                                                <option value="2">@lang('common.English')</option>
                                            </select>
                                        </div>
                                    </header>
                                </div>
                                <!--/ Chat Header -->

                                <!-- User Chat messages -->
                                <div class="user-chats" id="user-chats">
                                    <div class="chats" id="chats" style="overflow-x: hidden;">

                                    </div>
                                </div>
                                <!-- User Chat messages -->

                                <!-- Submit Chat form -->
                                <div class="progress" style="display: none">
                                    <div class="bar" style="background-color: #2264df"></div>
                                    <div class="percent" style="font-weight: bold;">0%</div>
                                </div>

                                <div id="status"></div>

                                <form class="chat-app-form" action="javascript:void(0);">
                                    <div class="input-group input-group-merge me-1 form-send-message">
                                        <span class="speech-to-text input-group-text" href="#sound_record_modal"
                                              data-bs-toggle="modal"
                                              data-bs-target="#sound_record_modal"><i data-feather="mic"
                                                                                      class="cursor-pointer"></i></span>
                                        <input type="text" class="form-control message"
                                               placeholder="@lang('common.enter_message')"/>
                                        <span class="input-group-text">
                                            <label for="image" class="attachment-icon form-label mb-0">
                                                <i data-feather="image" class="cursor-pointer text-secondary"
                                                   id="chat_image"></i>
                                                <input type="file" id="image" hidden/> </label></span>
                                    </div>
                                    <button type="button" class="btn btn-primary send">
                                        <i data-feather="send" class="d-lg-none"></i>
                                        <span class="d-none d-lg-block">@lang('common.send')</span>
                                    </button>
                                </form>
                                <!--/ Submit Chat form -->
                            </div>
                            <!--/ Active Chat -->
                        </section>
                        <!--/ Main chat area -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-bottom modal-450 fade" id="sound_record_modal" tabindex="-1" role="dialog"
         aria-labelledby="bottom_modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-center">تسجيل صوتي</h5>
                    {{--                    <button type="button" class="close btn btn-outline-secondary rounded-circle" data-bs-dismiss="modal"--}}
                    {{--                            aria-label="Close">--}}
                    <span class="close" data-bs-dismiss="modal" style="font-size: 30px;cursor: pointer;">&times;</span>
                    {{--                    </button>--}}
                </div>
                <div class="modal-body">
                    <div class="audio-record">
                        <button id="recordButton"><img src="{{ asset('app-assets/images/record-1.svg') }}" alt="">
                        </button>
                        <button id="stopButton" class="d-none"><img src="{{ asset('app-assets/images/record-2.svg') }}"
                                                                    alt=""></button>
                    </div>
                    <div class="download">
                        <button class="hidden" id="downloadContainer">
                            <a href="" download="" id="downloadButton">Download Audio</a>
                        </button>
                    </div>
                    <div class="playback">
                        <audio src="" controls id="audio-playback" class="hidden"></audio>
                    </div>
                    <div class="progress_2" style="display: none">
                        <div class="bar_2" style="background-color: #2264df"></div>
                        <div class="percent_2" style="font-weight: bold;">0%</div>
                    </div>

                    <div id="status_2"></div>
                    <div class="d-flex justify-content-center">
                        <button type="button" class="btn btn-secondary me-2 rounded-pill d-none" data-bs-dismiss="modal"
                                id="cancel_audio">
                            @lang('common.discard')
                        </button>
                        <button type="button" class="btn btn-primary rounded-pill d-none"
                                id="chat_audio">@lang('common.send') <i class="fas fa-spinner fa-spin d-none"></i>
                        </button>
                    </div>
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
    {{--    <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>--}}

    <script src="https://www.gstatic.com/firebasejs/9.15.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.15.0/firebase-database-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.15.0/firebase-firestore-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.15.0/firebase-storage-compat.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/recorderjs/0.1.0/recorder.js"></script>

    <script>
        const firebaseConfig = {
            apiKey: "AIzaSyC3psuKtqTfGgno4XvfplEgQRh807mPVOc",
            authDomain: "qlamsa.firebaseapp.com",
            databaseURL: "https://qlamsa-default-rtdb.firebaseio.com",
            projectId: "qlamsa",
            storageBucket: "qlamsa.appspot.com",
            messagingSenderId: "257148453632",
            appId: "1:257148453632:web:5617c89a5104099722a9e5",
            measurementId: "G-QHHHE1RLBB"
        };

        // Initialize Firebase
        firebase.initializeApp(firebaseConfig);
        const database = firebase.database();
        const storage = firebase.storage();

        var user_id = 0;

        var last_position = 0;

        $('.chat_item').on('click', function () {
            let key = $(this).data('key');
            user_id = $(this).data('user_id');

            $('#chats').html('');

            var user_image = '';
            $.ajax({
                url: "{{ url(app()->getLocale() . '/artist/chats/user_details/') }}/" + user_id,
                type: "GET",
                cache: false,
                success: function (dataResult) {
                    user_image = dataResult.user.image;
                    $('#user_image').attr('src', dataResult.user.image);
                    $('#user_name').html(dataResult.user.name);
                    $('.user_profile_image').attr('src', dataResult.user.image);
                }
            });

            database.ref('chat').child(key).on('value', function (querySnapshot) {
                if (querySnapshot.exists() == true) {
                    querySnapshot.forEach(function (child) {
                        let messageRead = child.val().messageRead;
                        let idSender = child.val().idSender;

                        if (idSender != '{{ auth('artist')->id() }}') {
                            if (messageRead == false) {
                                child.ref.update({
                                    messageRead: true,
                                });
                            }
                        }
                    });
                }
            });

            database.ref('chat').child(key).on('value', function (querySnapshot) {
                let html = '';

                $.ajax({
                    url: "{{ url(app()->getLocale() . '/artist/chats/update_message_read/') }}/" + key,
                    type: "PUT",
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    cache: false,
                    success: function (dataResult) {
                    }
                });

                // if (querySnapshot.numChildren() > 0) {
                last_position = querySnapshot.numChildren();
                // }

                $('#chats').html('');

                if (querySnapshot.exists() == true) {
                    querySnapshot.forEach(function (child) {
                        let messages = child.val();
                        if (messages.idSender == '{{ auth('artist')->id() }}' && messages.userType == 'artist') {
                            if (messages.type == 'text') {
                                html += '<div class="chat chat-left">' +
                                    '<div class="chat-avatar">' +
                                    '<span class="avatar box-shadow-1 cursor-pointer">' +
                                    '<img src="{{ auth('artist')->user()->image }}" alt="avatar" height="36" width="36"/>' +
                                    '</span>' +
                                    '</div>' +
                                    '<div class="chat-body">' +
                                    '<div class="chat-content">' +
                                    '<p class="ar_messages" style="display:block">' + messages.text + '</p>' +
                                    '<p class="en_messages" style="display:none">' + messages.textEn + '</p>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>';
                            }
                            if (messages.type == 'image') {
                                html += '<div class="chat chat-left">' +
                                    '<div class="chat-avatar">' +
                                    '<span class="avatar box-shadow-1 cursor-pointer">' +
                                    '<img src="{{ auth('artist')->user()->image }}" alt="avatar" height="36" width="36"/>' +
                                    '</span>' +
                                    '</div>' +
                                    '<div class="chat-body">' +
                                    '<div class="chat-content" style="width: 34% !important;">' +
                                    '<img class="image_view" src="' + messages.image + '" style="width: 100%;height: 100% !important;">' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>';
                            }
                            if (messages.type == 'audio') {
                                html += '<div class="chat chat-left">' +
                                    '<div class="chat-avatar">' +
                                    '<span class="avatar box-shadow-1 cursor-pointer">' +
                                    '<img src="{{ auth('artist')->user()->image }}" alt="avatar" height="36" width="36"/>' +
                                    '</span>' +
                                    '</div>' +
                                    '<div class="chat-body">' +
                                    '<div class="chat-content">' +
                                    '<audio controls>' +
                                    '<source src="' + messages.audio + '" type="audio/ogg">' +
                                    '<source src="' + messages.audio + '" type="audio/mpeg">' +
                                    '</audio>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>';
                            }
                            if (messages.type == 'other') {
                                let content = '';
                                if (messages.text != '') {
                                    content += '<div class="chat-content">' +
                                        '<p class="ar_messages" style="display:block">' + messages.text + '</p>' +
                                        '<p class="en_messages" style="display:none">' + messages.text + '</p>' +
                                        '</div>';
                                }
                                if (messages.image != '') {
                                    content += '<div class="chat-content" style="width: 34% !important;">' +
                                        '<img class="image_view" src="' + messages.image + '" style="width: 100%;height: 100% !important;">' +
                                        '</div>';
                                }
                                if (messages.audio != '') {
                                    content += '<div class="chat-content">' +
                                        '<audio controls>' +
                                        '<source src="' + messages.audio + '" type="audio/ogg">' +
                                        '<source src="' + messages.audio + '" type="audio/mpeg">' +
                                        '</audio>' +
                                        '</div>';
                                }
                                html += '<div class="chat chat-left">' +
                                    '<div class="chat-avatar">' +
                                    '<span class="avatar box-shadow-1 cursor-pointer">' +
                                    '<img src="{{ auth('artist')->user()->image }}" alt="avatar" height="36" width="36"/>' +
                                    '</span>' +
                                    '</div>' +
                                    '<div class="chat-body">' +
                                    content +
                                    '</div>' +
                                    '</div>';
                            }
                        } else {
                            if (messages.type == 'text') {
                                html += '<div class="chat">' +
                                    '<div class="chat-avatar">' +
                                    '<span class="avatar box-shadow-1 cursor-pointer">' +
                                    '<img class="user_profile_image" src="' + user_image + '" alt="avatar" height="36" width="36"/>' +
                                    '</span>' +
                                    '</div>' +
                                    '<div class="chat-body">' +
                                    '<div class="chat-content">' +
                                    '<p class="ar_messages" style="display:block">' + messages.text + '</p>' +
                                    '<p class="en_messages" style="display:none">' + messages.textEn + '</p>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>';
                            }
                            if (messages.type == 'image') {
                                html += '<div class="chat">' +
                                    '<div class="chat-avatar">' +
                                    '<span class="avatar box-shadow-1 cursor-pointer">' +
                                    '<img class="user_profile_image" src="' + user_image + '" alt="avatar" height="36" width="36"/>' +
                                    '</span>' +
                                    '</div>' +
                                    '<div class="chat-body">' +
                                    '<div class="chat-content" style="width: 34% !important;">' +
                                    '<img class="image_view" src="' + messages.image + '" style="width: 100%;height: 100% !important;">' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>';
                            }
                            if (messages.type == 'audio') {
                                html += '<div class="chat">' +
                                    '<div class="chat-avatar">' +
                                    '<span class="avatar box-shadow-1 cursor-pointer">' +
                                    '<img class="user_profile_image" src="' + user_image + '" alt="avatar" height="36" width="36"/>' +
                                    '</span>' +
                                    '</div>' +
                                    '<div class="chat-body">' +
                                    '<div class="chat-content">' +
                                    '<audio controls>' +
                                    '<source src="' + messages.audio + '" type="audio/ogg">' +
                                    '<source src="' + messages.audio + '" type="audio/mpeg">' +
                                    '</audio>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>';
                            }
                            if (messages.type == 'other') {
                                let content = '';
                                if (messages.text != '') {
                                    content += '<div class="chat-content">' +
                                        '<p class="ar_messages" style="display:block">' + messages.text + '</p>' +
                                        '<p class="en_messages" style="display:none">' + messages.textEn + '</p>' +
                                        '</div>';
                                }
                                if (messages.image != '') {
                                    content += '<div class="chat-content" style="width: 34% !important;">' +
                                        '<img class="image_view" src="' + messages.image + '" style="width: 100%;height: 100% !important;">' +
                                        '</div>';
                                }
                                if (messages.audio != '') {
                                    content += '<div class="chat-content">' +
                                        '<audio controls>' +
                                        '<source src="' + messages.audio + '" type="audio/ogg">' +
                                        '<source src="' + messages.audio + '" type="audio/mpeg">' +
                                        '</audio>' +
                                        '</div>';
                                }
                                html += '<div class="chat">' +
                                    '<div class="chat-avatar">' +
                                    '<span class="avatar box-shadow-1 cursor-pointer">' +
                                    '<img class="user_profile_image" src="' + user_image + '" alt="avatar" height="36" width="36"/>' +
                                    '</span>' +
                                    '</div>' +
                                    '<div class="chat-body">' +
                                    content +
                                    '</div>' +
                                    '</div>';
                            }
                        }
                    });
                }
                $('#chats').append(html);
                // var chats_div = document.getElementById('user-chats');
                // chats_div.scrollTop = chats_div.scrollHeight;
                $('.user-chats').scrollTop($('.user-chats > .chats').height());
            });
        });

        $(document).on('change', '#change_language', function () {
            var value = $(this).val();
            console.log(value)
            if(value == 1){
                $('.ar_messages').show();
                $('.en_messages').hide();
            }else{
                $('.ar_messages').hide();
                $('.en_messages').show();
            }
            {{--var messages = $("input[name='chat_message[]']").map(function () {--}}
            {{--    return $(this).val();--}}
            {{--}).get();--}}
            {{--var messages_positions = $("input[name='chat_message_position[]']").map(function () {--}}
            {{--    return $(this).val();--}}
            {{--}).get();--}}

            {{--$.ajax({--}}
            {{--    url: "{{ url(app()->getLocale() . '/artist/chats/translation_messages') }}",--}}
            {{--    type: "GET",--}}
            {{--    data: {--}}
            {{--        translation_type: value,--}}
            {{--        messages: messages,--}}
            {{--        messages_positions: messages_positions,--}}
            {{--        _token: '{{ csrf_token() }}',--}}
            {{--    },--}}
            {{--    cache: false,--}}
            {{--    success: function (dataResult) {--}}
            {{--        var translated_messages = dataResult.messages;--}}
            {{--        $.each(translated_messages, function (index, value) {--}}
            {{--            $('#chat_message_' + value.position).html(value.message);--}}
            {{--        });--}}
            {{--    }--}}
            {{--});--}}

        });

        document.addEventListener('keypress', (event) => {
            let keyCode = event.keyCode ? event.keyCode : event.which;
            if (keyCode == 13) {
                send_message();
            }
        });

        $('.send').on('click', function () {
            send_message();
        });

        function send_message() {
            var message = $('.message').val();
            if (/\S/.test(message)) {
                const timestamp = Date.now();

                $('#chats').html('');

                var firebase_id = '{{auth('artist')->id()}}-ArtistID-' + user_id + '-UserID';

                $.ajax({
                    url: "{{ url(app()->getLocale() . '/artist/chats/translation_message') }}",
                    type: "GET",
                    data: {
                        message: message,
                        _token: '{{ csrf_token() }}',
                    },
                    cache: false,
                    success: function (dataResult) {
                        database.ref('chat').child(firebase_id).push().set({
                            idSender: {{ auth('artist')->id() }},
                            messagePosition: last_position,
                            messageRead: false,
                            sendTime: '{{ \Carbon\Carbon::parse(\Carbon\Carbon::now())->format('g:i A') }}',
                            sendDate: '{{ \Carbon\Carbon::parse(\Carbon\Carbon::now())->format('d/m/Y') }}',
                            text: message,
                            textEn: dataResult.translated_message_en,
                            textAr: dataResult.translated_message_ar,
                            type: 'text',
                            userType: 'artist',
                        });

                        $.ajax({
                            url: "{{ url(app()->getLocale() . '/artist/chats/send_message_notification') }}",
                            type: "GET",
                            data: {
                                user_id: user_id,
                                message: message,
                                firebase_id: firebase_id,
                                _token: '{{ csrf_token() }}',
                            },
                            cache: false,
                            success: function (dataResult) {
                            }
                        });
                    }
                });

                $('.message').val('');
                $('.user-chats').scrollTop($('.user-chats > .chats').height());
                // var chats_div = document.getElementById('user-chats');
                // chats_div.scrollTop = chats_div.scrollHeight;
            }
        }

        $(document).on('change', '#image', function () {
            const image = document.getElementById('image').files[0];
            const ref = storage.ref();

            const file_name = new Date().toISOString() + '_' + image.name;
            const metadata = {
                contentType: image.type,
            };

            const task = ref.child('images/' + file_name).put(image, metadata);
            task.on('state_changed', (snapshot) => {
                var progress = (snapshot.bytesTransferred / snapshot.totalBytes) * 100;

                switch (snapshot.state) {
                    case firebase.storage.TaskState.PAUSED: // or 'paused'
                        console.log('Upload is paused');
                        break;
                    case firebase.storage.TaskState.RUNNING: // or 'running'
                        console.log('Upload is running');

                        $('.progress').show();

                        var bar = $('.bar');
                        var percent = $('.percent');
                        var status = $('#status');

                        var percentVal = progress + '%';
                        bar.width(percentVal);
                        percent.html(percentVal);

                        break;
                }
            }, (error) => {
                switch (error.code) {
                    case 'storage/unauthorized':
                        break;
                    case 'storage/canceled':
                        break;
                    case 'storage/unknown':
                        break;
                }
            }, () => {
                // Upload completed successfully, now we can get the download URL
                task.snapshot.ref.getDownloadURL().then((downloadURL) => {
                    console.log('File available at', downloadURL);

                    $('#chats').html('');

                    var firebase_id = '{{auth('artist')->id()}}-ArtistID-' + user_id + '-UserID';

                    database.ref('chat').child(firebase_id).push().set({
                        idSender: {{ auth('artist')->id() }},
                        messagePosition: last_position,
                        messageRead: false,
                        sendTime: '{{ \Carbon\Carbon::parse(\Carbon\Carbon::now())->format('g:i A') }}',
                        sendDate: '{{ \Carbon\Carbon::parse(\Carbon\Carbon::now())->format('d/m/Y') }}',
                        image: downloadURL,
                        type: 'image',
                        userType: 'artist',
                    });

                    $('.progress').hide();

                    $.ajax({
                        url: "{{ url(app()->getLocale() . '/artist/chats/send_message_notification') }}",
                        type: "GET",
                        data: {
                            user_id: user_id,
                            message: 'image',
                            firebase_id: firebase_id,
                            _token: '{{ csrf_token() }}',
                        },
                        cache: false,
                        success: function (dataResult) {
                        }
                    });

                    $('.message').val('');
                    // $('.user-chats').scrollTop($('.user-chats > .chats').height());
                    $('.user-chats').scrollTop($('.user-chats > .chats').height());
                });
            });
        });

        $(document).on('click', '.image_view', function () {
            $("#full-image").attr("src", $(this).attr("src"));
            $('#image-viewer').show();
        });

        $(document).on('click', '.close', function () {
            $('#image-viewer').hide();
        });

        // audio recorder
        let recorder, audio_stream;
        const recordButton = document.getElementById("recordButton");
        recordButton.addEventListener("click", startRecording);

        // stop recording
        const stopButton = document.getElementById("stopButton");
        stopButton.addEventListener("click", stopRecording);
        stopButton.disabled = true;

        // set preview
        const preview = document.getElementById("audio-playback");

        // set download button event
        const downloadAudio = document.getElementById("downloadButton");
        downloadAudio.addEventListener("click", downloadRecording);

        var audio_blob_data = '';

        var gumStream;
        var rec;
        var audio_input;

        var AudioContext = window.AudioContext || window.webkitAudioContext;
        var audioContext;

        function startRecording() {
            // button settings
            recordButton.disabled = true;
            // recordButton.innerText = "Recording..."
            $("#recordButton").addClass("button-animate").addClass("d-none");

            $("#stopButton").addClass("button-animate").removeClass("d-none");
            stopButton.disabled = false;


            if (!$("#audio-playback").hasClass("d-none")) {
                $("#audio-playback").addClass("d-none")
            }

            if (!$("#downloadContainer").hasClass("d-none")) {
                $("#downloadContainer").addClass("d-none")
            }

            navigator.mediaDevices.getUserMedia({audio: true})
                .then(function (stream) {
                    console.log("getUserMedia() success, stream created, initializing Recorder.js ...");

                    audioContext = new AudioContext();
                    // document.getElementById("formats").innerHTML = "Format: 1 channel pcm @ " + audioContext.sampleRate / 1000 + "kHz"
                    gumStream = stream;
                    audio_input = audioContext.createMediaStreamSource(stream);
                    rec = new Recorder(audio_input, {numChannels: 1})
                    rec.record()

                    console.log("Recording started");
                });
        }

        function stopRecording() {
            rec.stop();
            // audio_stream.getAudioTracks()[0].stop();
            gumStream.getAudioTracks()[0].stop();

            // buttons reset
            recordButton.disabled = false;
            // recordButton.innerText = "Redo Recording"
            $("#recordButton").removeClass("button-animate").removeClass("d-none");

            $("#stopButton").removeClass("button-animate").addClass("d-none");


            stopButton.disabled = true;

            $("#audio-playback").removeClass("d-none");
            $("#audio-playback").removeClass("hidden");

            $("#downloadContainer").removeClass("d-none");

            $("#cancel_audio").removeClass("d-none");
            $("#chat_audio").removeClass("d-none");

            rec.exportWAV(createDownloadLink);
        }

        function createDownloadLink(blob) {
            var url = URL.createObjectURL(blob);

            preview.src = url;

            downloadAudio.href = url;
        }

        function downloadRecording() {
            var name = new Date();
            var res = name.toISOString().slice(0, 10)
            downloadAudio.download = res + '.wav';
        }

        function blobToFile(theBlob, fileName) {
            return file_1 = new File([theBlob], fileName, {lastModified: new Date().getTime(), type: theBlob.type})
        }

        function store_to_firebase(blob) {
            var url = URL.createObjectURL(blob);

            preview.src = url;

            downloadAudio.href = url;

            var file = new File([blob], 'Audio', {
                lastModified: new Date().getTime(),
                type: blob.type
            });

            const ref = storage.ref();

            const file_name = '{{ \Carbon\Carbon::now()->timestamp }}' + '_' + 'audio_record' + '.mp3';
            const metadata = {
                contentType: file.type,
            };

            const task = ref.child('Audios/' + file_name).put(file, metadata);
            task.on('state_changed', (snapshot) => {
                var progress = (snapshot.bytesTransferred / snapshot.totalBytes) * 100;
                console.log(progress);
                switch (snapshot.state) {
                    case firebase.storage.TaskState.PAUSED: // or 'paused'
                        console.log('Upload is paused');
                        $(".sendMsg").prop('disabled', true);
                        $(".sendMsg").find('i').removeClass('d-none');
                        $("#chat_audio").find('i').removeClass('d-none');
                        $(".sendMsg").find('span').addClass('d-none');

                        break;
                    case firebase.storage.TaskState.RUNNING: // or 'running'
                        console.log('Upload is running');
                        $(".sendMsg").prop('disabled', true);
                        $(".sendMsg").find('i').removeClass('d-none');
                        $(".sendMsg").find('span').addClass('d-none');
                        $("#chat_audio").find('i').removeClass('d-none');

                        $('.progress_2').show();

                        var bar_2 = $('.bar_2');
                        var percent_2 = $('.percent_2');
                        var status_2 = $('#status_2');

                        var percentVal_2 = progress + '%';
                        bar_2.width(percentVal_2);
                        percent_2.html(percentVal_2);

                        break;
                }
            }, (error) => {
                $(".sendMsg").prop('disabled', false);
                $(".sendMsg").find('i').addClass('d-none');
                $(".sendMsg").find('span').removeClass('d-none');
                $("#chat_audio").find('i').addClass('d-none');

                $(".chat-body").animate({scrollTop: $('.chat-body').prop("scrollHeight")}, 1000);

                switch (error.code) {
                    case 'storage/unauthorized':
                        break;
                    case 'storage/canceled':
                        break;
                    case 'storage/unknown':
                        break;
                }
            }, () => {
                // Upload completed successfully, now we can get the download URL
                task.snapshot.ref.getDownloadURL().then((downloadURL) => {
                    console.log('File available at', downloadURL);

                    $('#chats').html('');

                    var firebase_id = '{{auth('artist')->id()}}-ArtistID-' + user_id + '-UserID';

                    database.ref('chat').child(firebase_id).push().set({
                        idSender: {{ auth('artist')->id() }},
                        messagePosition: last_position,
                        messageRead: false,
                        sendTime: '{{ \Carbon\Carbon::parse(\Carbon\Carbon::now())->format('g:i A') }}',
                        sendDate: '{{ \Carbon\Carbon::parse(\Carbon\Carbon::now())->format('d/m/Y') }}',
                        audio: downloadURL,
                        type: 'audio',
                        userType: 'artist',
                        voiceName: '{{ \Carbon\Carbon::now()->timestamp }}.mp3'
                    });

                    $('#sound_record_modal').modal('toggle');
                    $("#sound_record_modal").modal('hide');

                    $.ajax({
                        url: "{{ url(app()->getLocale() . '/artist/chats/send_message_notification') }}",
                        type: "GET",
                        data: {
                            user_id: user_id,
                            message: 'audio',
                            firebase_id: firebase_id,
                            _token: '{{ csrf_token() }}',
                        },
                        cache: false,
                        success: function (dataResult) {
                        }
                    });

                    $('.message').val('');
                    // $('.user-chats').scrollTop($('.user-chats > .chats').height());
                    $('.user-chats').scrollTop($('.user-chats > .chats').height());
                });
            });
        }

        $(document).on('click', '#chat_audio', function () {
            rec.exportWAV(store_to_firebase)
        });
    </script>
@endsection

