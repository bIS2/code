<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddFieldsToHoldings extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('holdings', function(Blueprint $table) {
			$table->string('245p')->nullable();
			$table->string('245n')->nullable();
			$table->string('852h_e')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('holdings', function(Blueprint $table) {
			$table->dropColumn('245p');
			$table->dropColumn('245n');
			$table->dropColumn('852h_e');
		});
	}

}
