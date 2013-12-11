<?php

class SetLibraryIdToHoldingsSeeder extends Seeder {

    public function run()
    {
    	$holdings = Holding::all();
    	print "hola";
			foreach ( $holdings as $holding) {

				$library = Library::where( 'sublibraries','like','%'.$holding->f852b.'%')->first();

				$holding->library_id = $library->id; 
				print $holding->f852b;
				$holding->save();
			}
    }
}

