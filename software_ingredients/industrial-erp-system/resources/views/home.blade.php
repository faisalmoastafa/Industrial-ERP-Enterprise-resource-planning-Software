@extends('layouts.app')

@section('title', 'Home')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item active">Home</li>
    </ol>
@endsection



@section('third_party_stylesheets')
<link rel="preload" href="{{ request()->getSchemeAndHttpHost() }}/images/yeti_welcome_back.png" as="image">
<style>
/* Business Flow Card Glowing Design */
.dashboard-grid .ring-card {
    border: none !important;
    box-shadow: none !important;
    background: var(--dash-panel) !important;
}
.business-flow-chart-container {
    filter: drop-shadow(0 0 15px rgba(59, 130, 246, 0.5)) drop-shadow(0 0 15px rgba(249, 115, 22, 0.5));
}
.business-flow-thunder {
    background: #ffffff !important;
    box-shadow: 0 0 15px rgba(255, 255, 255, 0.8) !important;
}



/* Profit / Loss card: weekly stacked movement, Saturday first and Friday last. */
.dashboard-grid .hero-card {
    display: flex;
    flex-direction: column;
}
.profit-volume-chart {
    flex-grow: 1;
    display: grid;
    grid-template-columns: repeat(7, minmax(24px, 1fr));
    align-items: end;
    gap: clamp(8px, 1.2vw, 16px);
    min-height: 205px;
    margin: 26px 0 22px;
    padding: 8px 4px 0;
    position: relative;
    overflow: visible;
}
.profit-volume-chart::before {
    content: "";
    position: absolute;
    left: 0;
    right: 0;
    bottom: 48%;
    height: 1px;
    background: rgba(255, 255, 255, 0.24);
}
.profit-day {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    position: relative;
    z-index: 1;
}
.profit-day:hover {
    z-index: 30;
}
.profit-bar-stack {
    width: clamp(20px, 2vw, 30px);
    height: var(--bar-height, 20px);
    min-height: 16px;
    max-height: 168px;
    display: flex;
    flex-direction: column-reverse;
    overflow: hidden;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.18);
    box-shadow:
        inset 0 1px 0 rgba(255, 255, 255, 0.22),
        0 12px 24px rgba(15, 23, 42, 0.16);
    transform-origin: bottom;
    animation: neciProfitBarGrow 850ms cubic-bezier(0.22, 1, 0.36, 1) both;
    animation-delay: var(--bar-delay, 0ms);
}
.profit-segment {
    display: block;
    min-height: 4px;
}
.profit-segment.sales {
    height: var(--sales-share, 0%);
    background: var(--profit-sales-bar, linear-gradient(180deg, #38bdf8 0%, #1d4ed8 100%));
}
.profit-segment.purchases {
    height: var(--purchase-share, 0%);
    background: var(--profit-purchases-bar, linear-gradient(180deg, #fb923c 0%, #ea580c 100%));
}
.profit-segment.expenses {
    height: var(--expense-share, 0%);
    background: linear-gradient(180deg, #ffffff 0%, #dbeafe 100%);
}
.profit-segment.empty {
    height: 100%;
    background: rgba(255, 255, 255, 0.22);
}
.profit-day.is-today:not(.is-off-day) .profit-bar-stack {
    outline: 3px solid rgba(255, 255, 255, 0.74);
    outline-offset: 4px;
}
.profit-day.is-off-day {
    opacity: 1;
}
.profit-day-tooltip {
    position: absolute;
    left: 50%;
    bottom: calc(100% + 18px);
    width: 148px;
    padding: 8px 10px;
    border-radius: 12px;
    background: rgba(15, 23, 42, 0.92);
    color: #ffffff;
    box-shadow: 0 12px 26px rgba(15, 23, 42, 0.2);
    font-size: 0.66rem;
    line-height: 1.3;
    opacity: 0;
    pointer-events: none;
    transform: translate(-50%, 8px) scale(0.96);
    transition: opacity 180ms ease, transform 180ms ease;
    z-index: 60;
}
.profit-day-tooltip::after {
    content: "";
    position: absolute;
    left: 50%;
    top: 100%;
    width: 10px;
    height: 10px;
    background: inherit;
    transform: translate(-50%, -5px) rotate(45deg);
}
.profit-day:hover .profit-day-tooltip {
    opacity: 1;
    transform: translate(-50%, 0) scale(1);
}
.profit-day:first-child .profit-day-tooltip {
    left: 0;
    transform: translate(0, 8px) scale(0.96);
}
.profit-day:first-child:hover .profit-day-tooltip {
    transform: translate(0, 0) scale(1);
}
.profit-day:first-child .profit-day-tooltip::after {
    left: 18px;
}
.profit-day:last-child .profit-day-tooltip {
    left: auto;
    right: 0;
    transform: translate(0, 8px) scale(0.96);
}
.profit-day:last-child:hover .profit-day-tooltip {
    transform: translate(0, 0) scale(1);
}
.profit-day:last-child .profit-day-tooltip::after {
    left: auto;
    right: 18px;
    transform: translate(0, -5px) rotate(45deg);
}
.profit-tooltip-title {
    display: block;
    margin-bottom: 5px;
    color: rgba(255, 255, 255, 0.78);
    font-size: 0.62rem;
    font-weight: 800;
    letter-spacing: 0.04em;
    text-transform: uppercase;
}
.profit-tooltip-row {
    display: flex;
    justify-content: space-between;
    gap: 10px;
    font-weight: 700;
}
.profit-tooltip-row + .profit-tooltip-row {
    margin-top: 4px;
}
.profit-tooltip-row span:first-child {
    color: rgba(255, 255, 255, 0.72);
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.profit-tooltip-row span:last-child {
    color: #ffffff;
}
.profit-tooltip-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    display: inline-block;
    flex: 0 0 7px;
}
.profit-tooltip-row.sales .profit-tooltip-dot {
    background: #38bdf8;
    box-shadow: 0 0 8px rgba(56, 189, 248, 0.7);
}
.profit-tooltip-row.purchases .profit-tooltip-dot {
    background: #fb923c;
    box-shadow: 0 0 8px rgba(251, 146, 60, 0.7);
}
.profit-tooltip-row.expenses .profit-tooltip-dot {
    background: #ffffff;
    border: 1px solid rgba(15, 23, 42, 0.28);
    box-shadow: 0 0 8px rgba(255, 255, 255, 0.55);
}
.profit-day-label {
    color: rgba(255, 255, 255, 0.82);
    font-size: 0.75rem;
    font-weight: 800;
    letter-spacing: 0.02em;
}
.profit-volume-chart:hover .profit-bar-stack {
    filter: saturate(1.14) brightness(1.04);
}

/* Fix Apexcharts tooltip z-index over chart-header icon */
.apexcharts-tooltip {
    z-index: 1000 !important;
}
.chart-header i {
    z-index: 1;
}
@keyframes neciProfitBarGrow {
    from {
        opacity: 0;
        transform: translateY(12px) scaleY(0.18);
    }
    to {
        opacity: 1;
        transform: translateY(0) scaleY(1);
    }
}
.hero-breakdown {
    margin-top: auto;
}

body[data-app-theme="blue-light"] {
    --profit-sales-bar: linear-gradient(180deg, #0ea5e9 0%, #1d4ed8 100%);
    --profit-purchases-bar: linear-gradient(180deg, #fb923c 0%, #ea580c 100%);
}

body[data-app-theme="orange-light"] {
    --profit-sales-bar: linear-gradient(180deg, #0284c7 0%, #1e40af 100%);
    --profit-purchases-bar: linear-gradient(180deg, #ff8a00 0%, #c2410c 100%);
}

body[data-app-mode="dark"][data-app-theme="blue-dark"] {
    --profit-sales-bar: linear-gradient(180deg, #38bdf8 0%, #2563eb 100%);
    --profit-purchases-bar: linear-gradient(180deg, #f97316 0%, #9a3412 100%);
}

body[data-app-mode="dark"][data-app-theme="orange-dark"] {
    --profit-sales-bar: linear-gradient(180deg, #0ea5e9 0%, #1d4ed8 100%);
    --profit-purchases-bar: linear-gradient(180deg, #fb923c 0%, #ea580c 100%);
}
.payable-receivable-card {
    margin-bottom: clamp(26px, 3vw, 42px);
}
</style>
@endsection

@section('content')
    <div class="container-fluid neci-dashboard" id="neciDashboard" data-dashboard-theme="blue-light">
        <div class="dashboard-shell">
            <div class="dashboard-topbar">
                <div>
                    <span class="dashboard-eyebrow">{{ settings()->app_tagline ?? settings()->company_name }}</span>
                    <h1>Analytics</h1>
                </div>
            </div>

            @can('show_total_stats')
                @php
                    if (!function_exists('format_short')) {
                        function format_short($num) {
                            if ($num >= 1000) {
                                return round($num / 1000, 1) . 'k';
                            }
                            return $num;
                        }
                    }

                    $profitBars = $profitBars ?? [];
                @endphp
                <div class="dashboard-grid">
                    <section class="dashboard-card hero-card">
                        <div class="dashboard-card-icon"><i class="bi bi-currency-exchange"></i></div>
                        <span class="metric-label">Profit / Loss</span>
                        <div class="hero-value">{{ format_currency($profit) }}</div>
                        <div class="profit-volume-chart" aria-label="Weekly sales, purchases, and expenses from Saturday to Friday">
                            @foreach($profitBars as $day)
                                <div class="profit-day {{ $day['is_today'] ? 'is-today' : '' }} {{ $day['is_off_day'] ? 'is-off-day' : '' }}"
                                     style="--bar-height: {{ $day['height'] }}px; --bar-delay: {{ $day['delay'] }}ms; --sales-share: {{ $day['sales_percent'] }}%; --purchase-share: {{ $day['purchases_percent'] }}%; --expense-share: {{ $day['expenses_percent'] }}%;">
                                    <div class="profit-bar-stack">
                                        @if($day['volume'] > 0)
                                            <span class="profit-segment sales"></span>
                                            <span class="profit-segment purchases"></span>
                                            <span class="profit-segment expenses"></span>
                                        @else
                                            <span class="profit-segment empty"></span>
                                        @endif
                                    </div>
                                    <div class="profit-day-tooltip">
                                        <strong class="profit-tooltip-title">{{ $day['label'] }}</strong>
                                        <div class="profit-tooltip-row sales">
                                            <span><i class="profit-tooltip-dot"></i>Sales</span>
                                            <span>{{ format_currency($day['sales']) }}</span>
                                        </div>
                                        <div class="profit-tooltip-row purchases">
                                            <span><i class="profit-tooltip-dot"></i>Purchases</span>
                                            <span>{{ format_currency($day['purchases']) }}</span>
                                        </div>
                                        <div class="profit-tooltip-row expenses">
                                            <span><i class="profit-tooltip-dot"></i>Expenses</span>
                                            <span>{{ format_currency($day['expenses']) }}</span>
                                        </div>
                                    </div>
                                    <span class="profit-day-label">{{ $day['label'] }}</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="hero-breakdown">
                            <div>
                                <span><i class="sparkline-key sparkline-key-sales"></i>Sales</span>
                                <strong>{{ format_currency($sales_total) }}</strong>
                            </div>
                            <div>
                                <span><i class="sparkline-key sparkline-key-purchases"></i>Purchases</span>
                                <strong>{{ format_currency($purchases_total) }}</strong>
                            </div>
                            <div>
                                <span><i class="sparkline-key sparkline-key-expenses"></i>Expenses</span>
                                <strong>{{ format_currency($expenses_total) }}</strong>
                            </div>
                        </div>
                    </section>

                    <section class="dashboard-card ring-card">
                        <div class="flow-card-header">
                            <span class="metric-label">Business Flow</span>
                            <div class="dashboard-card-icon"><i class="bi bi-lightning-charge"></i></div>
                        </div>
                        <div class="ring-content h-100 pb-2 d-flex flex-column align-items-center justify-content-center">
                            <!-- TOP: Stats -->
                            <div class="d-flex justify-content-center w-100 mb-3 mt-2">
                                <div class="text-center px-3">
                                    <div class="large-number" style="font-size: 1.8rem; font-weight: 800; color: var(--dash-text); line-height: 1;">{{ format_short($completed_sales) }}</div>
                                    <span class="muted" style="font-size: 0.75rem; font-weight: 600;">Completed<br>Sales</span>
                                </div>
                                <div style="width: 1px; height: 40px; background-color: var(--dash-line); margin-top: 5px;"></div>
                                <div class="text-center px-3">
                                    <div class="large-number" style="font-size: 1.8rem; font-weight: 800; color: var(--dash-text); line-height: 1;">{{ format_short($completed_purchases) }}</div>
                                    <span class="muted" style="font-size: 0.75rem; font-weight: 600;">Completed<br>Purchases</span>
                                </div>
                            </div>
                            
                            <!-- MIDDLE: Chart -->
                            <div class="business-flow-chart-container mx-auto" style="width: 140px; height: 140px; position: relative;">
                                <div class="business-flow-thunder" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); display: flex; align-items: center; justify-content: center; width: 95px; height: 95px; background: #ffffff; border-radius: 50%; box-shadow: 0 0 15px rgba(255,255,255,0.8); z-index: 1; color: var(--thunder-color); font-size: 2.6rem;">
                                    <i class="bi bi-lightning-charge-fill"></i>
                                </div>
                                <canvas id="businessFlowChart" data-sales="{{ $completed_sales }}" data-purchases="{{ $completed_purchases }}" style="position: relative; z-index: 2;"></canvas>
                            </div>

                            <!-- BOTTOM: Legend -->
                            <div class="d-flex justify-content-center mt-3 w-100 text-center" style="font-size: 0.75rem; font-weight: 600;">
                                <div class="px-3"><i class="legend-dot primary" style="background: #3b82f6; width: 10px; height: 10px; display: inline-block; border-radius: 50%; margin-right: 5px; box-shadow: 0 0 5px #3b82f6;"></i> Sales</div>
                                <div class="px-3"><i class="legend-dot secondary" style="background: #f97316; width: 10px; height: 10px; display: inline-block; border-radius: 50%; margin-right: 5px; box-shadow: 0 0 5px #f97316;"></i> Purchases</div>
                            </div>
                        </div>
                    </section>

                    <aside class="dashboard-side">
                        <div class="mini-card">
                            <div class="mini-icon"><i class="bi bi-arrow-return-right"></i></div>
                            <div>
                                <span>Sales Return</span>
                                <strong>{{ format_currency($sale_returns) }}</strong>
                            </div>
                        </div>
                        <div class="mini-card">
                            <div class="mini-icon"><i class="bi bi-arrow-return-left"></i></div>
                            <div>
                                <span>Purchase Return</span>
                                <strong>{{ format_currency($purchase_returns) }}</strong>
                            </div>
                        </div>
                        <div class="mini-card">
                            <div class="mini-icon"><i class="bi bi-trophy"></i></div>
                            <div>
                                            <span>Achievement</span>
                                            <strong>{{ $revenue != 0 ? number_format(($profit / $revenue) * 100, 1) : '0.0' }}%</strong>
                            </div>
                        </div>
                        <div class="mini-card">
                            <div class="mini-icon"><i class="bi bi-exclamation-triangle"></i></div>
                            <div>
                                <span>Low Stock</span>
                                <strong>{{ $low_stock_products }} Items</strong>
                            </div>
                        </div>
                    </aside>
                </div>
            @endcan

            @if(auth()->user()->can('show_weekly_sales_purchases') || auth()->user()->can('show_month_overview'))
                <div class="dashboard-main-row">
                    @can('show_weekly_sales_purchases')
                        <section class="dashboard-card chart-card chart-wide">
                            <div class="chart-header">
                                <div>
                                    <span class="dashboard-eyebrow">Last 7 Days</span>
                                    <h2>Sales & Purchases</h2>
                                </div>
                                <i class="bi bi-bar-chart-line"></i>
                            </div>
                            <canvas id="salesPurchasesChart"></canvas>
                        </section>
                    @endcan

                    @can('show_month_overview')
                        <section class="dashboard-card chart-card chart-compact">
                            <div class="chart-header">
                                <div>
                                    <span class="dashboard-eyebrow">{{ now()->format('F, Y') }}</span>
                                    <h2>Month Overview</h2>
                                </div>
                                <i class="bi bi-pie-chart"></i>
                            </div>
                            <div class="donut-holder">
                                <canvas id="currentMonthChart"></canvas>
                            </div>
                        </section>
                    @endcan
                </div>
            @endif

            @can('access_products')
                <div class="dashboard-half-row">
                    <section class="dashboard-card chart-card stock-flow-card">
                        <div class="chart-header">
                            <div>
                                <span class="dashboard-eyebrow">Monthly Inventory Movement</span>
                                <h2>Stock Analysis</h2>
                            </div>
                            <i class="bi bi-bar-chart"></i>
                        </div>
                        <canvas id="stockMovementChart"></canvas>
                    </section>
                    
                    <section class="dashboard-card chart-card stock-flow-card">
                        <div class="chart-header">
                            <div>
                                <span class="dashboard-eyebrow">Last 7 Days Movement</span>
                                <h2>Weekly Stock Analysis</h2>
                            </div>
                            <i class="bi bi-bar-chart-steps"></i>
                        </div>
                        <canvas id="stockMovementWeeklyChart"></canvas>
                    </section>
                </div>
            @endcan

            @can('show_monthly_cashflow')
                <div class="dashboard-half-row mt-4 mb-4" style="margin-top: 24px; margin-bottom: 24px;">
                    <section class="dashboard-card chart-card stock-flow-card">
                        <div class="chart-header">
                            <div>
                                <span class="dashboard-eyebrow">Customer vs Supplier</span>
                                <h2>Monthly Prepay</h2>
                            </div>
                            <i class="bi bi-cash-stack"></i>
                        </div>
                        <canvas id="prepayChart"></canvas>
                    </section>

                    <section class="dashboard-card chart-card stock-flow-card">
                        <div class="chart-header">
                            <div>
                                <span class="dashboard-eyebrow">Customer vs Supplier</span>
                                <h2>Monthly Pay Later</h2>
                            </div>
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <canvas id="payLaterChart"></canvas>
                    </section>
                </div>

                <section class="dashboard-card chart-card cashflow-card payable-receivable-card">
                    <div class="chart-header">
                        <div>
                            <span class="dashboard-eyebrow">Monthly Outstanding</span>
                            <h2>Payable & Receivable</h2>
                        </div>
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <canvas id="payableReceivableChart"></canvas>
                </section>

                <section class="dashboard-card chart-card cashflow-card">
                    <div class="chart-header">
                        <div>
                            <span class="dashboard-eyebrow">Payment Sent & Received</span>
                            <h2>Monthly Cash Flow</h2>
                        </div>
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <canvas id="paymentChart"></canvas>
                </section>
            @endcan
        </div>
    </div>
@endsection

@section('third_party_scripts')
    <script src="{{ asset('js/vendor/chart.min.js') }}"></script>
@endsection

@push('page_scripts')
    @vite('resources/js/chart-config.js')

    <!-- SweetAlert2 (local) -->
    <script src="{{ asset('vendor/sweetalert/sweetalert2.all.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if(session('login_success'))
                const wishes = [
                    "Great to have you back! Have a productive day.",
                    "Welcome back! Ready to tackle the inventory?",
                    "Hello again! Hope your day is going wonderfully.",
                    "Glad to see you! Let's get things done.",
                    "Welcome aboard! Time to make magic happen."
                ];
                const randomWish = wishes[Math.floor(Math.random() * wishes.length)];
                
                Swal.fire({
                    // Uses the custom NECI welcome artwork instead of SweetAlert's default info icon.
                    imageUrl: "{{ request()->getSchemeAndHttpHost() }}/images/yeti_welcome_back.png",
                    imageWidth: 200,
                    imageAlt: 'Welcome back to NECI Inventory System',
                    text: randomWish,
                    timer: 5000,
                    showConfirmButton: true,
                    confirmButtonText: 'Let\'s Go!',
                    buttonsStyling: false,
                    toast: false,
                    position: 'center',
                    customClass: {
                        popup: 'neci-welcome-swal',
                        image: 'neci-welcome-swal__image',
                        htmlContainer: 'neci-welcome-swal__text',
                        actions: 'neci-welcome-swal__actions',
                        confirmButton: 'neci-welcome-swal__button'
                    },
                    showClass: {
                        popup: 'neci-welcome-swal--show'
                    },
                    hideClass: {
                        popup: 'neci-welcome-swal--hide'
                    }
                });
            @endif
        });

    </script>
    <script src="{{ asset('js/vendor/gsap.min.js') }}"></script>
@endpush
