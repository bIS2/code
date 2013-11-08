<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class PivotHoldingTagTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('holding_tag', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('holding_id')->unsigned()->index();
			$table->integer('tag_id')->unsigned()->index();
			$table->string('content')->nullable();
			$table->foreign('holding_id')->references('id')->on('holdings')->onDelete('cascade');
			$table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
		});
	}



	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('holding_tag');
	}

}
