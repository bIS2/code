<?php

// set library_id in holding, search by sublibrary in f852b;

class SetLibraryIdToHoldingsSeeder extends Seeder {

    public function run() {

/*    	$holdings = Holding::select('id','f852b', 'library_id')->get();
			foreach ( $holdings as $holding) {
				$library = Library::where( 'sublibraries','like','%'.$holding->f852b.'%')->first(); 
				$holding->library_id = $library->id; 
				$holding->save();
			}
*/
			$libraries = Library::all();
			foreach ($libraries as $library) {
				DB::table('holdings')->whereIn('f852b', explode(',', $library->sublibraries))->update( ['library_id' => $library->id] );
			}
    }
}
