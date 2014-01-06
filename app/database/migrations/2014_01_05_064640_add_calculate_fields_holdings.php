<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddCalculateFieldsHoldings extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('holdings', function(Blueprint $table) {
			$table->boolean('exists_online')->default(false);
			$table->boolean('is_current')->default(false);
			$table->boolean('has_incomplete_vols')->default(false);
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
			$table->dropColumn('exists_online');
			$table->dropColumn('is_current');
			$table->dropColumn('has_incomplete_vols');
		});
	}

}
