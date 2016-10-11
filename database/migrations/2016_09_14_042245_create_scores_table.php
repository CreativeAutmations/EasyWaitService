<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scores', function (Blueprint $table) {
	$table->string('candidate_id');
	$table->string('assessment');
	$table->string('attribiute');
	$table->string('value');
	$table->string('comments')->nullable();
            $table->timestamps();
	$table->primary(array('candidate_id', 'assessment', 'attribiute'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scores');
    }
}
