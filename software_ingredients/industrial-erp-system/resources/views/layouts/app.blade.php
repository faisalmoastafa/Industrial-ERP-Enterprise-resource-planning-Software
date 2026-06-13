<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title') || {{ settings()->app_title ?? config('app.name') }}</title>
    <meta content="NECI Development Team" name="author">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/favicon.png') }}">

    {{-- Resolve theme from cookie before any stylesheet paints to eliminate FOUC --}}
    <script>
        (function () {
            try {
                var color = localStorage.getItem('neci_dashboard_color') || 'blue';
                var mode  = localStorage.getItem('neci_dashboard_mode')  || 'light';
                var el    = document.documentElement;
                el.setAttribute('data-app-pre-color', color);
                el.setAttribute('data-app-pre-mode',  mode);
                el.setAttribute('data-app-pre-theme', color + '-' + mode);
            } catch (e) {}
        })();
    </script>

    @include('includes.main-css')
    
    <style>
    /* Minimized Sidebar Icon Centering Fix */
    body[data-app-theme] .c-sidebar.c-sidebar-minimized .c-sidebar-nav > .c-sidebar-nav-item > .c-sidebar-nav-link {
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        padding: 0 !important;
        width: 46px !important;
        height: 46px !important;
        margin: 4px auto !important;
    }
    body[data-app-theme] .c-sidebar.c-sidebar-minimized .c-sidebar-nav > .c-sidebar-nav-item > .c-sidebar-nav-link > .c-sidebar-nav-icon {
        margin: 0 !important;
        padding: 0 !important;
        display: block !important;
        width: 24px !important;
        height: 24px !important;
        flex: none !important;
    }
    
    /* Minimized Sidebar Submenu (Dropdown Items) Fix */
    body[data-app-theme] .c-sidebar.c-sidebar-minimized .c-sidebar-nav-dropdown-items {
        background: var(--shell-sidebar) !important;
        border: 1px solid var(--shell-border) !important;
        border-radius: 12px !important;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15) !important;
        padding: 8px !important;
        min-width: 220px !important;
    }
    body[data-app-theme] .c-sidebar.c-sidebar-minimized .c-sidebar-nav-dropdown-items .c-sidebar-nav-link {
        column-gap: 8px !important;
        width: 100% !important;
        height: auto !important;
        padding: 10px 12px !important;
        font-size: 0.9rem !important;
        font-weight: 500 !important;
        justify-content: flex-start !important;
        color: var(--shell-text) !important;
        border-radius: 8px !important;
        margin: 2px 0 !important;
    }
    body[data-app-theme] .c-sidebar.c-sidebar-minimized .c-sidebar-nav-dropdown-items .c-sidebar-nav-link:hover,
    body[data-app-theme] .c-sidebar.c-sidebar-minimized .c-sidebar-nav-dropdown-items .c-sidebar-nav-link.c-active {
        background: var(--shell-sidebar-hover) !important;
        color: var(--shell-primary) !important;
    }
    body[data-app-theme] .neci-sidebar-flyout-link .neci-submenu-icon,
    body[data-app-theme] .c-sidebar.c-sidebar-minimized .c-sidebar-nav-dropdown-items .neci-submenu-icon {
        margin: 0 !important;
        width: 20px !important;
        height: 20px !important;
        display: inline-block !important;
        position: static !important;
        flex: none !important;
        opacity: 1 !important;
        visibility: visible !important;
        color: var(--shell-sidebar-text) !important;
        stroke: currentColor !important;
        stroke-width: 2 !important;
        fill: none !important;
    }
    body[data-app-theme] .c-sidebar.c-sidebar-minimized .c-sidebar-nav-dropdown-items .c-sidebar-nav-link:hover .neci-submenu-icon {
        animation: neci-sidebar-icon-hover-once 0.62s cubic-bezier(0.34, 1.15, 0.64, 1) 1 both !important;
        color: #fff !important;
    }
    
    body[data-app-theme^="blue"] {
        --thunder-color: #3b82f6;
    }
    body[data-app-theme^="orange"] {
        --thunder-color: #f97316;
    }
    .custom-mfg-icon .mfg-body,
    .custom-mfg-icon .mfg-door,
    .custom-mfg-icon .mfg-window,
    .custom-mfg-icon .mfg-smoke {
        fill: transparent !important;
    }
    .custom-mfg-icon .mfg-smoke {
        opacity: 0.72;
        stroke-dasharray: 8;
        stroke-dashoffset: 0;
    }
    :is(.c-sidebar-nav-link, .c-sidebar-nav-dropdown-toggle, .neci-sidebar-dropdown-toggle):hover .custom-mfg-icon .mfg-smoke-1 {
        animation: mfg-smoke-rise 0.9s ease-in-out;
    }
    :is(.c-sidebar-nav-link, .c-sidebar-nav-dropdown-toggle, .neci-sidebar-dropdown-toggle):hover .custom-mfg-icon .mfg-smoke-2 {
        animation: mfg-smoke-rise 0.9s ease-in-out 0.08s;
    }
    :is(.c-sidebar-nav-link, .c-sidebar-nav-dropdown-toggle, .neci-sidebar-dropdown-toggle):hover .custom-mfg-icon .mfg-window {
        animation: mfg-window-glow 0.72s ease-in-out;
    }
    @keyframes mfg-smoke-rise {
        0% { stroke-dashoffset: 8; opacity: 0.35; }
        45% { stroke-dashoffset: 0; opacity: 0.85; }
        100% { stroke-dashoffset: -3; opacity: 0.55; }
    }
    @keyframes mfg-window-glow {
        0%, 100% { opacity: 0.75; }
        50% { opacity: 1; }
    }
    body[data-app-theme] .neci-themed-table {
        background: #fff;
        border-radius: 0.65rem;
        overflow: hidden;
    }
    body[data-app-theme] .neci-themed-table thead th {
        background: color-mix(in srgb, var(--shell-primary) 10%, #fff) !important;
        border-bottom: 1px solid var(--form-border) !important;
        color: var(--shell-text) !important;
        font-size: 0.82rem;
        font-weight: 700;
        letter-spacing: 0.03em;
        text-transform: uppercase;
        vertical-align: middle;
    }
    body[data-app-theme] .neci-readonly-field[readonly],
    body[data-app-theme] input.neci-readonly-field:read-only {
        background: color-mix(in srgb, var(--shell-primary) 8%, #fff) !important;
        border-color: var(--form-border) !important;
        color: var(--shell-text) !important;
        cursor: not-allowed;
        font-weight: 600;
    }
    body[data-app-theme] .form-group > .neci-tx-cancel {
        margin-left: 0.75rem;
    }
    body[data-app-theme] .btn-outline-primary[data-add-row] {
        background: color-mix(in srgb, var(--shell-primary) 7%, #fff) !important;
        border-color: color-mix(in srgb, var(--shell-primary) 32%, #fff) !important;
        color: var(--shell-primary-dark) !important;
        font-weight: 600;
    }
    body[data-app-theme] .btn-outline-primary[data-add-row]:hover,
    body[data-app-theme] .btn-outline-primary[data-add-row]:focus {
        background: color-mix(in srgb, var(--shell-primary) 11%, #fff) !important;
        border-color: color-mix(in srgb, var(--shell-primary) 38%, #fff) !important;
        color: var(--shell-primary-dark) !important;
        box-shadow: none !important;
    }
    body[data-app-theme] .neci-page-heading {
        align-items: center;
        display: flex;
        gap: 0.9rem;
        margin-bottom: 1rem;
    }
    body[data-app-theme] .neci-page-heading__icon {
        align-items: center;
        background: color-mix(in srgb, var(--shell-primary) 12%, #fff);
        border: 1px solid color-mix(in srgb, var(--shell-primary) 20%, #fff);
        border-radius: 0.65rem;
        color: var(--shell-primary);
        display: inline-flex;
        flex: 0 0 auto;
        font-size: 1.45rem;
        height: 3rem;
        justify-content: center;
        width: 3rem;
    }
    body[data-app-theme] .neci-page-heading__title {
        color: var(--shell-text);
        font-size: 1.25rem;
        font-weight: 700;
        line-height: 1.2;
        margin: 0;
    }
    body[data-app-theme] .neci-page-heading__subtitle {
        color: var(--shell-muted);
        font-size: 0.9rem;
        margin: 0.25rem 0 0;
    }
    body[data-app-theme] .neci-page-actions {
        align-items: center;
        display: flex;
        flex-wrap: wrap;
        gap: 0.65rem;
        justify-content: flex-start;
        margin: 0 0 1.15rem;
    }
    body[data-app-theme] .modal-footer {
        justify-content: flex-start !important;
    }
    body[data-app-theme] .neci-report-reset {
        background: transparent !important;
        border: 2px solid color-mix(in srgb, var(--shell-primary) 36%, #fff) !important;
        border-radius: 999px;
        color: var(--shell-primary-dark) !important;
        font-weight: 600;
        padding: 0.45rem 1.05rem;
        transition: background 180ms ease, border-color 180ms ease, color 180ms ease;
    }
    body[data-app-theme] .neci-report-reset:hover,
    body[data-app-theme] .neci-report-reset:focus {
        background: color-mix(in srgb, var(--shell-primary) 10%, #fff) !important;
        border-color: color-mix(in srgb, var(--shell-primary) 48%, #fff) !important;
        color: var(--shell-primary-dark) !important;
        box-shadow: none !important;
    }
    </style>
</head>

<body class="c-app neci-preload">
    <script>
        /* Apply theme attrs from <html> pre-resolution (set in <head>) — zero FOUC */
        (function () {
            try {
                var el    = document.documentElement;
                var color = el.getAttribute('data-app-pre-color') || 'blue';
                var mode  = el.getAttribute('data-app-pre-mode')  || 'light';
                var body  = document.body;
                body.dataset.appColor = color;
                body.dataset.appMode  = mode;
                body.dataset.appTheme = color + '-' + mode;

                /* Apply saved sidebar state immediately so the sidebar never reflows */
                try {
                    if (sessionStorage.getItem('neci-sidebar-collapsed') === '1') {
                        body.setAttribute('data-sidebar-collapsed', '1');
                    }
                } catch (se) {}
            } catch (e) {}
        })();
    </script>
    @include('layouts.sidebar')

    <div class="c-wrapper">
        <header class="c-header c-header-light c-header-fixed">
            @include('layouts.header')
            <div class="c-subheader justify-content-between px-3">
                @yield('breadcrumb')
                <div class="d-flex align-items-center">
                    @yield('subheader_right')
                    <div class="dashboard-theme-switcher d-flex align-items-center ml-3" aria-label="Dashboard theme controls" style="border: none !important; background: transparent !important; box-shadow: none !important;">
                        <button type="button" class="theme-toggle" id="dashboardColorToggle" aria-label="Switch dashboard color" style="border: none !important; background: transparent !important; box-shadow: none !important; outline: none !important;">
                            <span class="toggle-track">
                                <span class="toggle-thumb"></span>
                            </span>
                            <span class="toggle-text" id="dashboardColorText" style="border: none !important;">Blue</span>
                        </button>
                        <button type="button" class="theme-toggle" id="dashboardModeToggle" aria-label="Switch dashboard light or dark mode" style="border: none !important; background: transparent !important; box-shadow: none !important; outline: none !important;">
                            <span class="toggle-track">
                                <span class="toggle-thumb"></span>
                                <i class="bi bi-sun toggle-sun"></i>
                                <i class="bi bi-moon-stars toggle-moon"></i>
                            </span>
                            <span class="toggle-text" id="dashboardModeText" style="border: none !important;">Light</span>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <div class="c-body">
            <main class="c-main">
                @yield('content')
            </main>
        </div>

        @include('layouts.footer')
    </div>



    @include('includes.main-js')
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            /* Remove preload class now that DOM is ready — enables transitions */
            document.body.classList.remove('neci-preload');

            // ── Diagnostic: track dashboard renders and Livewire updates ──────
            if (window.__neciDiag) {
                window.__neciDiag.dashboardRendered++;
                console.log('[NECI Dashboard] Dashboard Rendered (DOMContentLoaded #' + window.__neciDiag.dashboardRendered + ')');
            }

            document.addEventListener('livewire:commit', function () {
                if (window.__neciDiag) {
                    window.__neciDiag.sidebarUpdated++;
                    console.log('[NECI Sidebar] Sidebar Updated via Livewire commit (total: ' + window.__neciDiag.sidebarUpdated + ')');
                }
            });
            // ──────────────────────────────────────────────────────────────────

            const dashboard   = document.getElementById('neciDashboard');
            const colorToggle = document.getElementById('dashboardColorToggle');
            const modeToggle  = document.getElementById('dashboardModeToggle');
            const colorText   = document.getElementById('dashboardColorText');
            const modeText    = document.getElementById('dashboardModeText');

            /* Seed from the attrs already applied by the <head> anti-FOUC script */
            let color = document.body.dataset.appColor || 'blue';
            let mode  = document.body.dataset.appMode  || 'light';

            function applyTheme(c, m) {
                const theme = c + '-' + m;
                if (dashboard) {
                    dashboard.dataset.dashboardTheme = theme;
                    dashboard.dataset.dashboardColor = c;
                    dashboard.dataset.dashboardMode  = m;
                }
                document.body.dataset.appTheme = theme;
                document.body.dataset.appColor = c;
                document.body.dataset.appMode  = m;
                localStorage.setItem('neci_dashboard_theme', theme);
                localStorage.setItem('neci_dashboard_color', c);
                localStorage.setItem('neci_dashboard_mode',  m);
            }

            function syncToggleUI() {
                if (!colorToggle || !modeToggle) return;
                colorToggle.classList.toggle('is-orange', color === 'orange');
                modeToggle.classList.toggle('is-dark',    mode  === 'dark');
                modeToggle.classList.toggle('is-orange',  color === 'orange');
                if (colorText) colorText.textContent = color === 'orange' ? 'Orange' : 'Blue';
                if (modeText)  modeText.textContent  = mode  === 'dark'   ? 'Dark'   : 'Light';
            }

            /* Sync toggle UI state without re-applying theme (already set in <head>) */
            syncToggleUI();

            if (colorToggle && modeToggle) {
                colorToggle.addEventListener('click', function () {
                    color = color === 'blue' ? 'orange' : 'blue';
                    applyTheme(color, mode);
                    syncToggleUI();
                });

                modeToggle.addEventListener('click', function () {
                    mode = mode === 'light' ? 'dark' : 'light';
                    applyTheme(color, mode);
                    syncToggleUI();
                });
            }
        });
    </script>
</body>
</html>
