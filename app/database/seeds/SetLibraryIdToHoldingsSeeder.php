<?php

class SetLibraryIdToHoldingsSeeder extends Seeder {

    public function run()
    {
    	$holdings = Holding::all();
			foreach ( $holdings as $holding) {

				$library = Library::where( 'sublibraries','like','%'.$holding->f852b.'%')->first();
<<<<<<< HEAD

				$holding->library_id = $library->id; 
				print $library->code;
				$holding->save();
=======
				$holding->library_id = $library->id; 
				//print $library->code;
				$holding->save();

>>>>>>> 86da6699d295af34ca3376881e7d69712455ac23
			}
    }
}

