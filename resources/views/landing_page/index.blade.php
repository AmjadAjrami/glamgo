<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!--favicon icon-->
    <link rel="icon" href="{{ asset('lamsa.ico') }}" type="image/ico" sizes="16x16">

    <!--title-->
    <title>QLamsa</title>

    <!--build:css-->
    <link rel="stylesheet" href="{{ asset('landing_page/assets/css/main-rtl.css') }}">
    <!-- endbuild -->
</head>

<body>

<!--preloader start-->
<div id="preloader">
    <div class="preloader-wrap">
        <img src="{{ asset('landing_page/assets/img/logo-color.png') }}" alt="logo" class="img-fluid" />
        <div class="thecube">
            <div class="cube c1"></div>
            <div class="cube c2"></div>
            <div class="cube c4"></div>
            <div class="cube c3"></div>
        </div>
    </div>
</div>
<!--preloader end-->
<!--header section start-->
<header class="header">
    <!--start navbar-->
    <nav class="navbar navbar-expand-lg fixed-top custom-nav">
        <div class="container">
            <a class="navbar-brand" href="{{ route('landing_page.index') }}">
                <img src="{{ asset('logo.png') }}" alt="logo" class="img-fluid" style="width: 120px;margin-top: 12px"/>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="ti-menu"></span>
            </button>

            <div class="collapse navbar-collapse h-auto" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto menu">
                    <li><a href="#" class="dropdown-toggle"> Home</a>
                        <ul class="sub-menu">
                            <li><a href="index.html">Home Page 01</a></li>
                            <li><a href="index-2.html">Home Page 02</a></li>
                            <li><a href="index-3.html">Home Page 03</a></li>
                            <li><a href="index-4.html">Home Page 04</a></li>
                            <li><a href="index-5.html">Home Page 05</a></li>
                            <li><a href="index-6.html">Home Page 06</a></li>
                            <li><a href="index-7.html">Home Page 07</a></li>
                            <li><a href="index-8.html">Home Page 08</a></li>
                            <li><a href="index-9.html">Home Page 09</a></li>
                            <li><a href="index-10.html">Home Page 10</a></li>
                            <li><a href="index-11.html">Home Page 11</a></li>
                            <li><a href="index-12.html">Home Page 12 <span class="badge badge-danger">New</span></a></li>
                            <li><a href="index-13.html">Home Page 13 <span class="badge badge-danger">New</span></a></li>
                        </ul>
                    </li>
                    <li><a href="#about" class="page-scroll">About</a></li>
                    <li><a href="#features" class="page-scroll">Features</a></li>
                    <li><a href="#" class="dropdown-toggle">Pages</a>
                        <ul class="sub-menu">
                            <li><a href="#" class="dropdown-toggle-inner">Login & Sign Up</a>
                                <ul class="sub-menu">
                                    <li><a href="login.html">Login Page</a></li>
                                    <li><a href="sign-up.html">Signup Page</a></li>
                                    <li><a href="password-reset.html">Reset Password</a></li>
                                </ul>
                            </li>
                            <li><a href="#" class="dropdown-toggle-inner">Utilities</a>
                                <ul class="sub-menu">
                                    <li><a href="faq.html">FAQ Page</a></li>
                                    <li><a href="404.html">404 Page</a></li>
                                    <li><a href="coming-soon.html">Coming Soon</a></li>
                                    <li><a href="thank-you.html">Thank You Page</a></li>
                                    <li><a href="download.html">Download Page <span class="badge accent-bg text-white">New</span></a></li>
                                    <li><a href="review.html">Review Page <span class="badge accent-bg text-white">New</span></a></li>
                                </ul>
                            </li>
                            <li><a href="#" class="dropdown-toggle-inner">Team</a>
                                <ul class="sub-menu">
                                    <li><a href="team.html">Our Team Members</a></li>
                                    <li><a href="team-single.html">Team Member Profile</a></li>
                                </ul>
                            </li>
                            <li><a href="#" class="dropdown-toggle-inner">Our Blog</a>
                                <ul class="sub-menu">
                                    <li><a href="blog-default.html">Blog Grid</a></li>
                                    <li><a href="blog-no-sidebar.html">Blog No Sidebar</a></li>
                                    <li><a href="blog-left-sidebar.html">Blog Left Sidebar</a></li>
                                    <li><a href="blog-right-sidebar.html">Blog Right Sidebar</a></li>
                                    <li><a href="blog-single-left-sidebar.html">Details Left Sidebar</a></li>
                                    <li><a href="blog-single-right-sidebar.html">Details Right Sidebar</a></li>
                                </ul>
                            </li>
                            <li><a href="about-us.html">About Us </a></li>
                            <li><a href="contact-us.html">Contact Us</a></li>
                            <li><a href="sale-invoice.html">Sale Invoice <span class="badge badge-danger">New</span></a></li>
                        </ul>
                    </li>
                    <li><a href="#screenshots" class="page-scroll">Screenshots</a></li>
                    <li><a href="#process" class="page-scroll">Process</a></li>
                    <li><a href="#pricing" class="page-scroll">Pricing</a></li>
                    <li><a href="#contact" class="page-scroll">Contact</a></li>
                </ul>
            </div>
        </div>
    </nav>
</header>
<!--header section end-->

<div class="main">

    <!--hero section start-->
    <section class="position-relative bg-image pt-100" image-overlay="1">
        <div class="background-image-wraper bg-position-1" style="background: url('{{ asset('landing_page/assets/img/hero-new-bg-rtl.svg') }}'); opacity: 1;"></div>
        <div class="container">
            <div class="row align-items-center justify-content-between justify-content-sm-center">
                <div class="col-md-10 col-lg-6">
                    <div class="section-heading py-5">
                        <h1 class="font-weight-bold">Creative Way to Showcase Your App</h1>
                        <p>Start working with that can provide everything you need to generate awareness, drive traffic, connect. Efficiently transform granular value with client-focused content. Energistically redefine market.</p>
                        <div class="action-btns mt-4">
                            <ul class="list-inline">
                                <li class="list-inline-item">
                                    <a href="#" class="d-flex align-items-center app-download-btn btn btn-brand-02 btn-rounded">
                                        <span class="fab fa-apple icon-size-sm mr-3"></span>
                                        <div class="download-text text-left">
                                            <small>Download form</small>
                                            <h5 class="mb-0">App Store</h5>
                                        </div>
                                    </a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="#" class="d-flex align-items-center app-download-btn btn btn-brand-02 btn-rounded">
                                        <span class="fab fa-google-play icon-size-sm mr-3"></span>
                                        <div class="download-text text-left">
                                            <small>Download form</small>
                                            <h5 class="mb-0">Google Play</h5>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-7 text-sm-center col-lg-5">
                    <div class="img-wrap">
                        <img src="{{ asset('landing_page/assets/img/app-hand-top.png') }}" alt="shape" class="img-fluid">
                    </div>
                </div>
            </div>
            <!--end row-->
        </div>
    </section>


    <!--hero section end-->

    <!--promo section start-->
    <section class="promo-section ptb-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10 col-lg-8">
                    <div class="section-heading text-center">
                        <h2>Why Apdash Different?</h2>
                        <p>Uniquely repurpose strategic core competencies with progressive content. Assertively transition ethical imperatives and collaborative manufactured products. </p>
                    </div>
                </div>
            </div>
            <div class="row justify-content-md-center justify-content-sm-center">
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 single-promo-card single-promo-hover-2 text-center p-2 mt-4">
                        <div class="card-body">
                            <div class="pb-2">
                                <span class="fas fa-cubes icon-size-lg color-primary"></span>
                            </div>
                            <div class="pt-2 pb-3">
                                <h5>Fully functional</h5>
                                <p class="mb-0">Phosfluorescently target bleeding sources through viral methodsp progressively expedite empowered.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 single-promo-card single-promo-hover-2 text-center p-2 mt-4">
                        <div class="card-body">
                            <div class="pb-2">
                                <span class="fas fa-headset icon-size-lg color-primary"></span>
                            </div>
                            <div class="pt-2 pb-3">
                                <h5>24/7 Live Chat</h5>
                                <p class="mb-0">Enthusiastically productivate interactive interfaces after energistically scale client-centered catalysts.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 single-promo-card single-promo-hover-2 text-center p-2 mt-4">
                        <div class="card-body">
                            <div class="pb-2">
                                <span class="fas fa-lock icon-size-lg color-primary"></span>
                            </div>
                            <div class="pt-2 pb-3">
                                <h5>Secure Data</h5>
                                <p class="mb-0">Synergistically architect virtual content solutions. Monotonectally communicate cooperative solutions.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--promo section end-->

    <!--about us section start-->
    <!--about us section start-->
    <div class="overflow-hidden">
        <!--about us section start-->
        <section id="about" class="position-relative overflow-hidden feature-section ptb-100 gray-light-bg ">
            <div class="container">
                <div class="row align-items-center justify-content-between">
                    <div class="col-md-12 col-lg-6">
                        <div class="feature-contents section-heading">
                            <h2>The Most Useful Resource Created For Designers</h2>
                            <p>Objectively deliver professional value with diverse web-readiness.
                                Collaboratively transition wireless customer service without goal-oriented catalysts for
                                change. Collaboratively.</p>

                            <ul class="check-list-wrap list-two-col py-3">
                                <li>Data driven quality review</li>
                                <li>Secure work environment</li>
                                <li>24x7 coverage</li>
                                <li>Lifetime updates</li>
                                <li>Management team</li>
                                <li>Tech support</li>
                                <li>Integration ready</li>
                                <li>Tons of assets</li>
                                <li>Compliance driven process</li>
                                <li>Workforce management</li>
                            </ul>

                            <div class="action-btns mt-4">
                                <a href="#" class="btn btn-brand-02 mr-3">Get Start Now</a>
                                <a href="#" class="btn btn-outline-brand-02">Learn More</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6">
                        <div class="mask-image">
                            <img src="{{ asset('landing_page/assets/img/about1.jpg') }}" class="img-fluid" alt="about">
                            <div class="item-icon video-promo-content">
                                <a href="https://www.youtube.com/watch?v=9No-FiEInLA" class="popup-youtube video-play-icon text-center m-auto"><span class="ti-control-play"></span> </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--about us section end-->
    </div>
    <!--about us section end-->
    <!--about us section end-->

    <!--features section start-->
    <div id="features" class="feature-section ptb-100 ">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-9 col-lg-9">
                    <div class="section-heading text-center mb-5">
                        <h2>Quick &amp; Easy Process With Best Features</h2>
                        <p>Objectively deliver professional value with diverse web-readiness.
                            Collaboratively transition wireless customer service without goal-oriented catalysts for
                            change. Collaboratively.</p>
                    </div>
                </div>
            </div>

            <!--feature new style start-->
            <div class="row align-items-center justify-content-md-center">
                <div class="col-lg-4 col-md-12">
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex align-items-start mb-sm-0 mb-md-5 mb-lg-5">
                                <span class="ti-face-smile icon-size-xs p-3 secondary-bg rounded shadow-sm text-white mr-4"></span>
                                <div class="icon-text">
                                    <h5 class="mb-2">Responsive web design</h5>
                                    <p>Modular and interchangable componente between layouts and even demos.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-start mb-sm-0 mb-md-5 mb-lg-5">
                                <span class="ti-vector icon-size-xs p-3 secondary-bg rounded shadow-sm text-white mr-4"></span>
                                <div class="icon-text">
                                    <h5 class="mb-2">Loaded with features</h5>
                                    <p>Modular and interchangable componente between layouts and even demos.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-start mb-sm-0 mb-md-5 mb-lg-5">
                                <span class="ti-headphone-alt icon-size-xs p-3 secondary-bg rounded shadow-sm text-white mr-4"></span>
                                <div class="icon-text">
                                    <h5 class="mb-2">Friendly online support</h5>
                                    <p>Modular and interchangable componente between layouts and even demos.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-5 d-none d-sm-none d-md-block d-lg-block">
                    <div class="position-relative pb-md-5 py-lg-0">
                        <img alt="Image placeholder" src="{{ asset('landing_page/assets/img/app-mobile-image.png') }}" class="img-center img-fluid">
                    </div>
                </div>
                <div class="col-lg-4 col-md-12">
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex align-items-start mb-sm-0 mb-md-5 mb-lg-5">
                                <span class="ti-layout-media-right icon-size-xs p-3 secondary-bg rounded shadow-sm text-white mr-4"></span>
                                <div class="icon-text">
                                    <h5 class="mb-2">Free updates forever</h5>
                                    <p>Modular and interchangable componente between layouts and even demos.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-start mb-sm-0 mb-md-5 mb-lg-5">
                                <span class="ti-layout-cta-right icon-size-xs p-3 secondary-bg rounded shadow-sm text-white mr-4"></span>
                                <div class="icon-text">
                                    <h5 class="mb-2">Built with Sass</h5>
                                    <p>Modular and interchangable componente between layouts and even demos.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-start mb-sm-0 mb-md-5 mb-lg-5">
                                <span class="ti-palette icon-size-xs p-3 secondary-bg rounded shadow-sm text-white mr-4"></span>
                                <div class="icon-text">
                                    <h5 class="mb-2">Infinite colors</h5>
                                    <p>Modular and interchangable componente between layouts and even demos.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--feature new style end-->
        </div>
    </div>
    <!--features section end-->


    <!--download button section start-->
    <section class="bg-image ptb-100" image-overlay="8">
        <div class="background-image-wraper" style="background: url('{{ asset('landing_page/assets/img/cta-bg.jpg') }}') no-repeat center center / cover fixed; opacity: 1;"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-9 col-lg-8">
                    <div class="section-heading text-center mb-1 text-white">
                        <h2 class="text-white">قم بتنزيل تطبيقاتنا</h2>
                        <p>ابدأ العمل مع ذلك يمكن أن يوفر كل ما تحتاجه لتوليد الوعي ، وزيادة حركة المرور ، والاتصال. تحويل القيمة الدقيقة بكفاءة مع المحتوى الذي يركز على العميل. إعادة تعريف السوق بشكل نشيط.</p>
                        <div class="action-btns">
                            <ul class="list-inline">
                                <li class="list-inline-item my-3">
                                    <a href="#" class="d-flex align-items-center app-download-btn btn btn-brand-02 btn-rounded">
                                        <span class="fab fa-windows icon-size-sm mr-3"></span>
                                        <div class="download-text text-left">
                                            <small>Download form</small>
                                            <h5 class="mb-0">Windows</h5>
                                        </div>
                                    </a>
                                </li>
                                <li class="list-inline-item my-3">
                                    <a href="#" class="d-flex align-items-center app-download-btn btn btn-brand-02 btn-rounded">
                                        <span class="fab fa-apple icon-size-sm mr-3"></span>
                                        <div class="download-text text-left">
                                            <small>Download form</small>
                                            <h5 class="mb-0">App Store</h5>
                                        </div>
                                    </a>
                                </li>
                                <li class="list-inline-item my-3">
                                    <a href="#" class="d-flex align-items-center app-download-btn btn btn-brand-02 btn-rounded">
                                        <span class="fab fa-google-play icon-size-sm mr-3"></span>
                                        <div class="download-text text-left">
                                            <small>Download form</small>
                                            <h5 class="mb-0">Google Play</h5>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->
        </div>
    </section>


    <!--download button section end-->

    <!--screenshots section start-->
    <section id="screenshots" class="screenshots-section pb-100 pt-100 gray-light-bg">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-9 col-lg-8">
                    <div class="section-heading text-center mb-5">
                        <h2>لقطات شاشة التطبيقات</h2>
                        <p>التأثير بشكل استباقي على قنوات القيمة المضافة عبر مهارات القيادة الخلفية. إحداث ثورة فعالة في الشبكات حول العالم في حين أن المحفزات الاستراتيجية للتغيير. </p>
                    </div>
                </div>
            </div>
            <!--start app screen carousel-->
            <div class="screenshot-wrap">
                <div class="screenshot-frame"></div>
                <div class="screen-carousel owl-carousel owl-theme dot-indicator">
                    <img src="{{ asset('landing_page/assets/img/01.jpg') }}" class="img-fluid" alt="screenshots" />
                    <img src="{{ asset('landing_page/assets/img/02.jpg') }}" class="img-fluid" alt="screenshots" />
                    <img src="{{ asset('landing_page/assets/img/03.jpg') }}" class="img-fluid" alt="screenshots" />
                    <img src="{{ asset('landing_page/assets/img/04.jpg') }}" class="img-fluid" alt="screenshots" />
                    <img src="{{ asset('landing_page/assets/img/05.jpg') }}" class="img-fluid" alt="screenshots" />
                    <img src="{{ asset('landing_page/assets/img/06.jpg') }}" class="img-fluid" alt="screenshots" />
                </div>
            </div>
            <!--end app screen carousel-->
        </div>
    </section>


    <!--screenshots section end-->

    <!--work process start-->
    <section id="process" class="work-process-section position-relative  ptb-100 ">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-9 col-lg-8">
                    <div class="section-heading text-center mb-5">
                        <h2>عملية عملنا</h2>
                        <p>
                            استضافة احترافية بسعر مناسب. إعادة تحديد الكفاءات الأساسية التي تتمحور حول المبدأ بشكل مميز من خلال الكفاءات الأساسية التي تركز على العميل.
                        </p>
                    </div>
                </div>
            </div>
            <div class="row align-items-center justify-content-md-center justify-content-sm-center">
                <div class="col-md-12 col-lg-6">
                    <div class="work-process-wrap">
                        <div class="process-single-item">
                            <div class="process-icon-item left-shape">
                                <div class="d-flex align-items-center">
                                    <div class="process-icon mr-4">
                                        <i class="fas fa-project-diagram color-primary"></i>
                                    </div>
                                    <div class="process-content text-left">
                                        <h5>فكرة التخطيط</h5>
                                        <p>مهندس شامل للخدمات الوصفية المستدامة للكفاءات الأساسية التي تتمحور حول العمليات. بحماس إعادة هندسة الاستعانة بمصادر خارجية أفضل السلالات.</p>
                                    </div>
                                </div>
                                <svg x="0px" y="0px" width="312px" height="130px">
                                    <path class="dashed1" fill="none" stroke="rgb(95, 93, 93)" stroke-width="1" stroke-dasharray="1300" stroke-dashoffset="0" d="M3.121,2.028 C3.121,2.028 1.003,124.928 99.352,81.226 C99.352,81.226 272.319,21.200 310.000,127.338"></path>
                                    <path class="dashed2" fill="none" stroke="#ffffff" stroke-width="2" stroke-dasharray="6" stroke-dashoffset="1300" d="M3.121,2.028 C3.121,2.028 1.003,124.928 99.352,81.226 C99.352,81.226 272.319,21.200 310.000,127.338 "></path>
                                </svg>
                            </div>
                        </div>
                        <div class="process-single-item">
                            <div class="process-icon-item right-shape">
                                <div class="d-flex align-items-center">
                                    <div class="process-icon ml-4">
                                        <i class="fas fa-puzzle-piece color-primary"></i>
                                    </div>
                                    <div class="process-content text-right">
                                        <h5>المنتج النهائي المطور</h5>
                                        <p>تسخير monotonectally الاستعداد الشامل للويب بعد المحفزات القائمة على الوسائط المتعددة للتغيير. أنظمة الواجهة الأمامية للعلامة التجارية تمامًا قبل الرؤية.</p>
                                    </div>
                                </div>
                                <svg x="0px" y="0px" width="312px" height="130px">
                                    <path class="dashed1" fill="none" stroke="rgb(95, 93, 93)" stroke-width="1" stroke-dasharray="1300" stroke-dashoffset="0" d="M311.000,0.997 C311.000,0.997 313.123,123.592 214.535,79.996 C214.535,79.996 41.149,20.122 3.377,125.996"></path>
                                    <path class="dashed2" fill="none" stroke="#ffffff" stroke-width="2" stroke-dasharray="6" stroke-dashoffset="1300" d="M311.000,0.997 C311.000,0.997 313.123,123.592 214.535,79.996 C214.535,79.996 41.149,20.122 3.377,125.996"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="process-single-item">
                            <div class="process-icon-item left-shape mb-0">
                                <div class="d-flex align-items-center">
                                    <div class="process-icon mr-4">
                                        <i class="fas fa-truck color-primary"></i>
                                    </div>
                                    <div class="process-content text-left">
                                        <h5>تسليم للعميل</h5>
                                        <p>انتحال Monotonectally الأعمال التآزرية للمجتمعات القائمة بذاتها. مهنيا تعزيز المنتجات المصنعة البصيرة التدريجي.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6">
                    <div class="img-wrap">
                        <img src="{{ asset('landing_page/assets/img/app-mobile-image-3.png') }}" alt="features" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!--work process end-->

    <!--pricing section start-->
    <section id="pricing" class="pricing-section ptb-100 gray-light-bg">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-9 col-lg-8">
                    <div class="section-heading text-center mb-4">
                        <h2>سعرنا المرن</h2>
                        <p>
                            استضافة احترافية بسعر مناسب. إعادة تحديد الكفاءات الأساسية التي تتمحور حول المبدأ بشكل مميز من خلال الكفاءات الأساسية التي تركز على العميل.
                        </p>
                    </div>
                </div>
            </div>
            <div class="row align-items-center justify-content-md-center justify-content-center">
                <div class="col-12">
                    <div class="d-flex justify-content-center text-center">
                        <label class="pricing-switch-wrap">
                                <span class="beforeinput year-switch text-success">
                                Monthly
                            </span>
                            <input type="checkbox" class="d-none" id="js-contcheckbox">
                            <span class="switch-icon"></span>
                            <span class="afterinput year-switch">
                                    Yearly
                            </span>
                        </label>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-8">
                    <div class="text-center bg-white single-pricing-pack mt-4">
                        <div class="price-img pt-4">
                            <img src="{{ asset('landing_page/assets/img/priching-img-1.png') }}" alt="price" width="120" class="img-fluid">
                        </div>
                        <div class="py-4 border-0 pricing-header">
                            <div class="price text-center mb-0 monthly-price color-secondary" style="display: block;">$19<span>.99</span></div>
                            <div class="price text-center mb-0 yearly-price color-secondary" style="display: none;">$69<span>.99</span></div>
                        </div>
                        <div class="price-name">
                            <h5 class="mb-0">اساسي</h5>
                        </div>
                        <div class="pricing-content">
                            <ul class="list-unstyled mb-4 pricing-feature-list">
                                <li><span>محدود</span> الوصول لمدة شهر</li>
                                <li><span>15</span> تخصيص الصفحة الفرعية</li>
                                <li class="text-deem"><span>105</span> مساحة القرص</li>
                                <li class="text-deem"><span>3</span> الوصول إلى المجال</li>
                                <li class="text-deem">دعم عبر الهاتف 24/7</li>
                            </ul>
                            <a href="#" class="btn btn-outline-brand-02 btn-rounded mb-3" target="_blank">Purchase now</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-8">
                    <div class="popular-price bg-white text-center single-pricing-pack mt-4">
                        <div class="price-img pt-4">
                            <img src="{{ asset('landing_page/assets/img/priching-img-2.png') }}" alt="price" width="120" class="img-fluid">
                        </div>
                        <div class="py-4 border-0 pricing-header">
                            <div class="price text-center mb-0 monthly-price color-secondary" style="display: block;">$49<span>.99</span></div>
                            <div class="price text-center mb-0 yearly-price color-secondary" style="display: none;">$159<span>.99</span></div>
                        </div>
                        <div class="price-name">
                            <h5 class="mb-0">الممتازة</h5>
                        </div>
                        <div class="pricing-content">
                            <ul class="list-unstyled mb-4 pricing-feature-list">
                                <li><span>غير محدود</span> الوصول لمدة شهر</li>
                                <li><span>25</span> تخصيص الصفحة الفرعية</li>
                                <li><span>150</span> مساحة القرص</li>
                                <li class="text-deem"><span>5</span> الوصول إلى المجال </li>
                                <li class="text-deem">دعم عبر الهاتف 24/7</li>
                            </ul>
                            <a href="#" class="btn btn-brand-02 btn-rounded mb-3" target="_blank">Purchase now</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-8">
                    <div class="text-center bg-white single-pricing-pack mt-4">
                        <div class="price-img pt-4">
                            <img src="{{ asset('landing_page/assets/img/priching-img-3.png') }}" alt="price" width="120" class="img-fluid">
                        </div>
                        <div class="py-4 border-0 pricing-header">
                            <div class="price text-center mb-0 monthly-price color-secondary" style="display: block;">$69<span>.99</span></div>
                            <div class="price text-center mb-0 yearly-price color-secondary" style="display: none;">$259<span>.99</span></div>
                        </div>
                        <div class="price-name">
                            <h5 class="mb-0">غير محدود</h5>
                        </div>
                        <div class="pricing-content">
                            <ul class="list-unstyled mb-4 pricing-feature-list">
                                <li><span>محدود</span> الوصول لمدة شهر</li>
                                <li><span>15</span> تخصيص الصفحة الفرعية </li>
                                <li><span>120</span> مساحة القرص </li>
                                <li><span>5</span> الوصول إلى المجال</li>
                                <li>24/7دعم عبر الهاتف </li>
                            </ul>
                            <a href="#" class="btn btn-outline-brand-02 btn-rounded mb-3" target="_blank">Purchase now</a>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="support-cta text-center mt-5">
                        <h5 class="mb-1"><span class="ti-headphone-alt color-primary mr-3"></span>نحن هنا لمساعدتك
                        </h5>
                        <p>هل لديك بعض الأسئلة؟ <a href="#">الدردشة معنا الآن</a>, or <a href="#">مراسلتنا على البريد الاليكتروني</a> أن يتواصل.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--pricing section end-->

    <!--counter section start-->
    <section class="counter-section gradient-bg ptb-40">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-md-6 col-lg-3">
                    <div class="text-white p-2 count-data text-center my-3">
                        <span class="fas fa-users icon-size-lg mb-2"></span>
                        <h3 class="count-number mb-1 text-white font-weight-bolder">21023</h3>
                        <span>الزبائن</span>
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-3">
                    <div class="text-white p-2 count-data text-center my-3">
                        <span class="fas fa-cloud-download-alt icon-size-lg mb-2"></span>
                        <h3 class="count-number mb-1 text-white font-weight-bolder">44023</h3>
                        <span>التحميلات</span>
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-3">
                    <div class="text-white p-2 count-data text-center my-3">
                        <span class="fas fa-smile icon-size-lg mb-2"></span>
                        <h3 class="count-number mb-1 text-white font-weight-bolder">35023</h3>
                        <span>راض</span>
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-3">
                    <div class="text-white p-2 count-data text-center my-3">
                        <span class="fas fa-mug-hot icon-size-lg mb-2"></span>
                        <h3 class="count-number mb-1 text-white font-weight-bolder">2323</h3>
                        <span>كوب من القهوة</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--counter section end-->

    <!--faq or accordion section start-->
    <section id="faq" class="ptb-100 gray-light-bg">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-9 col-lg-8">
                    <div class="section-heading text-center mb-5">
                        <h2>استفسارات شائعة</h2>
                        <p>إنتاج نماذج موثوقة بكفاءة قبل النماذج الموجودة في كل مكان. الاستفادة باستمرار من الخبرة الاحتكاكية في حين العلاقات التكتيكية. هل لا تزال لديك أسئلة؟ اتصل بنا</p>
                    </div>
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-md-12 col-lg-6 mb-5 mb-md-5 mb-sm-5 mb-lg-0">
                    <div class="img-wrap">
                        <img src="{{ asset('landing_page/assets/img/health.png') }}" alt="download" class="img-fluid">
                    </div>
                </div>
                <div class="col-md-12 col-lg-6">
                    <div id="accordion" class="accordion faq-wrap">
                        <div class="card mb-3">
                            <a class="card-header " data-toggle="collapse" href="#collapse0" aria-expanded="false">
                                <h6 class="mb-0 d-inline-block">ما هو الترخيص الذي أحتاجه؟</h6>
                            </a>
                            <div id="collapse0" class="collapse show" data-parent="#accordion" style="">
                                <div class="card-body white-bg">
                                    <p>تبسط monotonectically infomediaries التوصيل والتشغيل بعد عرض النطاق الترددي المميز. قم بتنمية حلول مستقبلية بشكل مميز قبل الأفكار التي تركز على العملية. توفير الوصول إلى المعلومات الذهنية التي تركز على العميل بشكل مفصل في مقابل التفاصيل الدقيقة. إستراتيجية مميزة. </p>
                                </div>
                            </div>
                        </div>
                        <div class="card my-3">
                            <a class="card-header collapsed" data-toggle="collapse" href="#collapse1" aria-expanded="false">
                                <h6 class="mb-0 d-inline-block">كيف يمكنني الوصول إلى موضوع؟</h6>
                            </a>
                            <div id="collapse1" class="collapse " data-parent="#accordion" style="">
                                <div class="card-body white-bg">
                                    <p>إعلان نباتي باستثناء الجزار نائب lomo. طماق Occaecat الحرفية البيرة مزرعة إلى طاولة ، nesciunt موالفة الجمالية الدنيم الخام التي ربما لم تسمع عنها اتهامات. تحسين إجراءات الاختبار المفوّضة بسلاسة قبل العمليات الثورية. تسهيل التقنيات التي تركز على العميل تدريجيًا في حين أن المستخدمين على نطاق واسع. بشكل رسمي. </p>
                                </div>
                            </div>
                        </div>
                        <div class="card my-3">
                            <a class="card-header collapsed" data-toggle="collapse" href="#collapse2" aria-expanded="false">
                                <h6 class="mb-0 d-inline-block">كيف أرى الطلبات السابقة؟</h6>
                            </a>
                            <div id="collapse2" class="collapse " data-parent="#accordion" style="">
                                <div class="card-body white-bg">
                                    <p>تبسط monotonectically infomediaries التوصيل والتشغيل بعد عرض النطاق الترددي المميز. قم بتنمية حلول مستقبلية بشكل مميز قبل الأفكار التي تركز على العملية. توفير الوصول إلى المعلومات الذهنية التي تركز على العميل بشكل مفصل في مقابل التفاصيل الدقيقة. إستراتيجية مميزة. </p>
                                </div>
                            </div>
                        </div>
                        <div class="card mt-3">
                            <a class="card-header collapsed" data-toggle="collapse" href="#collapse3" aria-expanded="false">
                                <h6 class="mb-0 d-inline-block">دعم القضايا ذات الصلة للقالب؟</h6>
                            </a>
                            <div id="collapse3" class="collapse " data-parent="#accordion" style="">
                                <div class="card-body white-bg">
                                    <p>إعلان نباتي باستثناء الجزار نائب lomo. طماق Occaecat الحرفية البيرة مزرعة إلى طاولة ، nesciunt موالفة الجمالية الدنيم الخام التي ربما لم تسمع عنها اتهامات. تحسين إجراءات الاختبار المفوّضة بسلاسة قبل العمليات الثورية. تسهيل التقنيات التي تركز على العميل تدريجيًا في حين أن المستخدمين على نطاق واسع. بشكل رسمي. </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--faq or accordion section end-->

    <!--our team section start-->
    <section class="team-two-section ptb-100 ">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-9 col-lg-8">
                    <div class="section-heading text-center">
                        <h2>أعضاء فريقنا</h2>
                        <p>تتشابك بشكل رسمي مع النماذج البديهية في مقابل الشراكات الموجهة نحو الهدف. استمر في توسيع المصدر المفتوح خارج منطقة الجزاء بعد التفكير في المحفزات المركزة.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-lg-3">
                    <div class="single-team-wrap bg-white text-center border rounded p-4 mt-4">
                        <img src="{{ asset('landing_page/assets/img/team/team-member-1.png') }}" alt="team image" width="120" class="img-fluid m-auto pb-4">
                        <div class="team-content">
                            <h5 class="mb-0">Richard Ford</h5>
                            <span>Instructor of Mathematics</span>
                            <p class="mt-3">Authoritatively engage leading-edge processes tactical capital </p>
                            <ul class="list-inline social-list-default social-color icon-hover-top-bottom">
                                <li class="list-inline-item">
                                    <a class="facebook" href="#" target="_blank"><i class="fab fa-facebook-f"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a class="twitter" href="#" target="_blank"><i class="fab fa-twitter"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a class="dribbble" href="#" target="_blank"><i class="fab fa-dribbble"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a class="linkedin" href="#" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="single-team-wrap bg-white text-center border rounded p-4 mt-4">
                        <img src="{{ asset('landing_page/assets/img/team/team-member-2.png') }}" alt="team image" width="120" class="img-fluid m-auto pb-4">
                        <div class="team-content">
                            <h5 class="mb-0">Kely Roy</h5>
                            <span>Lead Designer</span>
                            <p class="mt-3">Monotonectally engage sticky collaborative markets synergistically</p>
                            <ul class="list-inline social-list-default social-color icon-hover-top-bottom">
                                <li class="list-inline-item">
                                    <a class="facebook" href="#" target="_blank"><i class="fab fa-facebook-f"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a class="twitter" href="#" target="_blank"><i class="fab fa-twitter"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a class="dribbble" href="#" target="_blank"><i class="fab fa-dribbble"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a class="linkedin" href="#" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="single-team-wrap bg-white text-center border rounded p-4 mt-4">
                        <img src="{{ asset('landing_page/assets/img/team/team-member-3.png') }}" alt="team image" width="120" class="img-fluid m-auto pb-4">
                        <div class="team-content">
                            <h5 class="mb-0">Gerald Nichols</h5>
                            <span>Managing Director</span>
                            <p class="mt-3">Assertively procrastinate standardized whereas technically sound</p>
                            <ul class="list-inline social-list-default social-color icon-hover-top-bottom">
                                <li class="list-inline-item">
                                    <a class="facebook" href="#" target="_blank"><i class="fab fa-facebook-f"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a class="twitter" href="#" target="_blank"><i class="fab fa-twitter"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a class="dribbble" href="#" target="_blank"><i class="fab fa-dribbble"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a class="linkedin" href="#" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="single-team-wrap bg-white text-center border rounded p-4 mt-4">
                        <img src="{{ asset('landing_page/assets/img/team/team-member-4.png') }}" alt="team image" width="120" class="img-fluid m-auto pb-4">
                        <div class="team-content">
                            <h5 class="mb-0">Gerald Nichols</h5>
                            <span>Team Manager</span>
                            <p class="mt-3">Synergistically actualize out the-box technologies before parallel process</p>
                            <ul class="list-inline social-list-default social-color icon-hover-top-bottom">
                                <li class="list-inline-item">
                                    <a class="facebook" href="#" target="_blank"><i class="fab fa-facebook-f"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a class="twitter" href="#" target="_blank"><i class="fab fa-twitter"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a class="dribbble" href="#" target="_blank"><i class="fab fa-dribbble"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a class="linkedin" href="#" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!--our team section end-->

    <!--testimonial section start-->
    <section class="position-relative gradient-bg ptb-100">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 col-lg-5 mb-4 mb-sm-4 mb-md-0 mb-lg-0">
                    <div class="testimonial-heading text-white">
                        <h2 class="text-white">ماذا يقول عملاؤنا عن Apdash</h2>
                        <p>التعاون الفعال لتحقيق مخططات ممتازة بدون نماذج فعالة. هندسة التطبيقات الوظيفية بالتآزر بدلاً من التجارة الإلكترونية الخلفية.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6">
                    <div class="testimonial-content-wrap">
                        <div class="owl-carousel owl-theme client-testimonial-1 dot-indicator testimonial-shape">
                            <div class="item">
                                <div class="testimonial-quote-wrap">
                                    <div class="media author-info mb-3">
                                        <div class="author-img mr-3">
                                            <img src="{{ asset('landing_page/assets/img/client/1.jpg') }}" alt="client" class="img-fluid">
                                        </div>
                                        <div class="media-body text-white">
                                            <h5 class="mb-0 text-white">John Charles</h5>
                                            <span>Head Of Admin</span>
                                        </div>
                                        <i class="fas fa-quote-right text-white"></i>
                                    </div>
                                    <div class="client-say text-white">
                                        <p>تحسين الخبرة البحثية بالكامل بشكل تفاعلي مقابل علاقات التوصيل والتشغيل. تطوير جوهري للكفاءات الأساسية الفيروسية لخدمة العملاء التي تم اختبارها بالكامل. بحماس إنشاء استراتيجيات النمو والجيل القادم.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="item">
                                <div class="testimonial-quote-wrap">
                                    <div class="media author-info mb-3">
                                        <div class="author-img mr-3">
                                            <img src="{{ asset('landing_page/assets/img/client/2.jpg') }}" alt="client" class="img-fluid">
                                        </div>
                                        <div class="media-body text-white">
                                            <h5 class="mb-0 text-white">Arabella Ora</h5>
                                            <span>HR Manager</span>
                                        </div>
                                        <i class="fas fa-quote-right text-white"></i>
                                    </div>
                                    <div class="client-say text-white">
                                        <p>تطوير استراتيجيات نمو سهلة الاستخدام بسرعة بعد مبادرات واسعة النطاق. قم بزيادة الفوائد المبتكرة بشكل ملائم من خلال التسليمات المختبرة بالكامل. الاستفادة بسرعة من المنصات المركزة من خلال المخططات الشاملة.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="item">
                                <div class="testimonial-quote-wrap">
                                    <div class="media author-info mb-3">
                                        <div class="author-img mr-3">
                                            <img src="{{ asset('landing_page/assets/img/client/3.jpg') }}" alt="client" class="img-fluid">
                                        </div>
                                        <div class="media-body text-white">
                                            <h5 class="mb-0 text-white">Jeremy Jere</h5>
                                            <span>Team Leader</span>
                                        </div>
                                        <i class="fas fa-quote-right text-white"></i>
                                    </div>
                                    <div class="client-say text-white">
                                        <p>توليف موضوعي e-Tailers التي تركز على العميل للقنوات التي يمكن صيانتها. إدارة شاملة للباحثين الموجهين للعملاء في حين الوظائف التكتيكية. تحقيق الدخل الضروري من الضرورات الموثوقة من خلال التركيز على العميل.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="item">
                                <div class="testimonial-quote-wrap">
                                    <div class="media author-info mb-3">
                                        <div class="author-img mr-3">
                                            <img src="{{ asset('landing_page/assets/img/client/4.jpg') }}" alt="client" class="img-fluid">
                                        </div>
                                        <div class="media-body text-white">
                                            <h5 class="mb-0 text-white">John Charles</h5>
                                            <span>Marketing Head</span>
                                        </div>
                                        <i class="fas fa-quote-right text-white"></i>
                                    </div>
                                    <div class="client-say text-white">
                                        <p>ابتكار بحماس بيانات B2C دون التقارب بين النقرات وقذائف الهاون. يتم تنفيذ إجراءات اختبار مقنعة بشكل أحادي مقابل إجراءات اختبار B2B. قم بمصادرة الموارد المتكاملة بكفاءة بينما عالمية.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--testimonial section end-->

    <!--our contact section start-->
    <section id="contact" class="contact-us-section ptb-100">
        <div class="container">
            <div class="row justify-content-around">
                <div class="col-12 pb-3 message-box d-none">
                    <div class="alert alert-danger"></div>
                </div>
                <div class="col-md-12 col-lg-5 mb-5 mb-md-5 mb-sm-5 mb-lg-0">
                    <div class="contact-us-form gray-light-bg rounded p-5">
                        <h4>على استعداد للبدء؟</h4>
                        <form action="" method="POST" id="contactForm" class="contact-us-form">
                            <div class="form-row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="name" placeholder="Enter name" required="required">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <input type="email" class="form-control" name="email" placeholder="Enter email" required="required">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <textarea name="message" id="message" class="form-control" rows="7" cols="25" placeholder="Message"></textarea>
                                    </div>
                                </div>
                                <div class="col-sm-12 mt-3">
                                    <button type="submit" class="btn btn-brand-02" id="btnContactUs">
                                        Send Message
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-12 col-lg-6">
                    <div class="contact-us-content">
                        <h2>هل تبحث عن فكرة عمل ممتازة؟</h2>
                        <p class="lead">اتصل بنا أو اتصل بنا في أي وقت ، ونحن نسعى للرد على جميع الاستفسارات في غضون 24 ساعة في أيام العمل.</p>

                        <a href="#" class="btn btn-outline-brand-01 align-items-center">Get Directions <span class="ti-arrow-right pl-2"></span></a>

                        <hr class="my-5">

                        <ul class="contact-info-list">
                            <li class="d-flex pb-3">
                                <div class="contact-icon mr-3">
                                    <span class="fas fa-location-arrow color-primary rounded-circle p-3"></span>
                                </div>
                                <div class="contact-text">
                                    <h5 class="mb-1">Company Location</h5>
                                    <p>
                                        100 Yellow House, Mn Factory, United State, 13420
                                    </p>
                                </div>
                            </li>
                            <li class="d-flex pb-3">
                                <div class="contact-icon mr-3">
                                    <span class="fas fa-envelope color-primary rounded-circle p-3"></span>
                                </div>
                                <div class="contact-text">
                                    <h5 class="mb-1">Email Address</h5>
                                    <p>
                                        hello@yourdomain.com
                                    </p>
                                </div>
                            </li>
                        </ul>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--our contact section end-->

    <!--our blog section start-->
    <section class="our-blog-section ptb-100 gray-light-bg">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-9 col-lg-8">
                    <div class="section-heading text-center">
                        <h2>اخر اخبارنا</h2>
                        <p>
                            مصفوفة بكفاءة الربط الكلي القوي بعد عرض النطاق الترددي لتحديد المواقع في السوق. استعادة مواد B2B بشكل شامل بدلاً من النماذج المرنة للعلامة التجارية مقابل التجارة الإلكترونية المهمة للمهمة.
                        </p>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <div class="single-blog-card card border-0 shadow-sm mt-4">
                        <div class="blog-img position-relative">
                            <img src="{{ asset('landing_page/assets/img/blog/1.jpg') }}" class="card-img-top" alt="blog">
                            <div class="meta-date">
                                <strong>24</strong>
                                <small>Apr</small>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="post-meta mb-2">
                                <ul class="list-inline meta-list">
                                    <li class="list-inline-item"><i class="fas fa-heart mr-2"></i><span>45</span>
                                        Comments
                                    </li>
                                    <li class="list-inline-item"><i class="fas fa-share-alt mr-2"></i><span>10</span>
                                        Share
                                    </li>
                                </ul>
                            </div>
                            <h3 class="h5 mb-2 card-title"><a href="#">Appropriately productize fully</a></h3>
                            <p class="card-text">Some quick example text to build on the card title and make up the
                                bulk.</p>
                            <a href="#" class="detail-link">Read more <span class="ti-arrow-right"></span></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="single-blog-card card border-0 shadow-sm mt-4">
                        <div class="blog-img position-relative">
                            <img src="{{ asset('landing_page/assets/img/blog/2.jpg') }}" class="card-img-top" alt="blog">
                            <div class="meta-date">
                                <strong>24</strong>
                                <small>Apr</small>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="post-meta mb-2">
                                <ul class="list-inline meta-list">
                                    <li class="list-inline-item"><i class="fas fa-heart mr-2"></i><span>45</span>
                                        Comments
                                    </li>
                                    <li class="list-inline-item"><i class="fas fa-share-alt mr-2"></i><span>10</span>
                                        Share
                                    </li>
                                </ul>
                            </div>
                            <h3 class="h5 mb-2 card-title"><a href="#">Quickly formulate backend</a></h3>
                            <p class="card-text">Synergistically engage effective ROI after customer directed
                                partnerships.</p>
                            <a href="#" class="detail-link">Read more <span class="ti-arrow-right"></span></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="single-blog-card card border-0 shadow-sm mt-4">
                        <div class="blog-img position-relative">
                            <img src="{{ asset('landing_page/assets/img/blog/3.jpg') }}" class="card-img-top" alt="blog">
                            <div class="meta-date">
                                <strong>24</strong>
                                <small>Apr</small>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="post-meta mb-2">
                                <ul class="list-inline meta-list">
                                    <li class="list-inline-item"><i class="fas fa-heart mr-2"></i><span>45</span>
                                        Comments
                                    </li>
                                    <li class="list-inline-item"><i class="fas fa-share-alt mr-2"></i><span>10</span>
                                        Share
                                    </li>
                                </ul>
                            </div>
                            <h3 class="h5 mb-2 card-title"><a href="#">Objectively extend extensive</a></h3>
                            <p class="card-text">Holisticly mesh open-source leadership rather than proactive
                                users. </p>
                            <a href="#" class="detail-link">Read more <span class="ti-arrow-right"></span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--our blog section end-->

    <!--our team section start-->
    <section class="client-section  ptb-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="section-heading text-center mb-5">
                        <h2>عملائنا الكرام</h2>
                        <p>
                            تتحول بسرعة سريعة داخلية أو مصادر شفافة بينما تمتص الموارد التجارة الإلكترونية. ابتكار بشكل ملائم داخلي مقنع.
                        </p>
                    </div>
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="owl-carousel owl-theme clients-carousel dot-indicator">
                        <div class="item single-customer">
                            <img src="{{ asset('landing_page/assets/img/customers/clients-logo-01.png') }}" alt="client logo" class="customer-logo">
                        </div>
                        <div class="item single-customer">
                            <img src="{{ asset('landing_page/assets/img/customers/clients-logo-02.png') }}" alt="client logo" class="customer-logo">
                        </div>
                        <div class="item single-customer">
                            <img src="{{ asset('landing_page/assets/img/customers/clients-logo-03.png') }}" alt="client logo" class="customer-logo">
                        </div>
                        <div class="item single-customer">
                            <img src="{{ asset('landing_page/assets/img/customers/clients-logo-04.png') }}" alt="client logo" class="customer-logo">
                        </div>
                        <div class="item single-customer">
                            <img src="{{ asset('landing_page/assets/img/customers/clients-logo-05.png') }}" alt="client logo" class="customer-logo">
                        </div>
                        <div class="item single-customer">
                            <img src="{{ asset('landing_page/assets/img/customers/clients-logo-06.png') }}" alt="client logo" class="customer-logo">
                        </div>
                        <div class="item single-customer">
                            <img src="{{ asset('landing_page/assets/img/customers/clients-logo-07.png') }}" alt="client logo" class="customer-logo">
                        </div>
                        <div class="item single-customer">
                            <img src="{{ asset('landing_page/assets/img/customers/clients-logo-08.png') }}" alt="client logo" class="customer-logo">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--our team section end-->

</div>

<!--footer section start-->
<footer class="footer-1 ptb-60 gradient-bg">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-lg-4 mb-4 mb-md-4 mb-sm-4 mb-lg-0">
                <a href="#" class="navbar-brand mb-2">
                    <img src="{{ asset('landing_page/assets/img/logo-white.png') }}" alt="logo" class="img-fluid">
                </a>
                <br>
                <p>Dynamically re-engineer high standards in functiona with alternative paradigms. Conveniently monetize resource maximizing initiatives.</p>
                <div class="list-inline social-list-default background-color social-hover-2 mt-2">
                    <li class="list-inline-item"><a class="twitter" href="#"><i class="fab fa-twitter"></i></a></li>
                    <li class="list-inline-item"><a class="youtube" href="#"><i class="fab fa-youtube"></i></a></li>
                    <li class="list-inline-item"><a class="linkedin" href="#"><i class="fab fa-linkedin-in"></i></a></li>
                    <li class="list-inline-item"><a class="dribbble" href="#"><i class="fab fa-dribbble"></i></a></li>
                </div>
            </div>
            <div class="col-md-12 col-lg-8">
                <div class="row mt-0">
                    <div class="col-sm-6 col-md-3 col-lg-3 mb-4 mb-sm-4 mb-md-0 mb-lg-0">
                        <h6 class="text-uppercase">Resources</h6>
                        <ul>
                            <li>
                                <a href="#">Help</a>
                            </li>
                            <li>
                                <a href="#">Events</a>
                            </li>
                            <li>
                                <a href="#">Live sessions</a>
                            </li>
                            <li>
                                <a href="#">Open source</a>
                            </li>
                            <li>
                                <a href="#">Documentation</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-sm-6 col-md-3 col-lg-3 mb-4 mb-sm-4 mb-md-0 mb-lg-0">
                        <h6 class="text-uppercase">Products</h6>
                        <ul>
                            <li>
                                <a href="#">Pricing</a>
                            </li>
                            <li>
                                <a href="#">Navigation</a>
                            </li>
                            <li>
                                <a href="#">AI Studio</a>
                            </li>
                            <li>
                                <a href="#">Page Speed </a>
                            </li>
                            <li>
                                <a href="#">Performance</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-sm-6 col-md-3 col-lg-3 mb-4 mb-sm-4 mb-md-0 mb-lg-0">
                        <h6 class="text-uppercase">Company</h6>
                        <ul>
                            <li>
                                <a href="#">About Us</a>
                            </li>
                            <li>
                                <a href="#">Careers</a>
                            </li>
                            <li>
                                <a href="#">Customers</a>
                            </li>
                            <li>
                                <a href="#">Community</a>
                            </li>
                            <li>
                                <a href="#">Our Team</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-sm-6 col-md-3 col-lg-3">
                        <h6 class="text-uppercase">Support</h6>
                        <ul>
                            <li>
                                <a href="#">FAQ</a>
                            </li>
                            <li>
                                <a href="#">Sells</a>
                            </li>
                            <li>
                                <a href="#">Contact Support</a>
                            </li>
                            <li>
                                <a href="#">Network Status</a>
                            </li>
                            <li>
                                <a href="#">Product Services</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end of container-->
</footer>

<!--footer bottom copyright start-->
<div class="footer-bottom py-3 gray-light-bg">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-lg-7">
                <div class="copyright-wrap small-text">
                    <p class="mb-0">&copy; ThemeTags Design Agency, All rights reserved</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-5">
                <div class="terms-policy-wrap text-lg-right text-md-right text-left">
                    <ul class="list-inline">
                        <li class="list-inline-item"><a class="small-text" href="#">Terms</a></li>
                        <li class="list-inline-item"><a class="small-text" href="#">Security</a></li>
                        <li class="list-inline-item"><a class="small-text" href="#">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!--footer bottom copyright end-->
<!--footer section end-->
<!--scroll bottom to top button start-->
<div class="scroll-top scroll-to-target primary-bg text-white" data-target="html">
    <span class="fas fa-hand-point-up"></span>
</div>
<!--scroll bottom to top button end-->
<!--build:js-->
<script src="{{ asset('landing_page/assets/js/vendors/jquery-3.5.1.min.js') }}"></script>
<script src="{{ asset('landing_page/assets/js/vendors/popper.min.js') }}"></script>
<script src="{{ asset('landing_page/assets/js/vendors/bootstrap.min-rtl.js') }}"></script>
<script src="{{ asset('landing_page/assets/js/vendors/jquery.easing.min.js') }}"></script>
<script src="{{ asset('landing_page/assets/js/vendors/owl.carousel.min.js') }}"></script>
<script src="{{ asset('landing_page/assets/js/vendors/countdown.min.js') }}"></script>
<script src="{{ asset('landing_page/assets/js/vendors/jquery.waypoints.min.js') }}"></script>
<script src="{{ asset('landing_page/assets/js/vendors/jquery.rcounterup.js') }}"></script>
<script src="{{ asset('landing_page/assets/js/vendors/magnific-popup.min.js') }}"></script>
<script src="{{ asset('landing_page/assets/js/vendors/validator.min.js') }}"></script>
<script src="{{ asset('landing_page/assets/js/app.js') }}"></script>
<!--endbuild-->
</body>

</html>
