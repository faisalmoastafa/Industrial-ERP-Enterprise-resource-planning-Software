<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOvertimesTable extends Migration
{
    public function up()
    {
        Schema::create('overtimes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->date('date');
            $table->decimal('hours', 8, 2)->default(0);
            $table->integer('rate_per_hour')->default(0);
            $table->integer('amount')->default(0);
            $table->foreign('employee_id')->references('id')->on('employees')->restrictOnDelete();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('overtimes');
    }
}
