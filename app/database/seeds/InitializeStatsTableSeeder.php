<?php

class InitializeStatsTableSeeder extends Seeder {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		DB::table('stats')->truncate();

		$initializestats = array(
			'hodings_count' 					=> Holding::count(),
			'sets_count' 							=> Holdingsset::count(),
			'sets_grouped' 						=> Holdingsset::where('groups_number','>',0)->count(),
			'sets_confirmed'					=> Holdingsset::confirmed()->count(),
			'sets_confirmed_owner'		=> Holdingsset::owners()->confirmed()->count(),
			'sets_confirmed_auxiliar'	=> Holdingsset::auxiliars()->confirmed()->count(),
		);

		DB::table('stats')->insert($initializestats);

		foreach ( Library::all() as $library) {

			$holdings_in_library = Holding::inLibrary($library->id);
			$stats_libraries = [
				'holdings_annotated'				=> Holding::inLibrary($library->id)->withState('annotated')->count(),
				'holdings_ok'								=> Holding::inLibrary($library->id)->withState('ok')->count(),
				'holdings_revised_ok'				=> Holding::inLibrary($library->id)->withState('revised_ok')->count(),
				'holdings_revised_annotated'=> Holding::inLibrary($library->id)->withState('revised_annotated')->count(),
				'holdings_in_list'					=> DB::table('holdings')->select('id')->distinct()
																						->join('hlist_holding','holdings.id','=','hlist_holding.holding_id')
																						->join('hlists','hlists.id','=','hlist_holding.hlist_id')
																						->where('holdings.library_id','=',$library->id)
																						->count()
			];
			# code...
			$library->update($stats_libraries);
		}
		
		// Uncomment the below to run the seeder
	}

}
