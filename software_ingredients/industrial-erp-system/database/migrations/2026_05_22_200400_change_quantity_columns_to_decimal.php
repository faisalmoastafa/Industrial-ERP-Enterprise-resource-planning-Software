<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::connection()->getDriverName() !== 'sqlite') {
            DB::statement('ALTER TABLE products MODIFY product_quantity DECIMAL(10, 3) NOT NULL');
            DB::statement('ALTER TABLE products MODIFY product_stock_alert DECIMAL(10, 3) NOT NULL');
            DB::statement('ALTER TABLE sale_details MODIFY quantity DECIMAL(10, 3) NOT NULL');
            DB::statement('ALTER TABLE purchase_details MODIFY quantity DECIMAL(10, 3) NOT NULL');
            DB::statement('ALTER TABLE purchase_return_details MODIFY quantity DECIMAL(10, 3) NOT NULL');
            DB::statement('ALTER TABLE sale_return_details MODIFY quantity DECIMAL(10, 3) NOT NULL');
            DB::statement('ALTER TABLE adjusted_products MODIFY quantity DECIMAL(10, 3) NOT NULL');
            DB::statement('ALTER TABLE quotation_details MODIFY quantity DECIMAL(10, 3) NOT NULL');
            DB::statement('ALTER TABLE units MODIFY operation_value DECIMAL(10, 3) NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE products MODIFY product_quantity INT NOT NULL');
        DB::statement('ALTER TABLE products MODIFY product_stock_alert INT NOT NULL');
        DB::statement('ALTER TABLE sale_details MODIFY quantity INT NOT NULL');
        DB::statement('ALTER TABLE purchase_details MODIFY quantity INT NOT NULL');
        DB::statement('ALTER TABLE purchase_return_details MODIFY quantity INT NOT NULL');
        DB::statement('ALTER TABLE sale_return_details MODIFY quantity INT NOT NULL');
        DB::statement('ALTER TABLE adjusted_products MODIFY quantity INT NOT NULL');
        DB::statement('ALTER TABLE quotation_details MODIFY quantity INT NOT NULL');
        DB::statement('ALTER TABLE units MODIFY operation_value INT NULL');
    }
};
