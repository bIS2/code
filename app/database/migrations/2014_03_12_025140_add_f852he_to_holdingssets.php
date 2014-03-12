<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;	

class AddF852heToHoldingssets extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('holdingssets', function(Blueprint $table) {
			$table->string('f852h_e')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('holdingssets', function(Blueprint $table) {
			$table->dropColumn('f852h_e');
		});
	}

}
