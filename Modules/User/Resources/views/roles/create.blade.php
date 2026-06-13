@extends('layouts.app')

@section('title', 'Create Role')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
        <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></li>
        <li class="breadcrumb-item active">Create</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid roles-permissions">
        @include('includes.neci-page-header', [
            'icon' => 'bi-key',
            'title' => 'Create Role',
            'subtitle' => 'Create a permission group for system access'
        ])

        <div class="row">
            <div class="col-md-12">
                @include('utils.alerts')
                <form action="{{ route('roles.store') }}" method="POST">
                    @csrf

                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="form-group">
                                <label for="name" class="font-weight-bold">Role Name <span
                                        class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="name" required value="{{ old('name') }}">
                            </div>

                            <hr class="my-4">

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="font-weight-bold h5 text-dark">Permissions <span
                                            class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6 text-md-right">
                                    <div class="custom-control custom-checkbox d-inline-block">
                                        <input type="checkbox" class="custom-control-input" id="select-all">
                                        <label class="custom-control-label font-weight-bold text-primary"
                                            for="select-all">Give All Permissions</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 col-lg-3 border-right">
                                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist"
                                        aria-orientation="vertical">
                                        <a class="nav-link active" id="tab-dashboard" data-toggle="pill"
                                            href="#panel-dashboard" role="tab"><i class="bi bi-speedometer2 mr-2"></i>
                                            Dashboard</a>
                                        <a class="nav-link" id="tab-products" data-toggle="pill" href="#panel-products"
                                            role="tab"><i class="bi bi-box-seam mr-2"></i> Products</a>
                                        <a class="nav-link" id="tab-adjustments" data-toggle="pill"
                                            href="#panel-adjustments" role="tab"><i class="bi bi-sliders mr-2"></i> Stock
                                            Adjustments</a>
                                        <a class="nav-link" id="tab-quotations" data-toggle="pill" href="#panel-quotations"
                                            role="tab"><i class="bi bi-file-earmark-text mr-2"></i> Quotations</a>
                                        <a class="nav-link" id="tab-purchases" data-toggle="pill" href="#panel-purchases"
                                            role="tab"><i class="bi bi-cart-plus mr-2"></i> Purchases & Returns</a>
                                        <a class="nav-link" id="tab-sales" data-toggle="pill" href="#panel-sales"
                                            role="tab"><i class="bi bi-journal-check mr-2"></i> Sales & Returns</a>
                                        <a class="nav-link" id="tab-manufacturing" data-toggle="pill" href="#panel-manufacturing"
                                            role="tab"><i class="bi bi-building mr-2"></i> Manufacturing</a>
                                        <a class="nav-link" id="tab-expenses" data-toggle="pill" href="#panel-expenses"
                                            role="tab"><i class="bi bi-wallet2 mr-2"></i> Expenses</a>
                                        <a class="nav-link" id="tab-income" data-toggle="pill" href="#panel-income"
                                            role="tab"><i class="bi bi-cash-coin mr-2"></i> Earnings</a>
                                        <a class="nav-link" id="tab-hrm" data-toggle="pill" href="#panel-hrm"
                                            role="tab"><i class="bi bi-briefcase mr-2"></i> HRM</a>
                                        <a class="nav-link" id="tab-parties" data-toggle="pill" href="#panel-parties"
                                            role="tab"><i class="bi bi-people mr-2"></i> Parties</a>
                                        <a class="nav-link" id="tab-reports" data-toggle="pill" href="#panel-reports"
                                            role="tab"><i class="bi bi-graph-up mr-2"></i> Reports</a>
                                        <a class="nav-link" id="tab-users" data-toggle="pill" href="#panel-users"
                                            role="tab"><i class="bi bi-shield-lock mr-2"></i> User Management</a>
                                        <a class="nav-link" id="tab-settings" data-toggle="pill" href="#panel-settings"
                                            role="tab"><i class="bi bi-gear mr-2"></i> Settings</a>
                                        <a class="nav-link" id="tab-utilities" data-toggle="pill" href="#panel-utilities"
                                            role="tab"><i class="bi bi-server mr-2"></i> System Utilities</a>
                                    </div>
                                </div>

                                <div class="col-md-8 col-lg-9 p-4">
                                    <div class="tab-content" id="v-pills-tabContent">

                                        <div class="tab-pane fade show active" id="panel-dashboard" role="tabpanel">
                                            <div class="permission-section-title">Dashboard Matrix Core</div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <div class="custom-control custom-switch"><input type="checkbox"
                                                            class="custom-control-input" id="show_total_stats"
                                                            name="permissions[]" value="show_total_stats" {{ in_array('show_total_stats', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                            for="show_total_stats">Total Stats</label></div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <div class="custom-control custom-switch"><input type="checkbox"
                                                            class="custom-control-input" id="show_notifications"
                                                            name="permissions[]" value="show_notifications" {{ in_array('show_notifications', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                            for="show_notifications">Notifications</label></div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <div class="custom-control custom-switch"><input type="checkbox"
                                                            class="custom-control-input" id="show_month_overview"
                                                            name="permissions[]" value="show_month_overview" {{ in_array('show_month_overview', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                            for="show_month_overview">Month Overview</label></div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <div class="custom-control custom-switch"><input type="checkbox"
                                                            class="custom-control-input" id="show_weekly_sales_purchases"
                                                            name="permissions[]" value="show_weekly_sales_purchases" {{ in_array('show_weekly_sales_purchases', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                            for="show_weekly_sales_purchases">Weekly Sales &
                                                            Purchases</label></div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <div class="custom-control custom-switch"><input type="checkbox"
                                                            class="custom-control-input" id="show_monthly_cashflow"
                                                            name="permissions[]" value="show_monthly_cashflow" {{ in_array('show_monthly_cashflow', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                            for="show_monthly_cashflow">Monthly Cashflow</label></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="panel-products" role="tabpanel">
                                            <div class="permission-section-title">Products Specifications</div>
                                            <div class="row">
                                                <div class="col-md-4 mb-3">
                                                    <div class="custom-control custom-switch"><input type="checkbox"
                                                            class="custom-control-input" id="access_products"
                                                            name="permissions[]" value="access_products" {{ in_array('access_products', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                            for="access_products">Access</label></div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="custom-control custom-switch"><input type="checkbox"
                                                            class="custom-control-input" id="show_products"
                                                            name="permissions[]" value="show_products" {{ in_array('show_products', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                            for="show_products">View</label></div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="custom-control custom-switch"><input type="checkbox"
                                                            class="custom-control-input" id="create_products"
                                                            name="permissions[]" value="create_products" {{ in_array('create_products', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                            for="create_products">Create</label></div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="custom-control custom-switch"><input type="checkbox"
                                                            class="custom-control-input" id="edit_products"
                                                            name="permissions[]" value="edit_products" {{ in_array('edit_products', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                            for="edit_products">Edit</label></div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="custom-control custom-switch"><input type="checkbox"
                                                            class="custom-control-input" id="delete_products"
                                                            name="permissions[]" value="delete_products" {{ in_array('delete_products', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                            for="delete_products">Delete</label></div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="custom-control custom-switch"><input type="checkbox"
                                                            class="custom-control-input" id="access_product_categories"
                                                            name="permissions[]" value="access_product_categories" {{ in_array('access_product_categories', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                            for="access_product_categories">Category</label></div>
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <div class="custom-control custom-switch"><input type="checkbox"
                                                            class="custom-control-input" id="print_barcodes"
                                                            name="permissions[]" value="print_barcodes" {{ in_array('print_barcodes', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                            for="print_barcodes">Print Barcodes</label></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="panel-adjustments" role="tabpanel">
                                            <div class="permission-section-title">Stock Adjustments Control Registry</div>
                                            <div class="row">
                                                <div class="col-md-4 mb-3">
                                                    <div class="custom-control custom-switch"><input type="checkbox"
                                                            class="custom-control-input" id="access_adjustments"
                                                            name="permissions[]" value="access_adjustments" {{ in_array('access_adjustments', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                            for="access_adjustments">Access</label></div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="custom-control custom-switch"><input type="checkbox"
                                                            class="custom-control-input" id="create_adjustments"
                                                            name="permissions[]" value="create_adjustments" {{ in_array('create_adjustments', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                            for="create_adjustments">Create</label></div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="custom-control custom-switch"><input type="checkbox"
                                                            class="custom-control-input" id="show_adjustments"
                                                            name="permissions[]" value="show_adjustments" {{ in_array('show_adjustments', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                            for="show_adjustments">View</label></div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="custom-control custom-switch"><input type="checkbox"
                                                            class="custom-control-input" id="edit_adjustments"
                                                            name="permissions[]" value="edit_adjustments" {{ in_array('edit_adjustments', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                            for="edit_adjustments">Edit</label></div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="custom-control custom-switch"><input type="checkbox"
                                                            class="custom-control-input" id="delete_adjustments"
                                                            name="permissions[]" value="delete_adjustments" {{ in_array('delete_adjustments', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                            for="delete_adjustments">Delete</label></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="panel-quotations" role="tabpanel">
                                            <div class="permission-section-title">Quotations Operations</div>
                                            <div class="row">
                                                <div class="col-md-4 mb-3">
                                                    <div class="custom-control custom-switch"><input type="checkbox"
                                                            class="custom-control-input" id="access_quotations"
                                                            name="permissions[]" value="access_quotations" {{ in_array('access_quotations', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                            for="access_quotations">Access</label></div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="custom-control custom-switch"><input type="checkbox"
                                                            class="custom-control-input" id="create_quotations"
                                                            name="permissions[]" value="create_quotations" {{ in_array('create_quotations', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                            for="create_quotations">Create</label></div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="custom-control custom-switch"><input type="checkbox"
                                                            class="custom-control-input" id="show_quotations"
                                                            name="permissions[]" value="show_quotations" {{ in_array('show_quotations', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                            for="show_quotations">View</label></div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="custom-control custom-switch"><input type="checkbox"
                                                            class="custom-control-input" id="edit_quotations"
                                                            name="permissions[]" value="edit_quotations" {{ in_array('edit_quotations', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                            for="edit_quotations">Edit</label></div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="custom-control custom-switch"><input type="checkbox"
                                                            class="custom-control-input" id="delete_quotations"
                                                            name="permissions[]" value="delete_quotations" {{ in_array('delete_quotations', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                            for="delete_quotations">Delete</label></div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="custom-control custom-switch"><input type="checkbox"
                                                            class="custom-control-input" id="send_quotation_mails"
                                                            name="permissions[]" value="send_quotation_mails" {{ in_array('send_quotation_mails', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                            for="send_quotation_mails">Send Email</label></div>
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <div class="custom-control custom-switch"><input type="checkbox"
                                                            class="custom-control-input" id="create_quotation_sales"
                                                            name="permissions[]" value="create_quotation_sales" {{ in_array('create_quotation_sales', old('permissions', []), true) ? 'checked' : '' }}><label
                                                            class="custom-control-label font-weight-bold text-dark"
                                                            for="create_quotation_sales">Sale From Quotation</label></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="panel-purchases" role="tabpanel">
                                            <div class="permission-section-title">Purchases Registry & Returns Ledger</div>

                                            <div class="nested-sub-module">
                                                <div class="nested-title"><i class="bi bi-file-earmark-arrow-down"></i>
                                                    Purchases</div>
                                                <div class="row">
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-switch"><input type="checkbox"
                                                                class="custom-control-input" id="access_purchases"
                                                                name="permissions[]" value="access_purchases" {{ in_array('access_purchases', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                                for="access_purchases">Access</label></div>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-switch"><input type="checkbox"
                                                                class="custom-control-input" id="create_purchases"
                                                                name="permissions[]" value="create_purchases" {{ in_array('create_purchases', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                                for="create_purchases">Create</label></div>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-switch"><input type="checkbox"
                                                                class="custom-control-input" id="show_purchases"
                                                                name="permissions[]" value="show_purchases" {{ in_array('show_purchases', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                                for="show_purchases">View</label></div>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-switch"><input type="checkbox"
                                                                class="custom-control-input" id="edit_purchases"
                                                                name="permissions[]" value="edit_purchases" {{ in_array('edit_purchases', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                                for="edit_purchases">Edit</label></div>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-switch"><input type="checkbox"
                                                                class="custom-control-input" id="delete_purchases"
                                                                name="permissions[]" value="delete_purchases" {{ in_array('delete_purchases', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                                for="delete_purchases">Delete</label></div>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-switch"><input type="checkbox"
                                                                class="custom-control-input" id="access_purchase_payments"
                                                                name="permissions[]" value="access_purchase_payments" {{ in_array('access_purchase_payments', old('permissions', []), true) ? 'checked' : '' }}><label
                                                                class="custom-control-label font-weight-bold"
                                                                for="access_purchase_payments">Payments</label></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="nested-sub-module" style="border-left-color: #321fdb;">
                                                <div class="nested-title"><i class="bi bi-arrow-counterclockwise"></i>
                                                    Purchase Returns</div>
                                                <div class="row">
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-switch"><input type="checkbox"
                                                                class="custom-control-input" id="access_purchase_returns"
                                                                name="permissions[]" value="access_purchase_returns" {{ in_array('access_purchase_returns', old('permissions', []), true) ? 'checked' : '' }}><label
                                                                class="custom-control-label"
                                                                for="access_purchase_returns">Access</label></div>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-switch"><input type="checkbox"
                                                                class="custom-control-input" id="create_purchase_returns"
                                                                name="permissions[]" value="create_purchase_returns" {{ in_array('create_purchase_returns', old('permissions', []), true) ? 'checked' : '' }}><label
                                                                class="custom-control-label"
                                                                for="create_purchase_returns">Create</label></div>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-switch"><input type="checkbox"
                                                                class="custom-control-input" id="show_purchase_returns"
                                                                name="permissions[]" value="show_purchase_returns" {{ in_array('show_purchase_returns', old('permissions', []), true) ? 'checked' : '' }}><label
                                                                class="custom-control-label"
                                                                for="show_purchase_returns">View</label></div>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-switch"><input type="checkbox"
                                                                class="custom-control-input" id="edit_purchase_returns"
                                                                name="permissions[]" value="edit_purchase_returns" {{ in_array('edit_purchase_returns', old('permissions', []), true) ? 'checked' : '' }}><label
                                                                class="custom-control-label"
                                                                for="edit_purchase_returns">Edit</label></div>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-switch"><input type="checkbox"
                                                                class="custom-control-input" id="delete_purchase_returns"
                                                                name="permissions[]" value="delete_purchase_returns" {{ in_array('delete_purchase_returns', old('permissions', []), true) ? 'checked' : '' }}><label
                                                                class="custom-control-label"
                                                                for="delete_purchase_returns">Delete</label></div>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-switch"><input type="checkbox"
                                                                class="custom-control-input"
                                                                id="access_purchase_return_payments" name="permissions[]"
                                                                value="access_purchase_return_payments" {{ in_array('access_purchase_return_payments', old('permissions', []), true) ? 'checked' : '' }}><label
                                                                class="custom-control-label font-weight-bold"
                                                                for="access_purchase_return_payments">Payments</label></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="panel-sales" role="tabpanel">
                                            <div class="permission-section-title">Sales Pipeline, Terminal Registers &
                                                Invoices</div>

                                            <div class="nested-sub-module">
                                                <div class="nested-title"><i class="bi bi-receipt"></i> Sales Ledger
                                                    Configurations</div>
                                                <div class="row">
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-switch"><input type="checkbox"
                                                                class="custom-control-input" id="access_sales"
                                                                name="permissions[]" value="access_sales" {{ in_array('access_sales', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                                for="access_sales">Access</label></div>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-switch"><input type="checkbox"
                                                                class="custom-control-input" id="create_sales"
                                                                name="permissions[]" value="create_sales" {{ in_array('create_sales', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                                for="create_sales">Create</label></div>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-switch"><input type="checkbox"
                                                                class="custom-control-input" id="show_sales"
                                                                name="permissions[]" value="show_sales" {{ in_array('show_sales', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                                for="show_sales">View</label></div>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-switch"><input type="checkbox"
                                                                class="custom-control-input" id="edit_sales"
                                                                name="permissions[]" value="edit_sales" {{ in_array('edit_sales', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                                for="edit_sales">Edit</label></div>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-switch"><input type="checkbox"
                                                                class="custom-control-input" id="delete_sales"
                                                                name="permissions[]" value="delete_sales" {{ in_array('delete_sales', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                                for="delete_sales">Delete</label></div>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-switch"><input type="checkbox"
                                                                class="custom-control-input" id="access_sale_payments"
                                                                name="permissions[]" value="access_sale_payments" {{ in_array('access_sale_payments', old('permissions', []), true) ? 'checked' : '' }}><label
                                                                class="custom-control-label font-weight-bold"
                                                                for="access_sale_payments">Payments</label></div>
                                                    </div>
                                                    <div class="col-md-12 mt-2">
                                                        <div class="custom-control custom-switch"><input type="checkbox"
                                                                class="custom-control-input" id="create_pos_sales"
                                                                name="permissions[]" value="create_pos_sales" {{ in_array('create_pos_sales', old('permissions', []), true) ? 'checked' : '' }}><label
                                                                class="custom-control-label font-weight-bold text-success"
                                                                for="create_pos_sales"><i class="bi bi-cpu"></i> Sales Terminal
                                                                Engine Control</label></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="nested-sub-module" style="border-left-color: #321fdb;">
                                                <div class="nested-title"><i class="bi bi-arrow-counterclockwise"></i>
                                                    Customer Sale Returns</div>
                                                <div class="row">
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-switch"><input type="checkbox"
                                                                class="custom-control-input" id="access_sale_returns"
                                                                name="permissions[]" value="access_sale_returns" {{ in_array('access_sale_returns', old('permissions', []), true) ? 'checked' : '' }}><label
                                                                class="custom-control-label"
                                                                for="access_sale_returns">Access</label></div>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-switch"><input type="checkbox"
                                                                class="custom-control-input" id="create_sale_returns"
                                                                name="permissions[]" value="create_sale_returns" {{ in_array('create_sale_returns', old('permissions', []), true) ? 'checked' : '' }}><label
                                                                class="custom-control-label"
                                                                for="create_sale_returns">Create</label></div>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-switch"><input type="checkbox"
                                                                class="custom-control-input" id="show_sale_returns"
                                                                name="permissions[]" value="show_sale_returns" {{ in_array('show_sale_returns', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                                for="show_sale_returns">View</label></div>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-switch"><input type="checkbox"
                                                                class="custom-control-input" id="edit_sale_returns"
                                                                name="permissions[]" value="edit_sale_returns" {{ in_array('edit_sale_returns', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                                for="edit_sale_returns">Edit</label></div>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-switch"><input type="checkbox"
                                                                class="custom-control-input" id="delete_sale_returns"
                                                                name="permissions[]" value="delete_sale_returns" {{ in_array('delete_sale_returns', old('permissions', []), true) ? 'checked' : '' }}><label
                                                                class="custom-control-label"
                                                                for="delete_sale_returns">Delete</label></div>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-switch"><input type="checkbox"
                                                                class="custom-control-input"
                                                                id="access_sale_return_payments" name="permissions[]"
                                                                value="access_sale_return_payments" {{ in_array('access_sale_return_payments', old('permissions', []), true) ? 'checked' : '' }}><label
                                                                class="custom-control-label font-weight-bold"
                                                                for="access_sale_return_payments">Payments</label></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @include('user::roles.partials.manufacturing-permissions')

                                        <div class="tab-pane fade" id="panel-expenses" role="tabpanel">
                                            <div class="permission-section-title">Operating Expenses Registry</div>
                                            <div class="row">
                                                <div class="col-md-4 mb-3">
                                                    <div class="custom-control custom-switch"><input type="checkbox"
                                                            class="custom-control-input" id="access_expenses"
                                                            name="permissions[]" value="access_expenses" {{ in_array('access_expenses', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                            for="access_expenses">Access</label></div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="custom-control custom-switch"><input type="checkbox"
                                                            class="custom-control-input" id="create_expenses"
                                                            name="permissions[]" value="create_expenses" {{ in_array('create_expenses', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                            for="create_expenses">Create</label></div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="custom-control custom-switch"><input type="checkbox"
                                                            class="custom-control-input" id="edit_expenses"
                                                            name="permissions[]" value="edit_expenses" {{ in_array('edit_expenses', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                            for="edit_expenses">Edit</label></div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="custom-control custom-switch"><input type="checkbox"
                                                            class="custom-control-input" id="delete_expenses"
                                                            name="permissions[]" value="delete_expenses" {{ in_array('delete_expenses', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                            for="delete_expenses">Delete</label></div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="custom-control custom-switch"><input type="checkbox"
                                                            class="custom-control-input" id="access_expense_categories"
                                                            name="permissions[]" value="access_expense_categories" {{ in_array('access_expense_categories', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                            for="access_expense_categories">Category</label></div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- ── Income / Earnings ──────────────────────────── --}}
                                        <div class="tab-pane fade" id="panel-income" role="tabpanel">
                                            <div class="permission-section-title">Earnings / Income Management</div>
                                            <div class="row">
                                                <div class="col-md-4 mb-3">
                                                    <div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="access_incomes" name="permissions[]" value="access_incomes" {{ in_array('access_incomes', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label" for="access_incomes">Access</label></div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="create_incomes" name="permissions[]" value="create_incomes" {{ in_array('create_incomes', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label" for="create_incomes">Create</label></div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="edit_incomes" name="permissions[]" value="edit_incomes" {{ in_array('edit_incomes', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label" for="edit_incomes">Edit</label></div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="delete_incomes" name="permissions[]" value="delete_incomes" {{ in_array('delete_incomes', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label" for="delete_incomes">Delete</label></div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="access_income_categories" name="permissions[]" value="access_income_categories" {{ in_array('access_income_categories', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label" for="access_income_categories">Category</label></div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- ── HRM ─────────────────────────────────────────── --}}
                                        <div class="tab-pane fade" id="panel-hrm" role="tabpanel">
                                            <div class="permission-section-title">Human Resource Management</div>

                                            <div class="nested-sub-module">
                                                <div class="nested-title"><i class="bi bi-person-badge"></i> Employees</div>
                                                <div class="row">
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="access_hrm" name="permissions[]" value="access_hrm" {{ in_array('access_hrm', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label" for="access_hrm">Access HRM</label></div>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="access_employees" name="permissions[]" value="access_employees" {{ in_array('access_employees', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label" for="access_employees">Access</label></div>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="create_employees" name="permissions[]" value="create_employees" {{ in_array('create_employees', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label" for="create_employees">Create</label></div>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="edit_employees" name="permissions[]" value="edit_employees" {{ in_array('edit_employees', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label" for="edit_employees">Edit</label></div>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="delete_employees" name="permissions[]" value="delete_employees" {{ in_array('delete_employees', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label" for="delete_employees">Delete</label></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="nested-sub-module" style="border-left-color:#e67e22;">
                                                <div class="nested-title"><i class="bi bi-clock-history"></i> Attendance, Overtime & Bonuses</div>
                                                <div class="row">
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="access_attendances" name="permissions[]" value="access_attendances" {{ in_array('access_attendances', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label" for="access_attendances">Attendance</label></div>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="access_overtimes" name="permissions[]" value="access_overtimes" {{ in_array('access_overtimes', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label" for="access_overtimes">Overtime</label></div>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="access_bonuses" name="permissions[]" value="access_bonuses" {{ in_array('access_bonuses', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label" for="access_bonuses">Bonuses</label></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="nested-sub-module" style="border-left-color:#27ae60;">
                                                <div class="nested-title"><i class="bi bi-cash-stack"></i> Payroll / Salary</div>
                                                <div class="row">
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="access_payrolls" name="permissions[]" value="access_payrolls" {{ in_array('access_payrolls', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label" for="access_payrolls">Access</label></div>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="create_payrolls" name="permissions[]" value="create_payrolls" {{ in_array('create_payrolls', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label" for="create_payrolls">Create</label></div>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="edit_payrolls" name="permissions[]" value="edit_payrolls" {{ in_array('edit_payrolls', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label" for="edit_payrolls">Edit</label></div>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="delete_payrolls" name="permissions[]" value="delete_payrolls" {{ in_array('delete_payrolls', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label" for="delete_payrolls">Delete</label></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="panel-parties" role="tabpanel">
                                            <div class="permission-section-title">Parties Registry Master Hub</div>

                                            <div class="nested-sub-module">
                                                <div class="nested-title"><i class="bi bi-person-workspace"></i> Customers
                                                    Registry (CRM Node)</div>
                                                <div class="row">
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-switch"><input type="checkbox"
                                                                class="custom-control-input" id="access_customers"
                                                                name="permissions[]" value="access_customers" {{ in_array('access_customers', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                                for="access_customers">Access Customers</label></div>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-switch"><input type="checkbox"
                                                                class="custom-control-input" id="create_customers"
                                                                name="permissions[]" value="create_customers" {{ in_array('create_customers', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                                for="create_customers">Create</label></div>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-switch"><input type="checkbox"
                                                                class="custom-control-input" id="show_customers"
                                                                name="permissions[]" value="show_customers" {{ in_array('show_customers', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                                for="show_customers">View</label></div>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-switch"><input type="checkbox"
                                                                class="custom-control-input" id="edit_customers"
                                                                name="permissions[]" value="edit_customers" {{ in_array('edit_customers', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                                for="edit_customers">Edit</label></div>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-switch"><input type="checkbox"
                                                                class="custom-control-input" id="delete_customers"
                                                                name="permissions[]" value="delete_customers" {{ in_array('delete_customers', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                                for="delete_customers">Delete Customer</label></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="nested-sub-module" style="border-left-color: #321fdb;">
                                                <div class="nested-title"><i class="bi bi-building-up"></i> Suppliers
                                                    Registry (Logistics Vendor Node)</div>
                                                <div class="row">
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-switch"><input type="checkbox"
                                                                class="custom-control-input" id="access_suppliers"
                                                                name="permissions[]" value="access_suppliers" {{ in_array('access_suppliers', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                                for="access_suppliers">Access Suppliers</label></div>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-switch"><input type="checkbox"
                                                                class="custom-control-input" id="create_suppliers"
                                                                name="permissions[]" value="create_suppliers" {{ in_array('create_suppliers', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                                for="create_suppliers">Create</label></div>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-switch"><input type="checkbox"
                                                                class="custom-control-input" id="show_suppliers"
                                                                name="permissions[]" value="show_suppliers" {{ in_array('show_suppliers', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                                for="show_suppliers">View</label></div>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-switch"><input type="checkbox"
                                                                class="custom-control-input" id="edit_suppliers"
                                                                name="permissions[]" value="edit_suppliers" {{ in_array('edit_suppliers', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                                for="edit_suppliers">Edit</label></div>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="delete_suppliers" name="permissions[]"
                                                                value="delete_suppliers" {{ in_array('delete_suppliers', old('permissions', []), true) ? 'checked' : '' }}>
                                                            <label class="custom-control-label "
                                                                for="delete_suppliers">Delete Supplier</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="panel-reports" role="tabpanel">
                                            <div class="permission-section-title">BI Reporting & Metrics Data</div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" class="custom-control-input"
                                                            id="access_reports" name="permissions[]" value="access_reports"
                                                            {{ in_array('access_reports', old('permissions', []), true) ? 'checked' : '' }}>
                                                        <label class="custom-control-label font-weight-bold text-dark"
                                                            for="access_reports">Access Master Business Reports
                                                            Engine</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="panel-users" role="tabpanel">
                                            <div class="permission-section-title">User Account Directory Infrastructure
                                            </div>

                                            <div class="col-md-12 p-0 mb-4">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input"
                                                        id="access_user_management" name="permissions[]"
                                                        value="access_user_management" {{ in_array('access_user_management', old('permissions', []), true) ? 'checked' : '' }}>
                                                    <label class="custom-control-label font-weight-bold text-primary"
                                                        for="access_user_management">Master Access: User Management
                                                        Directory</label>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="nested-sub-module">
                                                        <div class="nested-title"><i class="bi bi-person-plus"></i> Form
                                                            Node: Create User Directory</div>
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="edit_own_profile" name="permissions[]"
                                                                value="edit_own_profile" {{ in_array('edit_own_profile', old('permissions', []), true) ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="edit_own_profile">Own
                                                                Profile Configuration Update</label>
                                                        </div>
                                                        <div class="custom-control custom-switch mt-3">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="access_activity_log" name="permissions[]"
                                                                value="access_activity_log" {{ in_array('access_activity_log', old('permissions', []), true) ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="access_activity_log">
                                                                View Activity Log</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="nested-sub-module" style="border-left-color: #63c2de;">
                                                        <div class="nested-title"><i class="bi bi-shield-check"></i> Form
                                                            Node: Roles & Matrix Security Access</div>
                                                        <span class="text-muted small d-block mb-2">Granular credentials to
                                                            edit switchboard schemas are handled internally.</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="panel-settings" role="tabpanel">
                                            <div class="permission-section-title">System Settings, Localizations & Product
                                                Units Configuration</div>

                                            <div class="nested-sub-module" style="border-left-color: #321fdb;">
                                                <div class="nested-title"><i class="bi bi-calculator"></i>
                                                    Product Units Management</div>
                                                <div class="row">
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-switch"><input type="checkbox"
                                                                class="custom-control-input" id="access_units"
                                                                name="permissions[]" value="access_units" {{ in_array('access_units', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                                for="access_units">Access Units</label></div>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-switch"><input type="checkbox"
                                                                class="custom-control-input" id="create_units"
                                                                name="permissions[]" value="create_units" {{ in_array('create_units', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                                for="create_units">Create Unit</label></div>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-switch"><input type="checkbox"
                                                                class="custom-control-input" id="edit_units"
                                                                name="permissions[]" value="edit_units" {{ in_array('edit_units', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                                for="edit_units">Edit
                                                                Unit</label></div>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-switch"><input type="checkbox"
                                                                class="custom-control-input" id="delete_units"
                                                                name="permissions[]" value="delete_units" {{ in_array('delete_units', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                                for="delete_units">Delete Unit</label></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="nested-sub-module" style="border-left-color: #321fdb;">
                                                <div class="nested-title"><i class="bi bi-currency-exchange"></i>
                                                    Currency Configurations</div>
                                                <div class="row">
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-switch"><input type="checkbox"
                                                                class="custom-control-input" id="access_currencies"
                                                                name="permissions[]" value="access_currencies" {{ in_array('access_currencies', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                                for="access_currencies">Access Currencies</label></div>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-switch"><input type="checkbox"
                                                                class="custom-control-input" id="create_currencies"
                                                                name="permissions[]" value="create_currencies" {{ in_array('create_currencies', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                                for="create_currencies">Add Currency</label></div>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-switch"><input type="checkbox"
                                                                class="custom-control-input" id="edit_currencies"
                                                                name="permissions[]" value="edit_currencies" {{ in_array('edit_currencies', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                                for="edit_currencies">Edit Rates</label></div>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-switch"><input type="checkbox"
                                                                class="custom-control-input" id="delete_currencies"
                                                                name="permissions[]" value="delete_currencies" {{ in_array('delete_currencies', old('permissions', []), true) ? 'checked' : '' }}><label class="custom-control-label"
                                                                for="delete_currencies">Delete Currency</label></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="nested-sub-module" style="border-left-color: #321fdb;">
                                                <div class="nested-title"><i class="bi bi-gear"></i>
                                                    Main System Settings Dashboard</div>
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" id="access_settings"
                                                        name="permissions[]" value="access_settings" {{ in_array('access_settings', old('permissions', []), true) ? 'checked' : '' }}>
                                                    <label class="custom-control-label font-weight-bold"
                                                        for="access_settings">Master System Settings Access</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="panel-utilities" role="tabpanel">
                                            <div class="permission-section-title">System Utilities Control Panel</div>
                                            <div class="row">
                                                <div class="col-12 mb-3 ">
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" class="custom-control-input"
                                                            id="access_system_utilities" name="permissions[]"
                                                            value="access_system_utilities" {{ in_array('access_system_utilities', old('permissions', []), true) ? 'checked' : '' }}>
                                                        <label class="custom-control-label font-weight-bold text-dark"
                                                            for="access_system_utilities">Access System Utilities
                                                            Module</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" class="custom-control-input"
                                                            id="access_backup" name="permissions[]" value="access_backup" {{ in_array('access_backup', old('permissions', []), true) ? 'checked' : '' }}>
                                                        <label class="custom-control-label text-success font-weight-bold"
                                                            for="access_backup"><i class="bi bi-cloud-arrow-up-fill"></i>
                                                            Run Backup Operations</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" class="custom-control-input"
                                                            id="access_restore" name="permissions[]" value="access_restore"
                                                            {{ in_array('access_restore', old('permissions', []), true) ? 'checked' : '' }}>
                                                        <label class="custom-control-label text-warning font-weight-bold"
                                                            for="access_restore"><i class="bi bi-cloud-arrow-down-fill"></i>
                                                            Run Restore Operations</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="form-group d-flex justify-content-start mt-3 mb-0">
                                <button type="submit" class="btn btn-primary">Create Role <i class="bi bi-plus"></i></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

