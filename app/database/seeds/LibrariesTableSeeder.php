<?php

class LibrariesTableSeeder extends Seeder {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		DB::table('libraries')->truncate();

		$libraries = array(
			['title'=>'Kantonsbibliothek Aargau','code'=>'AGKB', 'created_at'=>new DateTime, 'updated_at'=>new DateTime ],
			['title'=>'Universitätsbibliothek Basel','code'=>'BSUB', 'created_at'=>new DateTime, 'updated_at'=>new DateTime ],
			['title'=>'Zentralbibliothek Luzern','code'=>'LUZB', 'created_at'=>new DateTime, 'updated_at'=>new DateTime ],
			['title'=>'Universitätsbibliothek Zürich','code'=>'ZHUZ', 'created_at'=>new DateTime, 'updated_at'=>new DateTime ],
			['title'=>'Zentralbibliothek Zürich','code'=>'ZHZB', 'created_at'=>new DateTime, 'updated_at'=>new DateTime ],
			['title'=>'Speicherbibliothek','code'=>'SPEI', 'created_at'=>new DateTime, 'updated_at'=>new DateTime ],
		);

		// Uncomment the below to run the seeder
		DB::table('libraries')->insert($libraries);
	}

}
