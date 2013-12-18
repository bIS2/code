<?php

class LockedsTableSeeder extends Seeder {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		DB::table('lockeds')->truncate();

		$lockeds = array(

		);

		// Uncomment the below to run the seeder
		// DB::table('lockeds')->insert($lockeds);
	}

}
