<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('production_batch_expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('production_batch_id');
            $table->string('name');
            $table->unsignedBigInteger('amount')->default(0);
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('production_batch_id')->references('id')->on('production_batches')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('production_batch_expenses');
    }
};
