<?php

class SetLibraryIdToHoldingsSeeder extends Seeder {

    public function run()
    {
    	$holdings = Holding::all();
			foreach ( $holdings as $holding) {

				$library = Library::where( 'sublibraries','like','%'.$holding->f852b.'%')->first();

				$holding->library_id = $library->id; 
				print $library->code;
				$holding->save();
			}
    }
}

