<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            if (!Schema::hasColumn('customers', 'opening_balance')) {
                $table->integer('opening_balance')->default(0)->after('address');
            }
        });

        Schema::table('suppliers', function (Blueprint $table) {
            if (!Schema::hasColumn('suppliers', 'opening_balance')) {
                $table->integer('opening_balance')->default(0)->after('address');
            }
        });

        Schema::create('party_payments', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->date('date');
            $table->string('party_type');
            $table->unsignedBigInteger('party_id');
            $table->string('party_name');
            $table->string('payment_type');
            $table->integer('amount');
            $table->string('payment_method')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index(['party_type', 'party_id']);
            $table->index(['date', 'payment_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('party_payments');

        Schema::table('suppliers', function (Blueprint $table) {
            if (Schema::hasColumn('suppliers', 'opening_balance')) {
                $table->dropColumn('opening_balance');
            }
        });

        Schema::table('customers', function (Blueprint $table) {
            if (Schema::hasColumn('customers', 'opening_balance')) {
                $table->dropColumn('opening_balance');
            }
        });
    }
};
