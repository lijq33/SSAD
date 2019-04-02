<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportCrisesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_crises', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('telephone_number');
            $table->integer('postal_code');
            $table->date('date');
            $table->time('time');
                
            $table->longText('description')->nullable();
            $table->string('address');
    
            $table->string('crisis_type');
    
            $table->string('image')->nullable(); 
            $table->integer('radius')->unsigned()->nullable();
    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('report_crises');
    }
}
