<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        // Clear Spatie's internal memory cache to prevent stale tracking bugs
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // User Management
            'edit_own_profile',
            'access_user_management',
            'access_activity_log',
            // Dashboard
            'show_total_stats',
            'show_month_overview',
            'show_weekly_sales_purchases',
            'show_monthly_cashflow',
            'show_notifications',
            // Products
            'access_products',
            'create_products',
            'show_products',
            'edit_products',
            'delete_products',
            'access_product_categories',
            'print_barcodes',
            // Adjustments
            'access_adjustments',
            'create_adjustments',
            'show_adjustments',
            'edit_adjustments',
            'delete_adjustments',
            // Quotations
            'access_quotations',
            'create_quotations',
            'show_quotations',
            'edit_quotations',
            'delete_quotations',
            'create_quotation_sales',
            'send_quotation_mails',
            // Expenses
            'access_expenses',
            'create_expenses',
            'edit_expenses',
            'delete_expenses',
            'access_expense_categories',
            // Customers
            'access_customers',
            'create_customers',
            'show_customers',
            'edit_customers',
            'delete_customers',
            // Suppliers
            'access_suppliers',
            'create_suppliers',
            'show_suppliers',
            'edit_suppliers',
            'delete_suppliers',
            // Sales & Billing
            'access_sales',
            'create_sales',
            'show_sales',
            'edit_sales',
            'delete_sales',
            'print_sales',
            'create_pos_sales',
            'access_sale_payments',
            // Sale Returns
            'access_sale_returns',
            'create_sale_returns',
            'show_sale_returns',
            'edit_sale_returns',
            'delete_sale_returns',
            'access_sale_return_payments',
            'edit_sale_amount_received',
            // Purchases
            'access_purchases',
            'create_purchases',
            'show_purchases',
            'edit_purchases',
            'delete_purchases',
            'print_purchases',
            'access_purchase_payments',
            'edit_purchase_amount_received',
            // Purchase Returns
            'access_purchase_returns',
            'create_purchase_returns',
            'show_purchase_returns',
            'edit_purchase_returns',
            'delete_purchase_returns',
            'access_purchase_return_payments',
            // Manufacturing
            'access_manufacturing',
            'create_production_batches',
            'edit_production_batches',
            'show_production_batches',
            'delete_production_batches',
            // Reports
            'access_reports',
            // Currencies
            'access_currencies',
            'create_currencies',
            'edit_currencies',
            'delete_currencies',
            // Settings
            'access_settings',
            // Units (Restored Permanently)
            'access_units',
            'create_units',
            'edit_units',
            'delete_units',
            // System Utilities
            'access_system_utilities',
            'access_backup',
            'access_restore',
            // Income
            'access_incomes',
            'create_incomes',
            'edit_incomes',
            'delete_incomes',
            'access_income_categories',
            // HRM
            'access_hrm',
            'access_employees',
            'create_employees',
            'edit_employees',
            'delete_employees',
            'access_overtimes',
            'access_attendances',
            'access_bonuses',
            'access_payrolls',
            'create_payrolls',
            'edit_payrolls',
            'delete_payrolls'
        ];

        // 1. Create permissions only if they don't already exist
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Setup the Roles safely without duplicate key exceptions
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions(
            Permission::whereIn('name', $permissions)->where('guard_name', 'web')->get()
        ); // Super Admin gets EVERYTHING

        $admin = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        // Custom Admin permissions mapping (Modify this list as needed!)
        $adminPermissions = array_filter(
            $permissions,
            fn($p) =>
            $p !== 'access_user_management' && $p !== 'access_activity_log' && $p !== 'access_system_utilities'
        );
        $admin->syncPermissions(
            Permission::whereIn('name', $adminPermissions)->where('guard_name', 'web')->get()
        );

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
