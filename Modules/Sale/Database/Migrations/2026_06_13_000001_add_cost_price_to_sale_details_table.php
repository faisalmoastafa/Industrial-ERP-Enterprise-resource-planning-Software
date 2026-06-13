<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds cost_price to sale_details so COGS is captured at the moment of sale.
 * Without this column, product_cost changes after a new production batch would
 * silently overwrite the historical cost basis for all previous sales.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('sale_details', 'cost_price')) {
            Schema::table('sale_details', function (Blueprint $table) {
                // Stored as cents like every other money column in this system.
                $table->unsignedBigInteger('cost_price')->default(0)->after('unit_price');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('sale_details', 'cost_price')) {
            Schema::table('sale_details', function (Blueprint $table) {
                $table->dropColumn('cost_price');
            });
        }
    }
};
