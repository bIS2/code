<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddTracesFieldsToTraces extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('traces', function(Blueprint $table) {
			$table->text('object_type')->nullable();
			$table->integer('object_id')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('traces', function(Blueprint $table) {
			$table->text('object_type');
			$table->integer('object_id');
		});
	}

}
