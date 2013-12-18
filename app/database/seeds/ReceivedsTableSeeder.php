<?php

class ReceivedsTableSeeder extends Seeder {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		DB::table('receiveds')->truncate();

		$receiveds = array(

		);

		// Uncomment the below to run the seeder
		// DB::table('receiveds')->insert($receiveds);
	}

}
