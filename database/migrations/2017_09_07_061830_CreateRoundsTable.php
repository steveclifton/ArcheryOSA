<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rounds', function (Blueprint $table) {
            $table->increments('roundid');
            $table->string('name');
            $table->string('code');
            $table->string('unit');
            $table->text('description')->nullable();
            $table->integer('dist1')->nullable();
            $table->integer('dist1max')->nullable();
            $table->integer('dist2')->nullable();
            $table->integer('dist2max')->nullable();
            $table->integer('dist3')->nullable();
            $table->integer('dist3max')->nullable();
            $table->integer('dist4')->nullable();
            $table->integer('dist4max')->nullable();
            $table->integer('totalmax')->nullable();

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
