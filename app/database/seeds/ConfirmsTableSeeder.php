<?php

class ConfirmsTableSeeder extends Seeder {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		DB::table('confirms')->truncate();

		$confirms = array(

		);

		// Uncomment the below to run the seeder
		// DB::table('confirms')->insert($confirms);
	}

}
