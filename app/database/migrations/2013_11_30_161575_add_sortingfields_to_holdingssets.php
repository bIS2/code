<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddSortingfieldsToHoldingssets extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('holdingssets', function(Blueprint $table) {
			$table->integer('holdings_number')->default(0);
			$table->integer('groups_number')->default(0);
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
			$table->dropColumn('holdings_number');
			$table->dropColumn('groups_number');
		});
	}

}
