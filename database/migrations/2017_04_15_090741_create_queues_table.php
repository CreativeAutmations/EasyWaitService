<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQueuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('queues', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('current_position')->unsigned()->default(0);
            $table->integer('next_available_slot')->unsigned()->default(1);
            $table->integer('accepting_appointments')->unsigned()->default(0);
            $table->integer('initial_free_slots')->unsigned()->default(0);;
            $table->integer('recurring_free_slot')->unsigned()->default(0);;
            $table->dateTime('start_time')->nullable();
            $table->dateTime('update_time')->nullable();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('queues');
    }
}
