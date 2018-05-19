<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outboundemails', function (Blueprint $table) {
            $table->increments('emailid');
            $table->string('userid');
            $table->string('email');
            $table->string('eventid');
            $table->string('senderuserid');
            $table->string('message');
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
        //Schema::dropIfExists('email');
    }
}
