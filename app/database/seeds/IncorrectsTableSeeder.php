<?php

class IncorrectsTableSeeder extends Seeder {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		DB::table('incorrects')->truncate();

		$incorrects = array(

		);

		// Uncomment the below to run the seeder
		// DB::table('incorrects')->insert($incorrects);
	}

}
