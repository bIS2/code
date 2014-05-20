<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddSetsAnnotatedToStats extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('stats', function(Blueprint $table) {
			$table->integer('sets_annotated')->default(0);
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
			$table->dropColumn('sets_annotated');
		});
	}

}
