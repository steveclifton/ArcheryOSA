<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTargetAllocationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('targetallocation', function (Blueprint $table) {
            $table->increments('targetallocationid');
            $table->integer('userid');
            $table->integer('eventid');
            $table->integer('evententryid');
            $table->integer('eventroundid');
            $table->string('spot');
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
        Schema::dropIfExists('target_allocation');
    }
}
