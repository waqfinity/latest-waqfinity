@php
    $contact = getContent('contact_us.content', true);
@endphp
<div class="header__top">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-5 mb-2 mb-sm-0">
                <div class="left d-flex align-items-center">
                    <a href="{{ route('contact') }}">
                        <i class="la la-phone-volume"></i> @lang('Help Center')
                    </a>
                    @if($general->multi_language)
                    <div class="language">
                        <i class="la la-globe-europe"></i>
                        <select id="language" class="langSel">
                            @foreach ($language as $lang)
                                <option value="{{ $lang->code }}" {{ session('lang') == $lang->code ? 'selected' : '' }}>{{ __($lang->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                </div>
            </div>
            <div class="col-7">
                @auth
                    <div class="right text-sm-end text-center dashboard">
                        <a class="{{ menuActive('user.home') }}" href="{{ route('user.home') }}"><i class="las la-tachometer-alt"></i> @lang('Dashboard')</a>
                    </div>
                @else
                    <div class="right text-sm-end text-center">
                        <a href="{{ route('user.login') }}"><i class="las la-sign-in-alt"></i> @lang('Login')</a>
                        <a href="{{ route('user.register') }}"><i class="las la-user-plus"></i> @lang('Register')</a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>

<header class="header__bottom">
    <div class="container">
        <nav class="navbar navbar-expand-xl p-0 align-items-center">
            <a class="site-logo site-title" href="{{ url('/') }}"><img src="{{ getImage(getFilePath('logoIcon') . '/logo.png') }}" alt="site-logo"><span class="logo-icon"><i class="flaticon-fire"></i></span></a>
            <button class="navbar-toggler ml-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="las la-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                @auth
                    @if (request()->routeIs('home') || request()->routeIs('campaign.index') || request()->routeIs('volunteer.index') || request()->routeIs('about')|| request()->routeIs(['success.story.*']))
                        <ul class="navbar-nav main-menu ms-auto">
                            @include($activeTemplate . 'partials.common_menus')
                        </ul>
                    @else
                        <ul class="navbar-nav main-menu ms-auto">
                            <li class="menu_has_children">
                                <a class="{{ menuActive('user.campaign.*') }}" href="{{ route('user.campaign.fundrise.all') }}">@lang('MY WAQF PAGES')</a>
                                <ul class="sub-menu">
                                    <li><a class="dropdown-item" href="{{ route('user.onboard') }}">@lang('CREATE WAQF PAGE')</a></li>
                                    <li><a class="dropdown-item" href="{{ route('user.campaign.fundrise.approved') }}">@lang('APPROVED WAQF PAGES')</a></li>
                                    <li><a class="dropdown-item" href="{{ route('user.campaign.fundrise.pending') }}">@lang('PENDING WAQF PAGES')</a></li>
                                    <li><a class="dropdown-item" href="{{ route('user.campaign.fundrise.rejected') }}">@lang('REJECTED WAQF PAGES')</a></li>
                                    <li><a class="dropdown-item" href="{{ route('user.campaign.fundrise.complete') }}">@lang('SUCCESSFUL WAQF PAGES')</a></li>
                                    <li><a class="dropdown-item" href="{{ route('user.campaign.fundrise.all') }}">@lang('ALL WAQF PAGES')</a></li>
                                </ul>
                            </li>
                            <li class="menu_has_children">
                                <a  href="{{ route('user.campaign.donation.my') }}">@lang('MY DONATIONS ')</a>
                                <ul class="sub-menu">
                                    <li><a class="dropdown-item" href="{{ route('user.transactions') }}">@lang('TRANSACTIONS LOG')</a></li>
                                    <li><a class="dropdown-item" href="{{ route('user.campaign.donation.received') }}">@lang('RECEIVED DONATIONS ')</a></li>
                                    <li><a class="dropdown-item" href="{{ route('user.campaign.donation.my') }}">@lang('MY DONATIONS ')</a></li>
                                </ul>
                            </li>
                            <!-- <li class="menu_has_children"><a class="{{ menuActive(['user.withdraw', 'user.withdraw.history']) }}" href="#0">@lang('WITHDRAW MONEY')</a>
                                <ul class="sub-menu">
                                    <li><a class="dropdown-item" href="{{ route('user.withdraw') }}">@lang('WITHDRAW MONEY')</a></li>
                                    <li><a class="dropdown-item" href="{{ route('user.withdraw.history') }}">@lang('WITHDRAW LOG')</a></li>
                                </ul>
                            </li> -->
                            <li class="menu_has_children"><a href="{{ route('ticket.index') }}">@lang('SUPPORT TICKET')</a>
                                <ul class="sub-menu">
                                    <li> <a class="dropdown-item" href="{{ route('ticket.open') }}">@lang('CREATE NEW')</a></li>
                                    <li><a class="dropdown-item" href="{{ route('ticket.index') }}">@lang('MY TICKETS')</a></li>
                                </ul>
                            </li>
                            <li class="menu_has_children"><a class="{{ menuActive(['user.change.password', 'user.twofactor', 'user.profile.setting']) }}" href="#0"> <i class="fa fa-user me-2"></i>{{ strtoupper(auth()->user()->username) }}</a>
                                <ul class="sub-menu">
                                    <li><a class="dropdown-item" href="{{ route('user.change.password') }}">@lang('CHANGE PASSWORD')</a></li>
                                    <li><a class="dropdown-item" href="{{ route('user.profile.setting') }}">@lang('PROFILE SETTING')</a></li>
                                    <li><a class="dropdown-item" href="{{ route('user.twofactor') }}">@lang('2FA SETTING')</a></li>
                                    <li><a class="dropdown-item" href="{{ route('user.logout') }}">@lang('LOGOUT')</a></li>
                                </ul>
                            </li>
                        </ul>
                    @endif
                @else
                <ul class="navbar-nav main-menu ms-auto">
                    @include($activeTemplate . 'partials.common_menus')
                </ul>
                <div class="nav-right">
                    <a href="{{ route('contact') }}" class="btn cmn-btn {{ menuActive('contact') }}">@lang('CONTACT')</a>
                </div>
                @endauth
            </div>
        </nav>
    </div>
</header>
