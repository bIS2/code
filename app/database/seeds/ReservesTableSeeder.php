<?php

class ReservesTableSeeder extends Seeder {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		DB::table('reserves')->truncate();

		$reserves = array(

		);

		// Uncomment the below to run the seeder
		// DB::table('reserves')->insert($reserves);
	}

}
