<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('eventid');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('eventtype')->nullable();
            $table->string('status');
            $table->string('closeentry');
            $table->string('contact')->nullable();
            $table->string('startdate');
            $table->string('enddate');
            $table->string('daycount');
            $table->string('createdby');
            $table->string('hostclub')->nullable();
            $table->string('location')->nullable();
            $table->string('cost')->nullable();
            $table->text('schedule')->nullable();
            $table->tinyInteger('deleted')->default(0);
            $table->tinyInteger('visible')->default(0);
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
        //
    }
}
