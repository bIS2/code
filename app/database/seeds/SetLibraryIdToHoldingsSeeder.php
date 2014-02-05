<?php

// error_reporting(E_ALL);

class SetLibraryIdToHoldingsSeeder extends Seeder {

    public function run() {

    	$holdings = Holding::select('id','f852b', 'library_id')->get();
			foreach ( $holdings as $holding) {
				$library = Library::where( 'sublibraries','like','%'.$holding->f852b.'%')->first(); 
				$holding->library_id = $library->id; 
				$holding->save();
			}
   //  	for ($i=0; $i < 117517; $i++) { 
			// $holding = Holding::find($i); 
			// $library = Library::whereCode($holding->hbib)->first();
			// Holding::whereId($i)->update(['library_id' => $library->id]);
   //  	}
    }
}