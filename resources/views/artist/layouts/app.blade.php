<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="rtl">

@include('artist.layouts.head')

<body class="vertical-layout vertical-menu-modern  navbar-floating footer-static  " data-open="click"
      data-menu="vertical-menu-modern" data-col="">

@include('artist.layouts.header')

@include('artist.layouts.sidebar')

@section('chat-main-content')
@show

<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <section id="dashboard-ecommerce">
                @section('main-content')
                @show
            </section>
        </div>
    </div>
</div>

@include('artist.layouts.footer')

</body>
</html>
