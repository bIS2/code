<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class PivotHlistHoldingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('hlist_holding', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('hlist_id')->unsigned()->index();
			$table->integer('holding_id')->unsigned()->index();
			$table->foreign('hlist_id')->references('id')->on('hlists')->onDelete('cascade');
			$table->foreign('holding_id')->references('id')->on('holdings')->onDelete('cascade');
		});
	}



	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('hlist_holding');
	}

}
