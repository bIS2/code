<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddNewFieldsToHoldings extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('holdings', function(Blueprint $table) {
			$table->string('f_tit')->nullable();
			$table->string('f260c')->nullable();
			$table->string('f710b')->nullable();
			$table->string('f245a_e')->nullable();
			$table->string('f245b_e')->nullable();
			$table->string('f245c_e')->nullable();
			$table->string('f_tit_e')->nullable();
			$table->string('f260a_e')->nullable();
			$table->string('f260b_e')->nullable();
			$table->string('f310a_e')->nullable();
			$table->string('f362a_e')->nullable();
			$table->string('f710a_e')->nullable();
			$table->string('f780t_e')->nullable();
			$table->string('f785t_e')->nullable();
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
			$table->string('f_tit')->nullable();
			$table->string('f260c')->nullable();
			$table->string('f710b')->nullable();
			$table->string('f245a_e')->nullable();
			$table->string('f245b_e')->nullable();
			$table->string('f245c_e')->nullable();
			$table->string('f_tit_e')->nullable();
			$table->string('f260a_e')->nullable();
			$table->string('f260b_e')->nullable();
			$table->string('f310a_e')->nullable();
			$table->string('f362a_e')->nullable();
			$table->string('f710a_e')->nullable();
			$table->string('f780t_e')->nullable();
			$table->string('f785t_e')->nullable();
		});
	}

}
