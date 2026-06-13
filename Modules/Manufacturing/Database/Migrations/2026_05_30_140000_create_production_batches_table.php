<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('production_batches', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->date('date');
            $table->string('status')->default('Completed');
            $table->decimal('input_weight', 12, 3)->default(0);
            $table->decimal('output_weight', 12, 3)->default(0);
            $table->decimal('wastage_weight', 12, 3)->default(0);
            $table->unsignedBigInteger('raw_material_cost')->default(0);
            $table->unsignedBigInteger('conversion_cost')->default(0);
            $table->unsignedBigInteger('total_cost')->default(0);
            $table->unsignedBigInteger('cost_per_output_kg')->default(0);
            $table->text('note')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('production_batches');
    }
};
