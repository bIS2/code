<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddFieldsStats extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('stats', function(Blueprint $table) {
			$table->integer('sets_confirmed')->default(0);
			$table->integer('sets_confirmed_owner')->default(0);
			$table->integer('sets_confirmed_auxiliar')->default(0);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('stats', function(Blueprint $table) {
			$table->dropColumn('sets_confirmed');
			$table->dropColumn('sets_confirmed_owner');
			$table->dropColumn('sets_confirmed_auxiliar');
		});
	}

}
