<!-- BEGIN: Head-->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <meta name="description"
          content="Vuexy admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords"
          content="admin template, Vuexy admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="PIXINVENT">
    <title>@lang('common.project_title') - {{ auth('salon')->user()->name }}</title>
    <link rel="apple-touch-icon" href="{{ asset('app-assets/images/ico/apple-icon-120.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('lamsa.ico') }}">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600"
          rel="stylesheet">
    <style>
        /*#toast-container{*/
        /*    display: none !important;*/
        /*}*/
        .dataTables_length label {
            display: -webkit-inline-box !important;
            margin-right: 20px !important;
        }

        select.form-select {
            margin-top: -8px !important;
        }

        div#datatable_info {
            margin-right: 20px !important;
        }

        #logo {
            width: 121px;
            margin-top: -11px;
            margin-right: 30px;
        }
    </style>
    @if(LaravelLocalization::getCurrentLocaleDirection() == 'rtl')
        <!-- BEGIN: Vendor CSS-->
        <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/vendors-rtl.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/charts/apexcharts.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/extensions/toastr.min.css') }}">
        {{--        <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/tables/datatable/datatables.css') }}">--}}
        {{--        <link rel="stylesheet" type="text/css"--}}
        {{--              href="{{ asset('app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">--}}
        {{--        <link rel="stylesheet" type="text/css"--}}
        {{--              href="{{ asset('app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">--}}
        <link rel="stylesheet" type="text/css"
              href="{{ asset('app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
        <link rel="stylesheet" type="text/css"
              href="{{ asset('app-assets/vendors/css/file-uploaders/dropzone.min.css') }}">
        <link rel="stylesheet" type="text/css"
              href="{{ asset('app-assets/css-rtl/plugins/extensions/ext-component-sweet-alerts.css') }}">
        <link rel="stylesheet" type="text/css"
              href="{{ asset('app-assets/vendors/css/forms/select/select2.min.css') }}">
        <!-- END: Vendor CSS-->

        <!-- BEGIN: Theme CSS-->
        <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css-rtl/bootstrap.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css-rtl/bootstrap-extended.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css-rtl/colors.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css-rtl/components.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css-rtl/themes/dark-layout.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css-rtl/themes/bordered-layout.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css-rtl/themes/semi-dark-layout.css') }}">
        <link rel="stylesheet" type="text/css"
              href="{{ asset('app-assets/vendors/css/forms/select/select2.min.css') }}">

        <!-- BEGIN: Page CSS-->
        <link rel="stylesheet" type="text/css"
              href="{{ asset('app-assets/css-rtl/core/menu/menu-types/vertical-menu.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css-rtl/pages/dashboard-ecommerce.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css-rtl/plugins/charts/chart-apex.css') }}">
        <link rel="stylesheet" type="text/css"
              href="{{ asset('app-assets/css-rtl/plugins/extensions/ext-component-toastr.css') }}">
        <link rel="stylesheet" type="text/css"
              href="{{ asset('app-assets/css-rtl/core/menu/menu-types/vertical-menu.css') }}">
        <link rel="stylesheet" type="text/css"
              href="{{ asset('app-assets/css-rtl/plugins/forms/form-validation.css') }}">
        <link rel="stylesheet" type="text/css"
              href="{{ asset('app-assets/css-rtl/plugins/forms/form-file-uploader.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css-rtl/pages/authentication.css') }}">
        <link rel="stylesheet" type="text/css"
              href="{{ asset('app-assets/css-rtl/core/menu/menu-types/vertical-menu.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css-rtl/plugins/forms/form-wizard.css') }}">
        <link rel="stylesheet" type="text/css"
              href="{{ asset('app-assets/css-rtl/plugins/forms/form-validation.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css-rtl/pages/modal-create-app.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css-rtl/pages/app-chat.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css-rtl/pages/app-chat-list.css') }}">
        <!-- END: Page CSS-->

        <!-- BEGIN: Custom CSS-->
        <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css-rtl/custom-rtl.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style-rtl.css') }}">
        <!-- END: Custom CSS-->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Almarai:wght@300&display=swap" rel="stylesheet">
        <style>
            * {
                font-family: 'Almarai', sans-serif;
            }

            button.delete-btn.bs-tooltip,
            button.user-delete-btn.bs-tooltip {
                background: none;
                border: none;
                color: #000000 !important;
            }

            button.bs-tooltip.edit_btn {
                background: no-repeat;
                border: none;
                color: #000000 !important;
            }

            .toast-message {
                margin-right: 30px !important;
            }
        </style>
    @else
        <!-- BEGIN: Vendor CSS-->
        <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/vendors.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/charts/apexcharts.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/extensions/toastr.min.css') }}">
        <link rel="stylesheet" type="text/css"
              href="{{ asset('app-assets/css-rtl/plugins/extensions/ext-component-sweet-alerts.css') }}">
        <!-- END: Vendor CSS-->

        <!-- BEGIN: Theme CSS-->
        <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/bootstrap.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/bootstrap-extended.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/colors.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/components.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/themes/dark-layout.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/themes/bordered-layout.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/themes/semi-dark-layout.css') }}">
        <link rel="stylesheet" type="text/css"
              href="{{ asset('app-assets/vendors/css/forms/select/select2.min.css') }}">

        <!-- BEGIN: Page CSS-->
        <link rel="stylesheet" type="text/css"
              href="{{ asset('app-assets/css/core/menu/menu-types/vertical-menu.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/pages/dashboard-ecommerce.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/charts/chart-apex.css') }}">
        <link rel="stylesheet" type="text/css"
              href="{{ asset('app-assets/css/plugins/extensions/ext-component-toastr.css') }}">
        <link rel="stylesheet" type="text/css"
              href="{{ asset('app-assets/css/core/menu/menu-types/vertical-menu.css') }}">
        <link rel="stylesheet" type="text/css"
              href="{{ asset('app-assets/css/plugins/forms/form-validation.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/pages/authentication.css') }}">
        <link rel="stylesheet" type="text/css"
              href="{{ asset('app-assets/css/core/menu/menu-types/vertical-menu.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/forms/form-wizard.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/forms/form-validation.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/pages/modal-create-app.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/pages/app-chat.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/pages/app-chat-list.css') }}">
        <!-- END: Page CSS-->

        <!-- BEGIN: Custom CSS-->
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
        <!-- END: Custom CSS-->
        <style>
            button.delete-btn.bs-tooltip,
            button.user-delete-btn.bs-tooltip {
                background: none;
                border: none;
                color: #000000;
            }

            button.bs-tooltip.edit_btn {
                background: no-repeat;
                border: none;
                color: #000000 !important;
            }

            button#create_btn {
                float: right !important;
            }

            form#search_form {
                margin-left: 23px !important;
            }

            div#datatable_length,
            div#datatable_info {
                margin-left: 25px;
            }
        </style>
    @endif
    <link rel="stylesheet" href="{{ asset('app-assets/file-uploader/neon-forms.css') }}">

    <style>
        /*#toast-container{*/
        /*    display: none !important;*/
        /*}*/
        .dataTables_length label {
            display: -webkit-inline-box !important;
            margin-right: 20px !important;
        }

        select.form-select {
            margin-top: -8px !important;
        }

        div#datatable_info {
            margin-right: 20px !important;
        }

        #toast-container > .toast-success {
            color: green !important;
            background-color: #ffffff !important;
        }

        #toast-container > .toast-error {
            color: red !important;
            background-color: #ffffff !important;
        }
        #logo{
            width: 40px;
            margin-top: -20px;
            margin-right: 30px;
        }
        .main-menu.menu-light .navigation > li.active > a,
        .main-menu.menu-light .navigation > li ul .active{
            background: linear-gradient(118deg, #D46676, rgb(212 102 118)) !important;
            box-shadow: 0 0 10px 1px rgb(225 172 180) !important;
            color: #fff !important;
            font-weight: 400 !important;
            border-radius: 4px !important;
        }

        .btn-primary {
            border-color: #D46676 !important;
            background-color: #D46676 !important;
            color: #fff !important;
        }

        .btn-primary:focus, .btn-primary:active, .btn-primary.active {
            color: #fff;
            background-color: #D46676 !important;
        }

        .btn-primary:hover:not(.disabled):not(:disabled) {
            box-shadow: 0 8px 25px -8px #D46676;
        }

        .btn-primary:not(:disabled):not(.disabled):active:focus {
            box-shadow: none;
        }

        .btn-check:checked + .btn-primary, .btn-check:active + .btn-primary {
            color: #fff;
            background-color: #D46676 !important;
        }

        .btn-outline-primary {
            border: 1px solid #D46676 !important;
            background-color: transparent;
            color: #D46676;
        }

        .page-item.active .page-link {
            background-color: #D46676 !important;
        }

        .select2-container--classic .select2-selection--multiple .select2-selection__choice, .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #D46676 !important;
            border-color: #D46676 !important;
        }

        .form-check-input:checked {
            background-color: #D46676;
            border-color: #D46676;
        }

        .select2-container--classic .select2-results__option[aria-selected='true'], .select2-container--default .select2-results__option[aria-selected='true'] {
            background-color: #D46676 !important;
            color: #FFFFFF !important;
        }

        .btn-outline-primary:not(:disabled):not(.disabled):active, .btn-outline-primary:not(:disabled):not(.disabled).active, .btn-outline-primary:not(:disabled):not(.disabled):focus{
            background-color: rgb(237 169 179) !important;
            color: #d46676 !important;
        }

        .toast-title {
            margin-right: 30px !important;
            /*color: #ffffff !important;*/
        }

        .toast-message {
            /*color: #ffffff !important;*/
        }

        .select2 {
            visibility: visible !important;
        }
        a.dropdown-item:hover{
            background-color: #fbd9dd;
            color: #D46676;
        }
        .select2-container--classic .select2-results__option--highlighted, .select2-container--default .select2-results__option--highlighted{
            background-color: rgb(237 169 179) !important;
            color: #d46676 !important;
        }
        .select2-container--classic.select2-container--focus .select2-selection--multiple, .select2-container--default.select2-container--focus .select2-selection--multiple{
            border-color: #d46676 !important;
        }
    </style>
</head>
<!-- END: Head-->
