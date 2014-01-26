<?php
// error_reporting(E_ALL);
class SetLibraryIdToHoldingsSeeder extends Seeder {

    public function run() {

    	for ($i=0; $i < 117517; $i++) { 
			$holding = Holding::find($i); 
			$library = Library::whereCode($holding->hbib)->first();
			Holding::whereId($i)->update(['library_id' => $library->id]); 
    	}
    }
}