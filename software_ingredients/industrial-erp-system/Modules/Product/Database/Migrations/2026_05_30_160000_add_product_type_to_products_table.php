<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('products', 'product_type')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('product_type')->default('finished')->after('category_id');
            });
        }

        DB::table('products')
            ->whereNull('product_type')
            ->orWhere('product_type', '')
            ->update(['product_type' => 'finished']);
    }

    public function down(): void
    {
        if (Schema::hasColumn('products', 'product_type')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('product_type');
            });
        }
    }
};
