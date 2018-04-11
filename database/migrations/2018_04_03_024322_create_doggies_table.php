<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDoggiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doggies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',255);
            $table->integer('age')->unsigned();
            $table->string('desc',255);
            $table->string('breed',50);
            $table->integer('owner_id')->unsigned();
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('displaypic',255)->default("default2.jpg");
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('doggies');
    }
}
