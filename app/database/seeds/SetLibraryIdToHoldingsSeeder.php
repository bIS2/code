<?php
error_reporting(E_ALL);
class SetLibraryIdToHoldingsSeeder extends Seeder {

    public function run() {

    	$holdings = Holding::select('id','f852b', 'library_id')->get();
			foreach ( $holdings as $holding) {
				$library = Library::where( 'sublibraries','like','%'.$holding->f852b.'%')->first(); 
				$holding->library_id = $library->id; 
				$holding->save();
			}
    }
}