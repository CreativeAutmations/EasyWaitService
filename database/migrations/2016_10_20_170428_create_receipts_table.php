<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipts', function (Blueprint $table) {
		$table->increments('id');
		$table->string('bill_number')->unique();
		$table->date('bill_date');
		$table->string('b17_debit');
		$table->string('description');
		$table->string('invoice_no')->nullable();
		$table->date('invoice_date')->nullable();
		$table->string('procurement_certificate')->nullable();
		$table->date('procurement_date')->nullable();
		$table->string('unit_weight')->nullable();
		$table->string('unit_quantity')->nullable();
		$table->string('value');
		$table->string('duty');
		$table->string('transport_registration')->nullable();
		$table->string('receipt_timestamp')->nullable();
		$table->string('balance_quantity')->nullable();
		$table->string('balance_value')->nullable();
		$table->softDeletes();
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
        Schema::dropIfExists('receipts');
    }
}
