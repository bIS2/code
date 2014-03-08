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
		$data['holdingsset_confirm'] 	= Confirm::take(10)->get();
		$ids = Holding::inLibrary()->lists('holdings.id');
		$ids[] = -1;
		$data['holdings_ok'] 			= State::orderby('id', 'desc')->whereState('ok')->whereIn('holding_id', $ids);
		$data['holdings_annotated'] 	= Note::orderby('id', 'desc')->take(10)->get();
		$data['holdings_revised'] 		= Holding::orderby('id', 'desc')->inLibrary()->reviseds()->get();

		$data['total'] 					= Holding::inLibrary()->count();
		$data['total_ok'] 				= Holding::inLibrary()->corrects()->count();
		$data['total_anottated'] 		= Holding::inLibrary()->annotated()->count();

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


		return View::make('pages.index', $data);
	}

	public function getHelp(){
		return View::make('pages.help');
	}

	private function search_by_library($holdings, $library){
		$count = 0;
		foreach ($holdings as $holding){
			if ($holding['library']==$library) 
				$count = $holding['count'];
		}
		return $count;
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
