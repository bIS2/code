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
			$table->string('f245p')->nullable();
			$table->string('f245n')->nullable();
			$table->string('f852h_e')->nullable();
			$table->string('years')->nullable();
			$table->string('f072a')->nullable();
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
