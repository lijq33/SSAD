<?php
/* Author: Li JinQuan */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCrisisAgenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crisis_agencies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('crisis_id')->unsigned();
            $table->foreign('crisis_id')
                ->references('id')->on('crises');
            $table->integer('agency_id')->unsigned();
            $table->foreign('agency_id')
                ->references('id')->on('agencies')
                ->onDelete('cascade');
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
        Schema::dropIfExists('crisis_agencies');
    }
}
