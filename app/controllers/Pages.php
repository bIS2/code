<?php
/*
*
*	Controls workflow with Pages.
*
*/
class Pages extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex(){
		if  (Input::has('lang')) return Redirect::to(Request::header('referer'));

		$data['libraries'] 				= Library::all();
		$library_id = (Input::has('library_id')) ? Input::get('library_id') : null;

		$holdings 						= Holding::inLibrary($library_id);
		$holdings_ok 					= State::inLibrary($library_id)->whereState('ok');
		$holdings_annotated 	= State::inLibrary($library_id)->whereState('annotated');
		$holdingsset_confirm 	= Confirm::take(10);

		if ( Input::has('month') && (Input::get('month')!='*') ) {
			$holdings_ok 			= $holdings_ok->where('month(created_at)','=',Input::get('month'));
			$holdings_annotated 	= $holdings_annotated->where('month(created_at)','=',Input::get('month'));
			$holdingsset_confirm	= $holdingsset_confirm->where('month(created_at)','=',Input::get('month'));
		}

		if (Input::has('year') && (Input::get('year')!='*')) {
			$holdings_ok 			= $holdings_ok->where('year(created_at)','=',Input::get('year'));
			$holdings_annotated 	= $holdings_annotated->where('year(created_at)','=',Input::get('year'));
			$holdingsset_confirm 	= $holdingsset_confirm->where('year(created_at)','=',Input::get('year'));
		}

		$data['holdingsset_confirm'] 	= $holdingsset_confirm->get();
		$data['holdings_ok'] 			= $holdings_ok->take(10)->get();
		$data['holdings_annotated'] 	= $holdings_annotated->take(10)->get();

		$data['total'] 					= $holdings->count();
		$data['total_ok'] 				= $holdings_ok->count();
		$data['total_anottated'] 		= $holdings_annotated->count();

/*
		$holdings_total = Holding::select(DB::raw('states.state as state, count(*) as count'))
							->join('states','holdings.id','=','states.holding_id')
							->groupBy('states.state')
							->get()->toArray();
		$holdings_confirmed 	=  Holding::countState('confirmed')->get()->toArray();
		$holdings_sent 			=  Holding::countState('sent')->get()->toArray();
		$holdings_integreted 	=  Holding::countState('integrated')->get()->toArray();
		$holdings_revised		=  Holding::countState('revised')->get()->toArray();
		$holdings_trashed 		=  Holding::countState('trash')->get()->toArray();
		$holdings_eliminated 	=  Holding::countState('burn')->get()->toArray();

		$data['holdings_confirmed'] = Holding::countState('confirmed')->get()->toArray();


		$list = [
			['State',					'pending', 'confirmed',	'sent',	'integrated',	'revised',	'trashed',	'eliminated'],
			['Total del bIS',	
				$this->search_by_state($holdings_total, 'pending'),	
				$this->search_by_state($holdings_total, 'confirmed'),	
				$this->search_by_state($holdings_total, 'sent'),	
				$this->search_by_state($holdings_total, 'integrated'),	
				$this->search_by_state($holdings_total, 'revised_ok') + $this->search_by_state($holdings_total, 'revised_annotated'),	
				$this->search_by_state($holdings_total, 'trash'),	
				$this->search_by_state($holdings_total, 'burn'),	

			],

			[	'ABKB',
				'0',
				$this->search_by_library($holdings_confirmed, 'ABKB'),	
				$this->search_by_library($holdings_sent, 'ABKB'),	
				$this->search_by_library($holdings_integrated, 'ABKB'),	
				$this->search_by_library($holdings_revised, 'ABKB'),	
				$this->search_by_library($holdings_trashed, 'ABKB'),	
				$this->search_by_library($holdings_eliminated, 'ABKB'),	
			],

			[	'LUZB',
				'0', 
				$this->search_by_library($holdings_confirmed, 'LUZB'),	
				$this->search_by_library($holdings_sent, 'LUZB'),	
				$this->search_by_library($holdings_integrated, 'LUZB'),	
				$this->search_by_library($holdings_revised, 'LUZB'),	
				$this->search_by_library($holdings_trashed, 'LUZB'),	
				$this->search_by_library($holdings_eliminated, 'LUZB'),	
			],

			[	'BSUB',
				'0', 
				$this->search_by_library($holdings_confirmed, 'BSUB'),	
				$this->search_by_library($holdings_sent, 'BSUB'),	
				$this->search_by_library($holdings_integrated, 'BSUB'),	
				$this->search_by_library($holdings_revised, 'BSUB'),	
				$this->search_by_library($holdings_trashed, 'BSUB'),	
				$this->search_by_library($holdings_eliminated, 'BSUB'),	
			],

			[	'ZHUB',
				'0', 
				$this->search_by_library($holdings_confirmed, 'ZHUB'),	
				$this->search_by_library($holdings_sent, 'ZHUB'),	
				$this->search_by_library($holdings_integrated, 'ZHUB'),	
				$this->search_by_library($holdings_revised, 'ZHUB'),	
				$this->search_by_library($holdings_trashed, 'ZHUB'),	
				$this->search_by_library($holdings_eliminated, 'ZHUB'),	
			],

			[	'ZHZB',
				'0', 
				$this->search_by_library($holdings_confirmed, 'ZHZB'),	
				$this->search_by_library($holdings_sent, 'ZHZB'),	
				$this->search_by_library($holdings_integrated, 'ZHZB'),	
				$this->search_by_library($holdings_revised, 'ZHZB'),	
				$this->search_by_library($holdings_trashed, 'ZHZB'),	
				$this->search_by_library($holdings_eliminated, 'ZHZB'),	
			]
		];

		$fp = fopen('BIS.csv', 'w+');

		foreach ($list as $fields) {
			fputcsv($fp, $fields);
		}

		fclose($fp);

*/
		return View::make('pages.index', $data);
	}

	public function getHelp(){
		return View::make('pages.help');
	}

	public function getStats(){

		$holdings_confirmed 		=  Holding::countState('confirmed')->get()->toArray();
		$holdings_sent 					=  Holding::countState('sent')->get()->toArray();
		$holdings_integreted 		=  Holding::countState('integrated')->get()->toArray();
		$holdings_revised				=  Holding::countState('revised')->get()->toArray();
		$holdings_trashed 			=  Holding::countState('trash')->get()->toArray();
		$holdings_eliminated 		=  Holding::countState('burn')->get()->toArray();
		// echo var_dump($holdings_confirmed );

		$confirmeds = [ 
					[1,$this->search_by_library($holdings_confirmed, 'ABKB')],	
					[2,$this->search_by_library($holdings_confirmed, 'LUZB')],	
					[3,$this->search_by_library($holdings_confirmed, 'BSUB')],	
					[4,$this->search_by_library($holdings_confirmed, 'ZHUB')],	
					[5,$this->search_by_library($holdings_confirmed, 'ZHZB')],	
				];

		$sents = [ 
					[1,$this->search_by_library($holdings_sent, 'ABKB')],	
					[2,$this->search_by_library($holdings_sent, 'LUZB')],	
					[3,$this->search_by_library($holdings_sent, 'BSUB')],	
					[4,$this->search_by_library($holdings_sent, 'ZHUB')],	
					[5,$this->search_by_library($holdings_sent, 'ZHZB')],	
				];

		$integrateds = [ 
					[1,$this->search_by_library($holdings_integrated, 'ABKB')],	
					[2,$this->search_by_library($holdings_integrated, 'LUZB')],	
					[3,$this->search_by_library($holdings_integrated, 'BSUB')],	
					[4,$this->search_by_library($holdings_integrated, 'ZHUB')],	
					[5,$this->search_by_library($holdings_integrated, 'ZHZB')],	
				];

		$reviseds = [ 
					[1,$this->search_by_library($holdings_revised, 'ABKB')],	
					[2,$this->search_by_library($holdings_revised, 'LUZB')],	
					[3,$this->search_by_library($holdings_revised, 'BSUB')],	
					[4,$this->search_by_library($holdings_revised, 'ZHUB')],	
					[5,$this->search_by_library($holdings_revised, 'ZHZB')],	
				];

		$trasheds = [ 
					[1,$this->search_by_library($holdings_trashed, 'ABKB')],	
					[2,$this->search_by_library($holdings_trashed, 'LUZB')],	
					[3,$this->search_by_library($holdings_trashed, 'BSUB')],	
					[4,$this->search_by_library($holdings_trashed, 'ZHUB')],	
					[5,$this->search_by_library($holdings_trashed, 'ZHZB')],	
				];

		$burned = [ 
					[1,$this->search_by_library($holdings_eliminated, 'ABKB')],	
					[2,$this->search_by_library($holdings_eliminated, 'LUZB')],	
					[3,$this->search_by_library($holdings_eliminated, 'BSUB')],	
					[4,$this->search_by_library($holdings_eliminated, 'ZHUB')],	
					[5,$this->search_by_library($holdings_eliminated, 'ZHZB')],	
				];


		$stats_size = [
		
		// confirmeds
			[ 
				[1,$this->size_by_library($holdings_confirmed, 'ABKB')],	
				[2,$this->size_by_library($holdings_confirmed, 'LUZB')],	
				[3,$this->size_by_library($holdings_confirmed, 'BSUB')],	
				[4,$this->size_by_library($holdings_confirmed, 'ZHUB')],	
				[5,$this->size_by_library($holdings_confirmed, 'ZHZB')],	
			],

		 //sents
			[ 
					[1,$this->size_by_library($holdings_sent, 'ABKB')],	
					[2,$this->size_by_library($holdings_sent, 'LUZB')],	
					[3,$this->size_by_library($holdings_sent, 'BSUB')],	
					[4,$this->size_by_library($holdings_sent, 'ZHUB')],	
					[5,$this->size_by_library($holdings_sent, 'ZHZB')],	
			],

			// integrateds
			[ 
					[1,$this->size_by_library($holdings_integrated, 'ABKB')],	
					[2,$this->size_by_library($holdings_integrated, 'LUZB')],	
					[3,$this->size_by_library($holdings_integrated, 'BSUB')],	
					[4,$this->size_by_library($holdings_integrated, 'ZHUB')],	
					[5,$this->size_by_library($holdings_integrated, 'ZHZB')],	
				],

		//$reviseds = 
			[ 
					[1,$this->size_by_library($holdings_revised, 'ABKB')],	
					[2,$this->size_by_library($holdings_revised, 'LUZB')],	
					[3,$this->size_by_library($holdings_revised, 'BSUB')],	
					[4,$this->size_by_library($holdings_revised, 'ZHUB')],	
					[5,$this->size_by_library($holdings_revised, 'ZHZB')],	
				],

		//$trasheds = 
			[ 
					[1,$this->size_by_library($holdings_trashed, 'ABKB')],	
					[2,$this->size_by_library($holdings_trashed, 'LUZB')],	
					[3,$this->size_by_library($holdings_trashed, 'BSUB')],	
					[4,$this->size_by_library($holdings_trashed, 'ZHUB')],	
					[5,$this->size_by_library($holdings_trashed, 'ZHZB')],	
				],
		//$burned = 
				[ 
					[1,$this->size_by_library($holdings_eliminated, 'ABKB')],	
					[2,$this->size_by_library($holdings_eliminated, 'LUZB')],	
					[3,$this->size_by_library($holdings_eliminated, 'BSUB')],	
					[4,$this->size_by_library($holdings_eliminated, 'ZHUB')],	
					[5,$this->size_by_library($holdings_eliminated, 'ZHZB')],	
				]
		];


		return Response::json([
				'counter' => [$confirmeds,$sents,$integrateds,$reviseds, $trasheds, $eliminateds],
				'large'		=> $stats_size
			]
		);

	}

	private function search_by_library($holdings, $library){
		$count = 0;
		foreach ($holdings as $holding){
			if ($holding['library']==$library) 
				$count = $holding['count'];
		}
		return (int) $count;
	}

	private function size_by_library($holdings, $library){
		$count = 0;
		foreach ($holdings as $holding){
			if ($holding['library']==$library) 
				$large = $holding['large'];
		}
		return (float) $large;
	}

	private function search_by_state($holdings, $state){
		$count = 0;
		foreach ($holdings as $holding){
			if ($holding['state']==$state) 
				$count = $holding['count'];
		}
		return $count;
	}	

}
