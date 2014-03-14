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
			$month = Input::get('month');
			$holdings_ok 			= $holdings_ok->where( DB::raw('extract(month from created_at)'),'=',$month );
			$holdings_annotated 	= $holdings_annotated->where( DB::raw('extract(month from created_at)'),'=',$month );
			$holdingsset_confirm	= $holdingsset_confirm->where( DB::raw('extract(month from created_at)'),'=',$month );
		}

		if (Input::has('year') && (Input::get('year')!='*')) {
			$year = Input::get('year');
			$holdings_ok 			= $holdings_ok->where( DB::raw('YEAR(created_at)'),'=',$year );
			$holdings_annotated 	= $holdings_annotated->where( DB::raw('YEAR(created_at)'),'=',$year );
			$holdingsset_confirm 	= $holdingsset_confirm->whereCreatedAt( DB::raw('YEAR(created_at)'),'=',$year );
		}

		$data['holdingsset_confirm'] 	= $holdingsset_confirm->get();
		$data['holdings_ok'] 			= $holdings_ok->take(10)->get();
		$data['holdings_annotated'] 	= $holdings_annotated->take(10)->get();

		$data['total'] 					= $holdings->count();
		$data['total_ok'] 				= $holdings_ok->count();
		$data['total_anottated'] 		= $holdings_annotated->count();


		return View::make('pages.index', $data);
	}

	public function getHelp(){
		return View::make('pages.help');
	}

	public function getStats(){

		$month = (Input::has('month')) ? Input::get('month') : false;
		$year = (Input::has('year')) ? Input::get('year') : false;

		$stats_size = [

			$this->find_stat( 'ok', 'size'),
			$this->find_stat( 'delivery', 'size'),
			$this->find_stat( 'integrate', 'size'),
			$this->find_stat( 'revised', 'size'),
			$this->find_stat( 'trash', 'size'),
			$this->find_stat( 'burn', 'size'),

		];
		$stats_count = [

			$this->find_stat( 'ok', 'count'),
			$this->find_stat( 'confirmed', 'count'),
			$this->find_stat( 'integrate', 'count'),
			$this->find_stat( 'revised', 'count'),
			$this->find_stat( 'trash', 'count'),
			$this->find_stat( 'burn', 'count'),

		];

		return Response::json([
				'titles'	=> [ trans('states.ok'),trans('states.delivery'), trans('states.integrated	'),trans('states.revised'),trans('states.trash'),trans('states.burn') ],
				'count' 	=> $stats_count,
				'size'		=> $stats_size
			]
		);

	}

	private function find_stat( $state, $value ){
		$stats 	=  Holding::stats($month, $year)->get()->toArray();
		$arr = [];

		foreach ( [ 1=>'AGKB', 2=>'BSUB', 3=>'LUZB', 4=>'ZHUB', 5=>'ZHZB' ] as $key => $library ) {
			$a = [$key,0];
			foreach ($stats as $stat){
				if (($stat['library']==$library) && ($stat['state']==$state) )
					$a = [ $key, (float) $stat[$value] ];
			}
			$arr[] = $a;
		}
		// 		echo json_encode($arr);
		// die();
		// echo json_encode($arr);
		return $arr;
	}





}
