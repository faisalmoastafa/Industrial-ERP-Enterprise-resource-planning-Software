<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendancesTable extends Migration
{
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->date('date');
            $table->enum('status', ['present', 'absent', 'leave'])->default('present');
            $table->text('note')->nullable();
            $table->foreign('employee_id')->references('id')->on('employees')->restrictOnDelete();
            $table->unique(['employee_id', 'date']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendances');
    }
}
