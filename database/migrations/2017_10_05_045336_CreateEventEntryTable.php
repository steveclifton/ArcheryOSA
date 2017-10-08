<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventEntryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evententry', function (Blueprint $table) {
            $table->increments('evententryid');
            $table->string('eventid');
            $table->string('userid');
            $table->string('fullname')->nullable();
            $table->string('clubid')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('divisionid')->nullable();
            $table->string('membershipcode')->nullable();
            $table->string('status')->nullable();
            $table->string('enteredbyuserid')->nullable();
            $table->integer('paid')->default(0);
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
