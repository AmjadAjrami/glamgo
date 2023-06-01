<!-- BEGIN: Header-->
<style>
    @if(app()->getLocale() == 'ar')
        #reviewers_count_label {
        text-align: right;
        clear: both;
        margin-right: 15px;
        float: right;
        margin-top: 7px !important;
    }

    #reviewers_count_span {
        display: block;
        overflow: hidden;
        padding: 0px 4px 0px 6px;
    }

    #reviewers_count {
        width: 35%;
    }

    @else
         #reviewers_count_label {
        text-align: left;
        clear: both;
        float: left;
        margin-right: 15px;
        margin-top: 7px !important;
    }

    #reviewers_count_span {
        display: block;
        overflow: hidden;
        padding: 0px 4px 0px 6px;
    }

    #reviewers_count {
        width: 35%;
    }

    @endif

    .dot {
        height: 10px;
        width: 10px;
        background-color: red;
        border-radius: 50%;
        display: inline-block;
        position: absolute;
        top: 12px;
        margin-right: 5px;
    }
</style>
<nav
    class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-light navbar-shadow container-xxl">
    <div class="navbar-container d-flex content">
        <div class="bookmark-wrapper d-flex align-items-center">
            <ul class="nav navbar-nav d-xl-none">
                <li class="nav-item"><a class="nav-link menu-toggle" href="#"><i class="ficon" data-feather="menu"></i></a>
                </li>
            </ul>
            <ul class="nav navbar-nav bookmark-icons">
                @php $unread = \App\Models\Chat::query()->where('provider_id', auth('artist')->id())->where('provider_type', 2)->where('provider_unread_messages', '>', 0)->count() > 0; @endphp

                <li class="nav-item d-none d-lg-block" style="margin-top: 8px;">
                    <a class="nav-link" href="{{ route('artist_chats.index') }}" data-bs-toggle="tooltip"
                       data-bs-placement="bottom" title="Chat">
                        <i class="ficon" data-feather="message-square"></i>
                    </a>
                    @if($unread)
                        <span class="dot"></span>
                    @endif
                </li>
            </ul>
        </div>
        <ul class="nav navbar-nav align-items-center ms-auto">
            <li class="nav-item dropdown dropdown-language">
                <a class="nav-link dropdown-toggle" id="dropdown-flag"
                   href="#" data-bs-toggle="dropdown" aria-haspopup="true"
                   aria-expanded="false">
                    <i class="flag-icon {{ app()->getLocale() == 'ar' ? 'flag-icon-qa' : 'flag-icon-us' }}"></i>
                    <span
                        class="selected-language">{{ app()->getLocale() == 'ar' ? __('common.Arabic') : __('common.English') }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-flag">
                    @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                        <a class="dropdown-item"
                           href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}"
                           data-language="{{ $localeCode }}">
                            <i class="flag-icon {{ $localeCode == 'ar' ? 'flag-icon-qa' : 'flag-icon-us' }}"></i> {{ $properties['native'] }}
                        </a>
                    @endforeach
                </div>
            </li>
            <li class="nav-item dropdown dropdown-notification me-25" ><a class="nav-link" href="#" id="notification_btn"
                                                                         data-bs-toggle="dropdown"><i class="ficon"
                                                                                                      data-feather="bell"></i><span
                        class="badge rounded-pill bg-danger badge-up" id="notifications_count"></span></a>
                <ul class="dropdown-menu dropdown-menu-media dropdown-menu-end">
                    <li class="dropdown-menu-header">
                        <div class="dropdown-header d-flex">
                            <h4 class="notification-title mb-0 me-auto">@lang('common.notifications')</h4>
                        </div>
                    </li>
                    <li class="scrollable-container media-list" id="notifications_list">

                    </li>
                </ul>
            </li>
            <li class="nav-item dropdown dropdown-user"><a class="nav-link dropdown-toggle dropdown-user-link"
                                                           id="dropdown-user" href="#" data-bs-toggle="dropdown"
                                                           aria-haspopup="true" aria-expanded="false">
                    <div class="user-nav d-sm-flex d-none"><span
                            class="user-name fw-bolder">{{ auth('artist')->user()->name }}</span><span
                            class="user-status">@lang('common.artist')</span></div>
                    <span class="avatar"><img class="round" src="{{ auth('artist')->user()->image }}" alt="avatar"
                                              height="40" width="40"><span class="avatar-status-online"></span></span>
                </a>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-user">
                    <a class="dropdown-item" href="{{ route('artist.logout') }}"><i class="me-50"
                                                                                    data-feather="power"></i> @lang('common.logout')
                    </a>
                </div>
            </li>
        </ul>
    </div>
</nav>
<!-- END: Header-->
