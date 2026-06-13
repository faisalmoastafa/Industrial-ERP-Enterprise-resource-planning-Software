<div class="c-sidebar c-sidebar-dark c-sidebar-fixed c-sidebar-lg-show {{ request()->routeIs('app.pos.*') ? 'neci-sidebar-collapsed' : '' }}" id="sidebar">
    <div class="c-sidebar-brand d-md-down-none">
        <a href="{{ route('home') }}" class="neci-brand-logo-link">
            <img class="c-sidebar-brand-full neci-brand-logo neci-brand-logo--sidebar-full"
                 src="{{ settings()->getLogoTransparentUrl() }}"
                 alt="{{ settings()->company_name }}">
            <img class="c-sidebar-brand-minimized neci-brand-logo neci-brand-logo--sidebar-mini"
                 src="{{ settings()->getLogoTransparentUrl() }}"
                 alt="{{ settings()->company_name }}">
        </a>
    </div>
    <ul class="c-sidebar-nav">
        @include('layouts.menu')
        <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
            <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
        </div>
        <div class="ps__rail-y" style="top: 0px; height: 692px; right: 0px;">
            <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 369px;"></div>
        </div>
    </ul>
</div>
