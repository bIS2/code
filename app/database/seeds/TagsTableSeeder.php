<?php

class TagsTableSeeder extends Seeder {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		// DB::table('tags')->truncate();

		$tags = [
			[ 'name' => 'Missing' ],
			[ 'name' => 'Additional' ],
			[ 'name' => 'Deliverable' ],
			[ 'name' => 'In bad condition' ],
			[ 'name' => 'Remarks' ],
		];

				// Uncomment the below to run the seeder
		DB::table('tags')->insert($tags);
	}

}
