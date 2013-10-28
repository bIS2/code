<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddHoldingssetsCount extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('holdingssets_count', function(Blueprint $table) {
			$table->integer('holdingssets_count')->default(0);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('holdingssets_count', function(Blueprint $table) {
			$table->dropColumn('holdingssets_count');
		});
	}

}
