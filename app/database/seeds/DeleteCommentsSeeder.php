<?php

class DeleteCommentsSeeder extends Seeder {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		// DB::table('comments')->truncate();
		Schema::drop('comments');
		$comments = array(

		);

		// Uncomment the below to run the seeder
		// DB::table('comments')->insert($comments);
	}

}
