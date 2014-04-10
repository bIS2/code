<?php

class TagsTableSeeder extends Seeder {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		DB::table('tags')->delete();

		$tags = [
			[ 'name' => 'missing' ],
			[ 'name' => 'aditional' ],
			[ 'name' => 'bad' ],
			[ 'name' => 'remarks' ],
		];

				// Uncomment the below to run the seeder
		DB::table('tags')->insert($tags);
	}

}
