<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddStatsToLibraries extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('libraries', function(Blueprint $table) {
			$table->integer('holdings_annotated')->default(0);
			$table->integer('holdings_ok')->default(0);
			$table->integer('holdings_revised_ok')->default(0);
			$table->integer('holdings_revised_annotated')->default(0);
			$table->integer('holdings_in_list')->default(0);
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
		Schema::table('libraries', function(Blueprint $table) {
			$table->dropColumn('holdings_annotated');
			$table->dropColumn('holdings_ok');
			$table->dropColumn('holdings_reviseds_ok');
			$table->dropColumn('holdings_reviseds_annotated');
			$table->dropColumn('holdings_unlist');
			$table->dropColumn('sets_confirmeds');
			$table->dropColumn('sets_confirmed');
			$table->dropColumn('sets_confirmed_owner');
			$table->dropColumn('sets_confirmed_auxiliar');
		});
	}

}
