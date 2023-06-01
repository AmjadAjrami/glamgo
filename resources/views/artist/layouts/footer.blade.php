<div class="sidenav-overlay"></div>
<div class="drag-target"></div>

<!-- BEGIN: Footer-->
<footer class="footer footer-static footer-light">

</footer>
<button class="btn btn-primary btn-icon scroll-top" type="button"><i data-feather="arrow-up"></i></button>
<!-- END: Footer-->


<!-- BEGIN: Vendor JS-->
<script src="{{ asset('app-assets/vendors/js/vendors.min.js') }}"></script>
<!-- BEGIN Vendor JS-->

<!-- BEGIN: Page Vendor JS-->
<script src="{{ asset('app-assets/vendors/js/charts/apexcharts.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/extensions/toastr.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/tables/datatable/datatables.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/forms/wizard/bs-stepper.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/forms/cleave/cleave.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/file-uploaders/dropzone.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/extensions/polyfill.min.js') }}"></script>
<!-- END: Page Vendor JS-->

<!-- BEGIN: Theme JS-->
<script src="{{ asset('app-assets/js/core/app-menu.js') }}"></script>
<script src="{{ asset('app-assets/js/core/app.js') }}"></script>
<!-- END: Theme JS-->

<!-- BEGIN: Page JS-->
<script src="{{ asset('app-assets/js/scripts/pages/dashboard-ecommerce.js') }}"></script>
<script src="{{ asset('app-assets/js/scripts/pages/auth-login.js') }}"></script>
<script src="{{ asset('app-assets/js/scripts/pages/modal-edit-user.js') }}"></script>
<script src="{{ asset('app-assets/js/scripts/forms/form-file-uploader.js') }}"></script>
<script src="{{ asset('app-assets/js/scripts/extensions/ext-component-sweet-alerts.js') }}"></script>
<script src="{{ asset('app-assets/js/scripts/forms/form-select2.js') }}"></script>
<script src="{{ asset('app-assets/file-uploader/fileinput.js') }}"></script>
<script src="{{ asset('app-assets/js/scripts/pages/app-chat.js') }}"></script>
<!-- END: Page JS-->

{{--<script src="{{ asset('app-assets/js/scripts/tables/table-datatables-advanced.js') }}"></script>--}}

<script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

<script src="https://js.pusher.com/7.2/pusher.min.js"></script>

<script>
    $(window).on('load', function() {
        if (feather) {
            feather.replace({
                width: 14,
                height: 14
            });
        }
    })

    var selectedIds = function () {
        return $("input[name='table_ids[]']:checked").map(function () {
            return this.value;
        }).get();
    };
    $(document).on('change', "#select_all", function (e) {
        this.checked ? $(".table_ids").each(function () {
            this.checked = true
        }) : $(".table_ids").each(function () {
            this.checked = false
        })
        $('#delete_btn').attr('data-id', selectedIds().join());
        $('.status_btn').attr('data-id', selectedIds().join());
        if (selectedIds().join().length) {
            $('#delete_btn').prop('disabled', '');
            $('.status_btn').prop('disabled', '');
        } else {
            $('#delete_btn').prop('disabled', 'disabled');
            $('.status_btn').prop('disabled', 'disabled');
        }
    });

    $(document).on('change', ".table_ids", function (e) {
        if ($(".table_ids:checked").length === $(".table_ids").length) {
            $("#select_all").prop("checked", true)
        } else {
            $("#select_all").prop("checked", false)
        }
        $('#delete_btn').attr('data-id', selectedIds().join());
        $('.status_btn').attr('data-id', selectedIds().join());
        console.log(selectedIds().join().length)
        if (selectedIds().join().length) {
            $('#delete_btn').prop('disabled', '');
            $('.status_btn').prop('disabled', '');
        } else {
            $('#delete_btn').prop('disabled', 'disabled');
            $('.status_btn').prop('disabled', 'disabled');
        }
    });

    $(document).on('submit', '.ajax_form', function (e) {
        // $('.submit_btn').prop('disabled', true);
        e.preventDefault();
        var form = $(this);
        var url = $(this).attr('action');
        var method = $(this).attr('method');
        var reset = $(this).data('reset');
        var Data = new FormData(this);
        $('.submit_btn').attr('disabled', 'disabled');
        $('.fa-spinner.fa-spin').show();

        $.ajaxSetup({
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
        });

        $.ajax({
            url: url,
            type: method,
            data: Data,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $('.invalid-feedback').html('');
                $('.is-invalid ').removeClass('is-invalid');
                form.removeClass('was-validated');
            }
        }).done(function (data) {
            if (data.status) {
                toastr.success('@lang('common.done_successfully')');
                if (reset === true) {
                    form[0].reset();
                    $('.submit_btn').removeAttr('disabled');
                    $('.fa-spinner.fa-spin').hide();
                    $('.modal').modal('hide');
                    // $("#city_id").selectpicker("refresh");
                    // $("#country_id").selectpicker("refresh");
                    dt_adv_filter.draw();
                    $('#add_btn_2').attr('hidden', true);
                } else {
                    var url = $('#cancel_btn').attr('href');
                    window.location.replace(url);
                }
            } else {
                if (data.message) {
                    toastr.error(data.message);
                } else {
                    toastr.error('@lang('common.something_wrong')');
                    console.log(data.errors)
                    console.log(111)
                    $.each(data.errors, function (index, value) {
                        toastr.error(value);
                    });
                }
                $('.submit_btn').removeAttr('disabled');
                $('.fa-spinner.fa-spin').hide();
            }
        }).fail(function (data) {
            if (data.status === 422) {
                var response = data.responseJSON;
                $.each(response.errors, function (key, value) {
                    toastr.error(value);
                    var str = (key.split("."));
                    if (str[1] === '0') {
                        key = str[0] + '[]';
                    }
                    console.log(key);
                    $('[name="' + key + '"], [name="' + key + '[]"]').addClass('is-invalid');
                    $('[name="' + key + '"], [name="' + key + '[]"]').closest('.form-group').find('.invalid-feedback').html(value[0]);
                });
            } else {
                toastr.error('@lang('common.something_wrong')');
                console.log(data.status)
                $.each(data.errors, function (index, value) {
                    toastr.error(value);
                })
            }
            $('.submit_btn').removeAttr('disabled');
            $('.fa-spinner.fa-spin').hide();
        });
    });

    $(document).on("click", ".delete-btn", function (e) {
        e.preventDefault();
        var urls = url + $(this).data('id') + '/destroy';
        console.log(urls);
        Swal.fire({
            title: '@lang('common.delete_confirmation')',
            text: "@lang('common.confirm_delete')",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '@lang('common.delete')',
            cancelButtonText: '@lang('common.cancel')',
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-outline-danger ms-1'
            },
            buttonsStyling: false,
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: urls,
                    method: 'DELETE',
                    type: 'DELETE',
                    data: {
                        _token: '{{csrf_token()}}'
                    },
                }).done(function (data) {
                    if (data.status) {
                        Swal.fire({
                            icon: 'success',
                            title: '@lang('common.deleted_successfully')',
                            {{--text: '@lang('common.deleted_successfully')',--}}
                            customClass: {
                                confirmButton: 'btn btn-success'
                            }
                        });
                        dt_adv_filter.draw();
                    } else {
                        toastr.warning('@lang('common.not_deleted')');
                        Swal.fire({
                            icon: 'info',
                            title: '@lang('common.not_deleted')',
                            {{--text: '@lang('common.not_deleted')',--}}
                            customClass: {
                                confirmButton: 'btn btn-info'
                            }
                        });
                    }
                }).fail(function () {
                    toastr.error('@lang('common.something_wrong')');
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire({
                    title: '@lang('common.delete_canceled')',
                    // text: 'Your imaginary file is safe :)',
                    icon: 'error',
                    customClass: {
                        confirmButton: 'btn btn-success'
                    }
                });
            }
        });
        {{--$.confirm({--}}
        {{--    title: '@lang('common.delete_confirmation')',--}}
        {{--    content: '@lang('common.confirm_delete')',--}}
        {{--    escapeKey: true,--}}
        {{--    // autoClose: true,--}}
        {{--    closeIcon: true,--}}
        {{--    rtl: "rtl",--}}
        {{--    buttons: {--}}
        {{--        cancel: {--}}
        {{--            text: '@lang('common.cancel')',--}}
        {{--            btnClass: 'btn-default',--}}
        {{--            action: function () {--}}
        {{--                toastr.info('@lang('common.delete_canceled')')--}}
        {{--            }--}}
        {{--        },--}}
        {{--        confirm: {--}}
        {{--            text: '@lang('common.delete')',--}}
        {{--            btnClass: 'btn-red',--}}
        {{--            action: function () {--}}
        {{--                $.ajax({--}}
        {{--                    url: urls,--}}
        {{--                    method: 'DELETE',--}}
        {{--                    type: 'DELETE',--}}
        {{--                    data: {--}}
        {{--                        _token: '{{csrf_token()}}'--}}
        {{--                    },--}}
        {{--                }).done(function (data) {--}}
        {{--                    if (data.status) {--}}
        {{--                        toastr.success('@lang('common.deleted_successfully')');--}}
        {{--                        dt_adv_filter.draw();--}}
        {{--                        $('#add_btn_2').attr('hidden', false);--}}
        {{--                    } else {--}}
        {{--                        toastr.warning('@lang('common.not_deleted')');--}}
        {{--                    }--}}

        {{--                }).fail(function () {--}}
        {{--                    toastr.error('@lang('common.something_wrong')');--}}
        {{--                });--}}
        {{--            }--}}
        {{--        }--}}
        {{--    }--}}
        {{--});--}}
    });

    $(document).on('click', '.status_btn', function (e) {
        var urls = url + 'update_all_status';
        var status = $(this).val();
        // console.log(status, $(this).data('id'));
        $.ajax({
            url: urls,
            method: 'PUT',
            type: 'PUT',
            data: {
                ids: $(this).data('id'),
                status: status,
                _token: '{{csrf_token()}}'
            },
            success: function (data) {
                if (data.status) {
                    toastr.success('@lang('common.done_successfully')');
                    dt_adv_filter.draw();
                } else {
                    toastr.error('@lang('common.something_wrong')');
                }
            }
        });
    });

    $('#search_btn').on('click', function (e) {
        dt_adv_filter.draw();
        e.preventDefault();
    });
    document.addEventListener('keypress', (event) => {
        let keyCode = event.keyCode ? event.keyCode : event.which;
        if (keyCode == 13){
            dt_adv_filter.draw();
            e.preventDefault();
        }
    });
    $('#clear_btn').on('click', function (e) {
        $('#search_form')[0].reset();
        $('select').val("").trigger("change")
        dt_adv_filter.draw();
        e.preventDefault();
    });

    var notification_ringtone = '{{ asset('notification_ringtone.mp3') }}';

    function get_notifications(){
        $('#notifications_list').html('');

        $.ajaxSetup({
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
        });

        $.ajax({
            url: '{{ url(app()->getLocale() . '/artist/get_notifications/') }}/',
            method: 'GET',
            success: function (data) {
                $('#notifications_count').html(data.notifications_count)
                var html = '';
                $.each(data.notifications, function (index, value){
                    var href = '';
                    if(value.type == 'artist_reservation_notification'){
                        href = '{{ url(app()->getLocale() . '/artist/reservations') }}';
                    }
                    if(value.type == 'artist_chat_notification'){
                        href = '{{ url(app()->getLocale() . '/artist/chats?user_id=') }}' + value.user_id;
                    }
                    html += '<a class="d-flex" href="'+ href +'">' +
                        '<div class="list-item d-flex align-items-start">' +
                        '<div class="me-1">' +
                        '<div class="avatar">' +
                        '<img src="'+ value.user_image +'" alt="avatar" width="32" height="32">' +
                        '</div>' +
                        '</div>' +
                        '<div class="list-item-body flex-grow-1">' +
                        '<p class="media-heading"><span class="fw-bolder">'+ value.user_name +'</span>' +
                        '</p><small class="notification-text">'+ value.message +'</small>' +
                        '</div>' +
                        '</div>' +
                        '</a>';
                });

                $('#notifications_list').append(html);
            }
        });
    }

    get_notifications();

    // Enable pusher logging - don't include this in production
    // Pusher.logToConsole = true;

    var pusher = new Pusher('8c6f7b3b44e89a54577b', {
        cluster: 'ap2'
    });

    var channel = pusher.subscribe('artist-channel');
    channel.bind('event', function(data) {
        console.log(data);
        if(data.artist == {{ auth('artist')->id() }}){
            toastr.success(data.title, data.message);
            setTimeout(function (){
                var audio = new Audio(notification_ringtone);
                audio.play();
            }, 3000)
            get_notifications();
        }
    });

    $('#notification_btn').on('click', function (){
        $.ajaxSetup({
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
        });

        $.ajax({
            url: '{{ url(app()->getLocale() . '/artist/read_notifications/') }}/',
            method: 'GET',
            success: function (data) {
                setInterval(function (){
                    get_notifications();
                }, 20000)
            }
        });
    });
</script>

@yield('scripts')
