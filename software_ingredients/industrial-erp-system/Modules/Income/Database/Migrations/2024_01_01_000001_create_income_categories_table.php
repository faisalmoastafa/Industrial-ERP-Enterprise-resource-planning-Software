<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncomeCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('income_categories', function (Blueprint $table) {
            $table->id();
            $table->string('category_name');
            $table->text('category_description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('income_categories');
    }
}
