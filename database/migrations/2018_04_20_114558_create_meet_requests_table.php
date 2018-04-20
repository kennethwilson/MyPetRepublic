<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeetRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meet_requests', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('requester_dog_id')->unsigned();
            $table->foreign('requester_dog_id')
              ->references('id')->on('doggies')
              ->onDelete('cascade');

            $table->integer('requested_dog_id')->unsigned();
            $table->foreign('requested_dog_id')
              ->references('id')->on('doggies')
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
        Schema::dropIfExists('meet_requests');
    }
}
