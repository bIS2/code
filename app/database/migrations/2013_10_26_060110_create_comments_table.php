<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCommentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Schema::create('comments', function(Blueprint $table) {
		// 	$table->increments('id');
		// 	$table->integer('holding_id');
		// 	$table->integer('category_id');
		// 	$table->integer('user_id');
		// 	$table->text('comments');
		// 	$table->timestamps();
		// });
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('comments');
	}

}
