<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayrollsTable extends Migration
{
    public function up()
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->date('period_start');
            $table->date('period_end');
            $table->integer('base_pay')->default(0);
            $table->integer('overtime_pay')->default(0);
            $table->integer('bonus_pay')->default(0);
            $table->integer('deductions')->default(0);
            $table->integer('advance_deduction')->default(0);
            $table->integer('total_paid')->default(0);
            $table->string('payment_method')->nullable();
            $table->text('note')->nullable();
            $table->foreign('employee_id')->references('id')->on('employees')->restrictOnDelete();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payrolls');
    }
}
