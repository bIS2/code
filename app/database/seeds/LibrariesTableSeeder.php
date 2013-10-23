<?php

class LibrariesTableSeeder extends Seeder {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		// DB::table('libraries')->truncate();

		$libraries = array(
			['title'=>'library 1', 'created_at'=>new DateTime, 'updated_at'=>new DateTime ],
			['title'=>'library 2', 'created_at'=>new DateTime, 'updated_at'=>new DateTime ],
			['title'=>'library 3', 'created_at'=>new DateTime, 'updated_at'=>new DateTime ],
			['title'=>'library 4', 'created_at'=>new DateTime, 'updated_at'=>new DateTime ],
			['title'=>'library 5', 'created_at'=>new DateTime, 'updated_at'=>new DateTime ],
		);

		// Uncomment the below to run the seeder
		DB::table('libraries')->insert($libraries);
	}

}
