<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('userid');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('anz')->nullable();
            $table->string('club')->nullable();
            $table->string('phone')->nullable();
            $table->integer('usertype');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('remember_token')->unique()->nullable();
            $table->string('image')->unique()->nullable();
            $table->string('lastipaddress');
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
        Schema::drop('users');
    }
}
