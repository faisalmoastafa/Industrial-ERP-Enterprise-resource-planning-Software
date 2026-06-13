<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds gross_profit to sale_details so per-line profit is frozen at sale time.
 * Formula: (unit_price - cost_price) * quantity, stored as cents.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('sale_details', 'gross_profit')) {
            Schema::table('sale_details', function (Blueprint $table) {
                $table->bigInteger('gross_profit')->default(0)->after('cost_price');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('sale_details', 'gross_profit')) {
            Schema::table('sale_details', function (Blueprint $table) {
                $table->dropColumn('gross_profit');
            });
        }
    }
};
