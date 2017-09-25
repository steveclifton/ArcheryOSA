<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventRoundTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eventrounds', function (Blueprint $table) {
            $table->increments('eventroundid');
            $table->string('eventid');
            $table->string('name');
            $table->string('startdate');
            $table->string('location')->nullable();
            $table->text('schedule')->nullable();
            $table->string('roundid')->nullable()->default('0');
            $table->string('organisationid')->nullable()->default('0');
            $table->string('divisions')->nullable();
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
