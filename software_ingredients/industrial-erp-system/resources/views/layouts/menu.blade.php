<li class="c-sidebar-nav-item {{ request()->routeIs('home') ? 'c-active' : '' }}">
    <a class="c-sidebar-nav-link" href="{{ route('home') }}">
        <svg class="neci-svg-icon custom-home-icon c-sidebar-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" class="home-base"></path>
            <path d="M9 22V12h6v10" class="home-door"></path>
            <path d="M14 8V4h3v2.6" class="home-chimney"></path>
            <circle cx="15.5" cy="2" r="0.5" class="home-smoke home-smoke-1"></circle>
            <circle cx="16.5" cy="0" r="1.5" class="home-smoke home-smoke-2"></circle>
        </svg> Home
    </a>
</li>

@can('access_products')
    <li
        class="c-sidebar-nav-item c-sidebar-nav-dropdown {{ request()->routeIs('products.*') || request()->routeIs('raw-materials.*') || request()->routeIs('product-categories.*') ? 'c-show' : '' }}">
        <a class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" href="#">
            <svg class="neci-svg-icon unbox-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path class="box-lid" d="M12 2L2 7l10 5 10-5-10-5z" />
                <path class="box-side-l" d="M2 7v10l10 5" />
                <path class="box-side-r" d="M22 7v10l-10 5" />
                <path class="box-inner" d="M12 12v10" />
                <path class="box-arrow" d="M12 7V3m-2 2l2-2 2 2" stroke-width="1.5" opacity="0"/>
            </svg> Products
        </a>
        <ul class="c-sidebar-nav-dropdown-items">
            @can('access_product_categories')
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('product-categories.*') ? 'c-active' : '' }}"
                        href="{{ route('product-categories.index') }}">
                        <svg class="neci-svg-icon c-sidebar-nav-icon neci-submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg> Categories
                    </a>
                </li>
            @endcan
            @can('create_products')
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('products.create') ? 'c-active' : '' }}"
                        href="{{ route('products.create') }}">
                        <svg class="neci-svg-icon c-sidebar-nav-icon neci-submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg> Create Product
                    </a>
                </li>
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('raw-materials.create') ? 'c-active' : '' }}"
                        href="{{ route('raw-materials.create') }}">
                        <svg class="neci-svg-icon c-sidebar-nav-icon neci-submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19h16"></path><path d="M6 19V8l6-4 6 4v11"></path><path d="M9 19v-5h6v5"></path><line x1="12" y1="7" x2="12" y2="11"></line><line x1="10" y1="9" x2="14" y2="9"></line></svg> Create Raw Material
                    </a>
                </li>
            @endcan
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{ request()->routeIs('products.index') ? 'c-active' : '' }}"
                    href="{{ route('products.index') }}">
                    <svg class="neci-svg-icon c-sidebar-nav-icon neci-submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg> All Products
                </a>
            </li>
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{ request()->routeIs('raw-materials.index') ? 'c-active' : '' }}"
                    href="{{ route('raw-materials.index') }}">
                    <svg class="neci-svg-icon c-sidebar-nav-icon neci-submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 20h18"></path><path d="M5 20V9l7-5 7 5v11"></path><path d="M9 20v-6h6v6"></path><path d="M8 10h8"></path></svg> All Raw Materials
                </a>
            </li>
            @can('print_barcodes')
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('barcode.print') ? 'c-active' : '' }}"
                        href="{{ route('barcode.print') }}">
                        <svg class="neci-svg-icon c-sidebar-nav-icon neci-submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7V4h3M20 7V4h-3M4 17v3h3M20 17v3h-3M7 9h2v6H7zM11 9h2v6h-2zM15 9h2v6h-2z"></path></svg> Print Barcode
                    </a>
                </li>
            @endcan
        </ul>
    </li>
@endcan

@can('access_adjustments')
    <li class="c-sidebar-nav-item c-sidebar-nav-dropdown {{ request()->routeIs('adjustments.*') ? 'c-show' : '' }}">
        <a class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" href="#">
            <svg class="neci-svg-icon custom-adj-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="4" y1="21" x2="4" y2="14" class="adj-track"></line>
                <line x1="4" y1="10" x2="4" y2="3" class="adj-track"></line>
                <line x1="12" y1="21" x2="12" y2="12" class="adj-track"></line>
                <line x1="12" y1="8" x2="12" y2="3" class="adj-track"></line>
                <line x1="20" y1="21" x2="20" y2="16" class="adj-track"></line>
                <line x1="20" y1="12" x2="20" y2="3" class="adj-track"></line>
                <line x1="1" y1="14" x2="7" y2="14" class="adj-knob adj-knob-1"></line>
                <line x1="9" y1="8" x2="15" y2="8" class="adj-knob adj-knob-2"></line>
                <line x1="17" y1="16" x2="23" y2="16" class="adj-knob adj-knob-3"></line>
            </svg> Stock Adjustments
        </a>
        <ul class="c-sidebar-nav-dropdown-items">
            @can('create_adjustments')
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('adjustments.create') ? 'c-active' : '' }}"
                        href="{{ route('adjustments.create') }}">
                        <svg class="neci-svg-icon c-sidebar-nav-icon neci-submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg> Create Adjustment
                    </a>
                </li>
            @endcan
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{ request()->routeIs('adjustments.index') ? 'c-active' : '' }}"
                    href="{{ route('adjustments.index') }}">
                    <svg class="neci-svg-icon c-sidebar-nav-icon neci-submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect><path d="M9 14h6M9 10h6M9 18h6"></path></svg> All Adjustments
                </a>
            </li>
        </ul>
    </li>
@endcan

@can('access_quotations')
    <li class="c-sidebar-nav-item c-sidebar-nav-dropdown {{ request()->routeIs('quotations.*') ? 'c-show' : '' }}">
        <a class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" href="#">
            <svg class="neci-svg-icon custom-quote-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13" class="quote-line-1"></line>
                <line x1="16" y1="17" x2="8" y2="17" class="quote-line-2"></line>
                <polyline points="10 9 9 9 8 9" class="quote-line-3"></polyline>
            </svg> Quotations
        </a>
        <ul class="c-sidebar-nav-dropdown-items">
            @can('create_adjustments')
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('quotations.create') ? 'c-active' : '' }}"
                        href="{{ route('quotations.create') }}">
                        <svg class="neci-svg-icon c-sidebar-nav-icon neci-submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg> Create Quotation
                    </a>
                </li>
            @endcan
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{ request()->routeIs('quotations.index') ? 'c-active' : '' }}"
                    href="{{ route('quotations.index') }}">
                    <svg class="neci-svg-icon c-sidebar-nav-icon neci-submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg> All Quotations
                </a>
            </li>
        </ul>
    </li>
@endcan

@can('access_manufacturing')
    <li
        class="c-sidebar-nav-item c-sidebar-nav-dropdown {{ request()->routeIs('production-batches.*') || request()->routeIs('conversion-expenses.*') ? 'c-show' : '' }}">
        <a class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" href="#">
            <svg class="neci-svg-icon custom-mfg-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path class="mfg-body" d="M3 21V9l5 3V9l5 3V5h8v16H3z"></path>
                <path class="mfg-door" d="M7 21v-4h3v4"></path>
                <path class="mfg-window" d="M14 17h3v4"></path>
                <path class="mfg-smoke mfg-smoke-1" d="M15 5c1-1 1-2 0-3"></path>
                <path class="mfg-smoke mfg-smoke-2" d="M18 5c1-1 1-2 0-3"></path>
            </svg> Manufacturing
        </a>
        <ul class="c-sidebar-nav-dropdown-items">
            @can('create_production_batches')
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('production-batches.create') ? 'c-active' : '' }}"
                        href="{{ route('production-batches.create') }}">
                        <svg class="neci-svg-icon c-sidebar-nav-icon neci-submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21V9l5 3V9l5 3V5h8v16H3z"></path><path d="M7 21v-4h3v4"></path><line x1="16" y1="10" x2="16" y2="16"></line><line x1="13" y1="13" x2="19" y2="13"></line></svg> Create Batch
                    </a>
                </li>
            @endcan
            <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('production-batches.index') || request()->routeIs('production-batches.show') || request()->routeIs('conversion-expenses.*') ? 'c-active' : '' }}"
                    href="{{ route('production-batches.index') }}">
                    <svg class="neci-svg-icon c-sidebar-nav-icon neci-submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21V9l5 3V9l5 3V5h8v16H3z"></path><path d="M7 21v-4h3v4"></path><path d="M14 17h3v4"></path></svg> All Batches
                </a>
            </li>
        </ul>
    </li>
@endcan

@can('access_purchases')
    <li
        class="c-sidebar-nav-item c-sidebar-nav-dropdown {{ request()->routeIs('purchases.*') || request()->routeIs('purchase-payments*') ? 'c-show' : '' }}">
        <a class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" href="#">
            <svg class="neci-svg-icon custom-purc-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path class="dash-wave" d="M2 10h-2 M4 14h-3 M2 18h-2" stroke-width="2" stroke-linecap="round" opacity="0"></path>
                <g class="cart-wrapper">
                    <circle cx="9" cy="21" r="1" class="cart-wheel"></circle>
                    <circle cx="20" cy="21" r="1" class="cart-wheel"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" class="cart-body"></path>
                </g>
            </svg> Purchases
        </a>
        @can('create_purchases')
            <ul class="c-sidebar-nav-dropdown-items">
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('purchases.create') ? 'c-active' : '' }}"
                        href="{{ route('purchases.create') }}">
                        <svg class="neci-svg-icon c-sidebar-nav-icon neci-submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path><line x1="12" y1="11" x2="12" y2="17"></line><line x1="9" y1="14" x2="15" y2="14"></line></svg> Create Purchase
                    </a>
                </li>
            </ul>
        @endcan
        <ul class="c-sidebar-nav-dropdown-items">
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{ request()->routeIs('purchases.index') ? 'c-active' : '' }}"
                    href="{{ route('purchases.index') }}">
                    <svg class="neci-svg-icon c-sidebar-nav-icon neci-submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path><polyline points="9 14 11 16 15 12"></polyline></svg> All Purchases
                </a>
            </li>
        </ul>
    </li>
@endcan

@can('access_purchase_returns')
    <li
        class="c-sidebar-nav-item c-sidebar-nav-dropdown {{ request()->routeIs('purchase-returns.*') || request()->routeIs('purchase-return-payments.*') ? 'c-show' : '' }}">
        <a class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" href="#">
            <svg class="neci-svg-icon custom-pret-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path class="dash-wave" d="M22 10h2 M20 14h3 M22 18h2" stroke-width="2" stroke-linecap="round" opacity="0"></path>
                <g class="cart-wrapper">
                    <g transform="translate(24, 0) scale(-1, 1)">
                        <circle cx="9" cy="21" r="1" class="cart-wheel"></circle>
                        <circle cx="20" cy="21" r="1" class="cart-wheel"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" class="cart-body"></path>
                    </g>
                </g>
            </svg> Purchase Returns
        </a>
        @can('create_purchase_returns')
            <ul class="c-sidebar-nav-dropdown-items">
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('purchase-returns.create') ? 'c-active' : '' }}"
                        href="{{ route('purchase-returns.create') }}">
                        <svg class="neci-svg-icon c-sidebar-nav-icon neci-submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 8 8 12 12 16"></polyline><line x1="16" y1="12" x2="8" y2="12"></line></svg> Create Purchase Return
                    </a>
                </li>
            </ul>
        @endcan
        <ul class="c-sidebar-nav-dropdown-items">
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{ request()->routeIs('purchase-returns.index') ? 'c-active' : '' }}"
                    href="{{ route('purchase-returns.index') }}">
                    <svg class="neci-svg-icon c-sidebar-nav-icon neci-submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 8 9 4"></polyline><line x1="13" y1="6" x2="21" y2="6"></line><polyline points="3 12 5 14 9 10"></polyline><line x1="13" y1="12" x2="21" y2="12"></line><polyline points="3 18 5 20 9 16"></polyline><line x1="13" y1="18" x2="21" y2="18"></line></svg> All Purchase Returns
                </a>
            </li>
        </ul>
    </li>
@endcan

@can('access_sales')
    <li
        class="c-sidebar-nav-item c-sidebar-nav-dropdown {{ request()->routeIs('sales.*') || request()->routeIs('sale-payments*') ? 'c-show' : '' }}">
        <a class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" href="#">
            <svg class="neci-svg-icon custom-sale-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path class="dash-wave" d="M22 10h2 M20 14h3 M22 18h2" stroke-width="2" stroke-linecap="round" opacity="0"></path>
                <g class="truck-wrapper">
                    <g transform="translate(24, 0) scale(-1, 1)">
                        <rect x="1" y="3" width="15" height="13" class="truck-body"></rect>
                        <polygon points="16 8 20 8 23 11 23 16 16 16 16 8" class="truck-cab"></polygon>
                        <circle cx="5.5" cy="18.5" r="2.5" class="truck-wheel"></circle>
                        <circle cx="18.5" cy="18.5" r="2.5" class="truck-wheel"></circle>
                    </g>
                </g>
            </svg> Sales
        </a>
        @can('create_sales')
            <ul class="c-sidebar-nav-dropdown-items">
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('sales.create') ? 'c-active' : '' }}"
                        href="{{ route('sales.create') }}">
                        <svg class="neci-svg-icon c-sidebar-nav-icon neci-submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 22L20 20L18 22L16 20L14 22L12 20L10 22L8 20L6 22L4 20L2 22V2C2 2 3 2 4 2L6 4L8 2L10 4L12 2L14 4L16 2L18 4L20 2L22 4V22Z"></path><line x1="8" y1="10" x2="16" y2="10"></line><line x1="8" y1="14" x2="16" y2="14"></line></svg> Create Sale
                    </a>
                </li>
            </ul>
        @endcan
        <ul class="c-sidebar-nav-dropdown-items">
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{ request()->routeIs('sales.index') ? 'c-active' : '' }}"
                    href="{{ route('sales.index') }}">
                    <svg class="neci-svg-icon c-sidebar-nav-icon neci-submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line><line x1="8" y1="14" x2="16" y2="14"></line><line x1="8" y1="18" x2="16" y2="18"></line></svg> All Sales
                </a>
            </li>
        </ul>
    </li>
@endcan

@can('access_sale_returns')
    <li
        class="c-sidebar-nav-item c-sidebar-nav-dropdown {{ request()->routeIs('sale-returns.*') || request()->routeIs('sale-return-payments.*') ? 'c-show' : '' }}">
        <a class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" href="#">
            <svg class="neci-svg-icon custom-sret-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path class="dash-wave" d="M2 10h-2 M4 14h-3 M2 18h-2" stroke-width="2" stroke-linecap="round" opacity="0"></path>
                <g class="truck-wrapper">
                    <rect x="1" y="3" width="15" height="13" class="truck-body"></rect>
                    <polygon points="16 8 20 8 23 11 23 16 16 16 16 8" class="truck-cab"></polygon>
                    <circle cx="5.5" cy="18.5" r="2.5" class="truck-wheel"></circle>
                    <circle cx="18.5" cy="18.5" r="2.5" class="truck-wheel"></circle>
                </g>
            </svg> Sale Returns
        </a>
        @can('create_sale_returns')
            <ul class="c-sidebar-nav-dropdown-items">
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('sale-returns.create') ? 'c-active' : '' }}"
                        href="{{ route('sale-returns.create') }}">
                        <svg class="neci-svg-icon c-sidebar-nav-icon neci-submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><path d="M9 12h6"></path><path d="M12 9l-3 3 3 3"></path></svg> Create Sale Return
                    </a>
                </li>
            </ul>
        @endcan
        <ul class="c-sidebar-nav-dropdown-items">
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{ request()->routeIs('sale-returns.index') ? 'c-active' : '' }}"
                    href="{{ route('sale-returns.index') }}">
                    <svg class="neci-svg-icon c-sidebar-nav-icon neci-submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line><line x1="8" y1="14" x2="16" y2="14"></line><line x1="8" y1="18" x2="16" y2="18"></line></svg> All Sale Returns
                </a>
            </li>
        </ul>
    </li>
@endcan

@can('access_incomes')
    <li
        class="c-sidebar-nav-item c-sidebar-nav-dropdown {{ request()->routeIs('incomes.*') || request()->routeIs('income-categories.*') ? 'c-show' : '' }}">
        <a class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" href="#">
            <svg class="neci-svg-icon custom-earn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                {{-- Money bag body --}}
                <g class="earn-bag">
                    {{-- neck / tie --}}
                    <path class="earn-neck" d="M9 8 Q7 6 8 4 Q10 2 12 3 Q14 2 16 4 Q17 6 15 8 Z"/>
                    {{-- sack --}}
                    <path class="earn-sack" d="M7 8 Q3 9 3 14 Q3 21 12 21 Q21 21 21 14 Q21 9 17 8 Z"/>
                    {{-- dollar sign vertical --}}
                    <line class="earn-dollar-v" x1="12" y1="11.5" x2="12" y2="18.5"/>
                    {{-- dollar sign curve top --}}
                    <path class="earn-dollar-s" d="M14.2 13 Q14.2 11.5 12 11.5 Q9.8 11.5 9.8 13 Q9.8 14.5 12 14.5 Q14.2 14.5 14.2 16 Q14.2 17.5 12 17.5 Q9.8 17.5 9.8 16"/>
                </g>
                {{-- three coins that fly out on hover --}}
                <circle class="earn-coin-1" cx="12" cy="6"  r="1.1" stroke="none"/>
                <circle class="earn-coin-2" cx="9"  cy="7"  r="0.9" stroke="none"/>
                <circle class="earn-coin-3" cx="15" cy="7"  r="0.9" stroke="none"/>
            </svg> Earnings
        </a>
        <ul class="c-sidebar-nav-dropdown-items">
            @can('access_income_categories')
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('income-categories.*') ? 'c-active' : '' }}"
                        href="{{ route('income-categories.index') }}">
                        <svg class="neci-svg-icon c-sidebar-nav-icon neci-submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg> Categories
                    </a>
                </li>
            @endcan
            @can('create_incomes')
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('incomes.create') ? 'c-active' : '' }}"
                        href="{{ route('incomes.create') }}">
                        <svg class="neci-svg-icon c-sidebar-nav-icon neci-submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg> Create Income
                    </a>
                </li>
            @endcan
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{ request()->routeIs('incomes.index') ? 'c-active' : '' }}"
                    href="{{ route('incomes.index') }}">
                    <svg class="neci-svg-icon c-sidebar-nav-icon neci-submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line><line x1="8" y1="14" x2="16" y2="14"></line><line x1="8" y1="18" x2="16" y2="18"></line></svg> All Incomes
                </a>
            </li>
        </ul>
    </li>
@endcan

@can('access_expenses')
    <li
        class="c-sidebar-nav-item c-sidebar-nav-dropdown {{ request()->routeIs('expenses.*') || request()->routeIs('expense-categories.*') || request()->routeIs('conversion-expenses.*') ? 'c-show' : '' }}">
        <a class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" href="#">
            <svg class="neci-svg-icon custom-exp-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 4h14a2 2 0 0 1 2 2v10" class="bill-back"></path>
                <rect x="2" y="8" width="16" height="12" rx="2" class="bill-front"></rect>
                <circle cx="10" cy="14" r="2" class="bill-front"></circle>
            </svg> Expenses
        </a>
        <ul class="c-sidebar-nav-dropdown-items">
            @can('access_expense_categories')
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('expense-categories.*') ? 'c-active' : '' }}"
                        href="{{ route('expense-categories.index') }}">
                        <svg class="neci-svg-icon c-sidebar-nav-icon neci-submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg> Categories
                    </a>
                </li>
            @endcan
            @can('create_expenses')
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('expenses.create') ? 'c-active' : '' }}"
                        href="{{ route('expenses.create') }}">
                        <svg class="neci-svg-icon c-sidebar-nav-icon neci-submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg> Create Expense
                    </a>
                </li>
                @can('access_manufacturing')
                    <li class="c-sidebar-nav-item">
                        <a class="c-sidebar-nav-link {{ request()->routeIs('conversion-expenses.create') ? 'c-active' : '' }}"
                            href="{{ route('conversion-expenses.create') }}">
                            <svg class="neci-svg-icon c-sidebar-nav-icon neci-submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21V9l5 3V9l5 3V5h8v16H3z"></path><path d="M7 21v-4h3v4"></path><circle cx="18" cy="7" r="3"></circle><path d="M18 5v4M16 7h4"></path></svg> Create Conversion Expense
                        </a>
                    </li>
                @endcan
            @endcan
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{ request()->routeIs('expenses.index') ? 'c-active' : '' }}"
                    href="{{ route('expenses.index') }}">
                    <svg class="neci-svg-icon c-sidebar-nav-icon neci-submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line><line x1="8" y1="14" x2="16" y2="14"></line><line x1="8" y1="18" x2="16" y2="18"></line></svg> All Expenses
                </a>
            </li>
        </ul>
    </li>
@endcan

@can('access_hrm')
    <li
        class="c-sidebar-nav-item c-sidebar-nav-dropdown {{ request()->routeIs('employees.*') || request()->routeIs('overtimes.*') || request()->routeIs('bonuses.*') || request()->routeIs('payrolls.*') || request()->routeIs('attendances.*') ? 'c-show' : '' }}">
        <a class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" href="#">
            <svg class="neci-svg-icon custom-hrm-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                {{-- Briefcase (left side) --}}
                <g class="hrm-bag-group">
                    {{-- handle --}}
                    <path d="M7 8 V6 Q7 4 9 4 H11 Q13 4 13 6 V8"/>
                    {{-- body --}}
                    <rect x="4" y="8" width="12" height="9" rx="1.5"/>
                    {{-- centre strap --}}
                    <line x1="4" y1="12" x2="16" y2="12"/>
                </g>
                {{-- Clock (right side, centre at 20,15) --}}
                <circle class="hrm-clock-ring" cx="20" cy="15" r="3.5"/>
                {{-- minute hand: from centre (20,15) up to (20,12) --}}
                <line class="hrm-minute" x1="20" y1="15" x2="20" y2="12"/>
                {{-- hour hand: from centre (20,15) right to (22,15) --}}
                <line class="hrm-hour"   x1="20" y1="15" x2="22" y2="15"/>
                {{-- pivot dot --}}
                <circle class="hrm-clock-dot" cx="20" cy="15" r="0.6" stroke="none"/>
            </svg> HRM
        </a>
        <ul class="c-sidebar-nav-dropdown-items">
            @can('access_employees')
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('employees.*') ? 'c-active' : '' }}"
                        href="{{ route('employees.index') }}">
                        <svg class="neci-svg-icon c-sidebar-nav-icon neci-submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> Employees
                    </a>
                </li>
            @endcan
            @can('access_overtimes')
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('overtimes.*') ? 'c-active' : '' }}"
                        href="{{ route('overtimes.index') }}">
                        <svg class="neci-svg-icon c-sidebar-nav-icon neci-submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg> Overtime
                    </a>
                </li>
            @endcan
            @can('access_attendances')
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('attendances.*') ? 'c-active' : '' }}"
                        href="{{ route('attendances.index') }}">
                        <svg class="neci-svg-icon c-sidebar-nav-icon neci-submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line><line x1="8" y1="14" x2="16" y2="14"></line><line x1="8" y1="18" x2="16" y2="18"></line></svg> Attendance
                    </a>
                </li>
            @endcan
            @can('access_bonuses')
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('bonuses.*') ? 'c-active' : '' }}"
                        href="{{ route('bonuses.index') }}">
                        <svg class="neci-svg-icon c-sidebar-nav-icon neci-submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 12 20 22 4 22 4 12"></polyline><rect x="2" y="7" width="20" height="5"></rect><line x1="12" y1="22" x2="12" y2="7"></line><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"></path><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"></path></svg> Bonuses
                    </a>
                </li>
            @endcan
            @can('access_payrolls')
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('payrolls.*') ? 'c-active' : '' }}"
                        href="{{ route('payrolls.index') }}">
                        <svg class="neci-svg-icon c-sidebar-nav-icon neci-submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line><line x1="8" y1="14" x2="16" y2="14"></line></svg> Payroll / Salary
                    </a>
                </li>
            @endcan
        </ul>
    </li>
@endcan

<li class="c-sidebar-nav-item c-sidebar-nav-dropdown {{ request()->routeIs('party-payments.*') ? 'c-show' : '' }}">
    <a class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" href="#">
        <svg class="neci-svg-icon custom-payment-wallet-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path class="wallet-note" d="M7 6h10l-1-3H8L7 6z"></path>
            <path class="wallet-body" d="M4 7h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2z"></path>
            <path class="wallet-pocket" d="M15 12h7v5h-7a2.5 2.5 0 0 1 0-5z"></path>
            <circle class="wallet-dot" cx="16.5" cy="14.5" r=".7"></circle>
            <path class="wallet-note-line" d="M10 4.5h3"></path>
        </svg> Payments
    </a>
    <ul class="c-sidebar-nav-dropdown-items">
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ request()->routeIs('party-payments.create') ? 'c-active' : '' }}" href="{{ route('party-payments.create') }}">
                <svg class="neci-svg-icon c-sidebar-nav-icon neci-submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg> Payment Entries
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ request()->routeIs('party-payments.index') || request()->routeIs('party-payments.show') || request()->routeIs('party-payments.edit') ? 'c-active' : '' }}" href="{{ route('party-payments.index') }}">
                <svg class="neci-svg-icon c-sidebar-nav-icon neci-submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h16"></path><circle cx="7" cy="6" r="1"></circle><circle cx="7" cy="12" r="1"></circle><circle cx="7" cy="18" r="1"></circle></svg> All Payments
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ request()->routeIs('party-payments.ledger') ? 'c-active' : '' }}" href="{{ route('party-payments.ledger') }}">
                <svg class="neci-svg-icon c-sidebar-nav-icon neci-submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M4 4.5A2.5 2.5 0 0 1 6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15z"></path></svg> Payment Ledger
            </a>
        </li>
    </ul>
</li>

@if(auth()->user()->can('access_customers') || auth()->user()->can('access_suppliers'))
    <li
        class="c-sidebar-nav-item c-sidebar-nav-dropdown {{ request()->routeIs('customers.*') || request()->routeIs('suppliers.*') ? 'c-show' : '' }}">
        <a class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" href="#">
            <svg class="neci-svg-icon custom-party-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <g class="person-1">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" class="person-body-1"></path>
                    <circle cx="9" cy="7" r="4" class="person-head-1"></circle>
                </g>
                <g class="person-2">
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87" class="person-body-2"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75" class="person-head-2"></path>
                </g>
            </svg> Parties
        </a>
        <ul class="c-sidebar-nav-dropdown-items">
            @can('access_customers')
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('customers.*') ? 'c-active' : '' }}"
                        href="{{ route('customers.index') }}">
                        <svg class="neci-svg-icon c-sidebar-nav-icon neci-submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> Customers
                    </a>
                </li>
            @endcan
            @can('access_suppliers')
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('suppliers.*') ? 'c-active' : '' }}"
                        href="{{ route('suppliers.index') }}">
                        <i class="bi bi-shop c-sidebar-nav-icon neci-submenu-icon"></i> Suppliers
                    </a>
                </li>
            @endcan
        </ul>
    </li>
@endif

@can('access_reports')
    <li class="c-sidebar-nav-item c-sidebar-nav-dropdown {{ request()->routeIs('*-report.index') ? 'c-show' : '' }}">
        <a class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" href="#">
            <svg class="neci-svg-icon report-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="18" y="3" width="4" height="18" class="bar-r"></rect>
                <rect x="10" y="8" width="4" height="13" class="bar-m"></rect>
                <rect x="2" y="13" width="4" height="8" class="bar-l"></rect>
            </svg> Reports
        </a>
        <ul class="c-sidebar-nav-dropdown-items">
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{ request()->routeIs('profit-loss-report.index') ? 'c-active' : '' }}"
                    href="{{ route('profit-loss-report.index') }}">
                    <svg class="neci-svg-icon c-sidebar-nav-icon neci-submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg> Profit / Loss Report
                </a>
            </li>
            @can('access_manufacturing')
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('production-report.index') ? 'c-active' : '' }}"
                        href="{{ route('production-report.index') }}">
                        <svg class="neci-svg-icon c-sidebar-nav-icon neci-submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21V9l5 3V9l5 3V5h8v16H3z"></path><path d="M7 21v-4h3v4"></path></svg> Production Report
                    </a>
                </li>
            @endcan
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{ request()->routeIs('payments-report.index') ? 'c-active' : '' }}"
                    href="{{ route('payments-report.index') }}">
                    <svg class="neci-svg-icon c-sidebar-nav-icon neci-submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg> Payments Report
                </a>
            </li>
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{ request()->routeIs('payable-report.index') ? 'c-active' : '' }}"
                    href="{{ route('payable-report.index') }}">
                    <svg class="neci-svg-icon c-sidebar-nav-icon neci-submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 17L17 7"></path><path d="M8 7h9v9"></path><rect x="3" y="3" width="18" height="18" rx="2"></rect></svg> Payable Report
                </a>
            </li>
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{ request()->routeIs('receivable-report.index') ? 'c-active' : '' }}"
                    href="{{ route('receivable-report.index') }}">
                    <svg class="neci-svg-icon c-sidebar-nav-icon neci-submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 7L7 17"></path><path d="M16 17H7V8"></path><rect x="3" y="3" width="18" height="18" rx="2"></rect></svg> Receivable Report
                </a>
            </li>
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{ request()->routeIs('sales-report.index') ? 'c-active' : '' }}"
                    href="{{ route('sales-report.index') }}">
                    <svg class="neci-svg-icon c-sidebar-nav-icon neci-submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 22L20 20L18 22L16 20L14 22L12 20L10 22L8 20L6 22L4 20L2 22V2C2 2 3 2 4 2L6 4L8 2L10 4L12 2L14 4L16 2L18 4L20 2L22 4V22Z"></path><line x1="8" y1="10" x2="16" y2="10"></line><line x1="8" y1="14" x2="16" y2="14"></line></svg> Sales Report
                </a>
            </li>
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{ request()->routeIs('purchases-report.index') ? 'c-active' : '' }}"
                    href="{{ route('purchases-report.index') }}">
                    <svg class="neci-svg-icon c-sidebar-nav-icon neci-submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg> Purchases Report
                </a>
            </li>
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{ request()->routeIs('sales-return-report.index') ? 'c-active' : '' }}"
                    href="{{ route('sales-return-report.index') }}">
                    <svg class="neci-svg-icon c-sidebar-nav-icon neci-submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 14L4 9l5-5"></path><path d="M4 9h10.5a5.5 5.5 0 0 1 5.5 5.5v0a5.5 5.5 0 0 1-5.5 5.5H11"></path></svg> Sales Return Report
                </a>
            </li>
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{ request()->routeIs('purchases-return-report.index') ? 'c-active' : '' }}"
                    href="{{ route('purchases-return-report.index') }}">
                    <svg class="neci-svg-icon c-sidebar-nav-icon neci-submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 14l5-5-5-5"></path><path d="M20 9H9.5A5.5 5.5 0 0 0 4 14.5v0A5.5 5.5 0 0 0 9.5 20H13"></path></svg> Purchases Return Report
                </a>
            </li>
        </ul>
    </li>
@endcan

@can('access_user_management')
    <li class="c-sidebar-nav-item c-sidebar-nav-dropdown {{ request()->routeIs('roles*') ? 'c-show' : '' }}">
        <a class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" href="#">
            <svg class="neci-svg-icon custom-usr-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" class="shield-body"></path>
                <rect x="9" y="11" width="6" height="5" rx="1" class="lock-body"></rect>
                <path d="M10 11V9a2 2 0 1 1 4 0v2" class="lock-shackle"></path>
            </svg> User Management
        </a>
        <ul class="c-sidebar-nav-dropdown-items">
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{ request()->routeIs('users.create') ? 'c-active' : '' }}"
                    href="{{ route('users.create') }}">
                    <svg class="neci-svg-icon c-sidebar-nav-icon neci-submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg> Create User
                </a>
            </li>
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{ request()->routeIs('users*') ? 'c-active' : '' }}"
                    href="{{ route('users.index') }}">
                    <svg class="neci-svg-icon c-sidebar-nav-icon neci-submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg> All Users
                </a>
            </li>
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{ request()->routeIs('roles*') ? 'c-active' : '' }}"
                    href="{{ route('roles.index') }}">
                    <svg class="neci-svg-icon c-sidebar-nav-icon neci-submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"></path></svg> Roles & Permissions
                </a>
            </li>
        </ul>
    </li>
@endcan

@if(auth()->user()->can('access_units') || auth()->user()->can('access_currencies') || auth()->user()->can('access_settings'))
    <li
        class="c-sidebar-nav-item c-sidebar-nav-dropdown {{ request()->routeIs('currencies*') || request()->routeIs('units*') || request()->routeIs('settings*') ? 'c-show' : '' }}">
        <a class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" href="#">
            <svg class="neci-svg-icon custom-set-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="3" class="gear-center"></circle>
                <path class="gear-teeth" d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
            </svg> Settings
        </a>
        @can('access_units')
            <ul class="c-sidebar-nav-dropdown-items">
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('units*') ? 'c-active' : '' }}"
                        href="{{ route('units.index') }}">
                        <svg class="neci-svg-icon c-sidebar-nav-icon neci-submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="2" width="16" height="20" rx="2"></rect><line x1="8" y1="6" x2="16" y2="6"></line><line x1="16" y1="14" x2="16" y2="18"></line><path d="M8 10h.01M12 10h.01M16 10h.01M8 14h.01M12 14h.01M8 18h.01M12 18h.01"></path></svg> Units
                    </a>
                </li>
            </ul>
        @endcan
        @can('access_currencies')
            <ul class="c-sidebar-nav-dropdown-items">
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('currencies*') ? 'c-active' : '' }}"
                        href="{{ route('currencies.index') }}">
                        <svg class="neci-svg-icon c-sidebar-nav-icon neci-submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg> Currencies
                    </a>
                </li>
            </ul>
        @endcan
        @can('access_settings')
            <ul class="c-sidebar-nav-dropdown-items">
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('settings*') ? 'c-active' : '' }}"
                        href="{{ route('settings.index') }}">
                        <svg class="neci-svg-icon c-sidebar-nav-icon neci-submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                        </svg> System Settings
                    </a>
                </li>
            </ul>
        @endcan
    </li>
@endif

{{-- Security Layer: Only displays the main category block if the user has permission to at least one submenu tool --}}
@if(auth()->user()->can('access_backup') || auth()->user()->can('access_restore'))
    {{-- System Design Integration: 'c-show' class automatically expands the dropdown menu if active inside a sub-route --}}
    <li
        class="c-sidebar-nav-item c-sidebar-nav-dropdown {{ request()->routeIs('backup*') || request()->routeIs('restore*') ? 'c-show' : '' }}">
        <a class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" href="#">
            <svg class="neci-svg-icon custom-sys-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="2" width="20" height="8" rx="2" ry="2" class="sys-rack"></rect>
                <rect x="2" y="14" width="20" height="8" rx="2" ry="2" class="sys-rack"></rect>
                <circle cx="6" cy="6" r="1" class="sys-light sys-light-1"></circle>
                <circle cx="6" cy="18" r="1" class="sys-light sys-light-2"></circle>
            </svg> System Utilities
        </a>
        <ul class="c-sidebar-nav-dropdown-items">
            {{-- Isolate Submenu 1: Standalone Backup Utility Control --}}
            @can('access_backup')
                <li class="c-sidebar-nav-item">
                    {{-- Highlights the menu item green/blue when viewing the page --}}
                    <a class="c-sidebar-nav-link {{ request()->routeIs('backup.index') ? 'c-active' : '' }}"
                        href="{{ route('backup.index') }}">
                        <i class="bi bi-cloud-arrow-down c-sidebar-nav-icon neci-submenu-icon"></i> Backup
                    </a>
                </li>
            @endcan

            {{-- Isolate Submenu 2: Standalone Restoration Utility Control --}}
            @can('access_restore')
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('restore.index') ? 'c-active' : '' }}"
                        href="{{ route('restore.index') }}">
                        <i class="bi bi-cloud-arrow-up c-sidebar-nav-icon neci-submenu-icon"></i> Restore System
                    </a>
                </li>
            @endcan

        </ul>
    </li>
@endif
