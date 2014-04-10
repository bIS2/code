<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class RenameHoldingIdToDeliveries extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('deliveries', function($table)
		{
		    $table->renameColumn('holding_id', 'hlist_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('deliveries', function($table)
		{
		    $table->renameColumn('hlist_id', 'holding_id');
		});
	}

}
