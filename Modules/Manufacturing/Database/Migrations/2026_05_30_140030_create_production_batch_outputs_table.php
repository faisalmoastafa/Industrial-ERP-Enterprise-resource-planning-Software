<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('production_batch_outputs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('production_batch_id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('product_name');
            $table->string('product_code')->nullable();
            $table->string('wire_size')->nullable();
            $table->decimal('quantity', 12, 3);
            $table->unsignedBigInteger('unit_cost')->default(0);
            $table->unsignedBigInteger('sub_total')->default(0);
            $table->timestamps();

            $table->foreign('production_batch_id')->references('id')->on('production_batches')->cascadeOnDelete();
            $table->foreign('product_id')->references('id')->on('products')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('production_batch_outputs');
    }
};
