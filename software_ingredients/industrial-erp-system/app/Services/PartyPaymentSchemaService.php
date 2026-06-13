<?php

namespace App\Services;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PartyPaymentSchemaService
{
    public function ensure(): void
    {
        if (!Schema::hasColumn('customers', 'opening_balance')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->integer('opening_balance')->default(0)->after('address');
            });
        }

        if (!Schema::hasColumn('suppliers', 'opening_balance')) {
            Schema::table('suppliers', function (Blueprint $table) {
                $table->integer('opening_balance')->default(0)->after('address');
            });
        }

        if (!Schema::hasTable('party_payments')) {
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
    }
}
