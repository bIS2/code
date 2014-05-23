<?php

class UpdateStateHlistsTableSeeder extends Seeder {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		// DB::table('updatestatehlists')->truncate();

		Hlist::whereIn('id',function($query){ $query->select('hlist_id')->from('deliveries')->lists('hlist_id'); } )->update(['state'=>'delivery']);
		$updatestatehlists = array(

		);

		// Uncomment the below to run the seeder
		// DB::table('updatestatehlists')->insert($updatestatehlists);
	}

}
