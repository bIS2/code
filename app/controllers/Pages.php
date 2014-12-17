<?php
/*
*
*	Controls workflow with Pages.
*
*/
class Pages extends BaseController {

	public function getIndex(){
		$user = Auth::user();

		if ( ( $user->roles()->count()==1 ) ){

			if ( $user->hasRole('magvuser') || $user->hasRole('maguser') || $user->hasRole('magvuser') || $user->hasRole('speichuser') )
				return Redirect::to('holdings');

			if ( $user->hasRole('postuser') ) 	return Redirect::to('lists');
			if ( $user->hasRole('bibuser') ) 		return Redirect::to('sets');
			if ( $user->hasRole('superuser') ) 	return Redirect::to('admin/users');
			if ( $user->hasRole('sysadmin') ) 	return Redirect::to('admin/users');

		}
		return View::make('pages.index');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getStatistics(){

		if  (Input::has('lang')) return Redirect::to(Request::header('referer'));

		$data['libraries'] 				= Library::all();
		$library_id = ( Input::has('library_id') && (Input::get('library_id')!='*') ) ? Input::get('library_id') : null;

		$holdings 						= Holding::inLibrary($library_id);
		$holdings_ok 					= State::inLibrary($library_id)->whereState('ok');
		$holdings_annotated 	= State::inLibrary($library_id)->whereState('annotated');
		$holdingsset_confirm 	= Confirm::orderBy('created_at', 'DESC'); 

		if ( Input::has('month') && (Input::get('month')!='*') ) {
			$month = Input::get('month');
			$holdings_ok 					= $holdings_ok->where( DB::raw('extract(month from created_at)'),$month );
			$holdings_annotated 	= $holdings_annotated->where( DB::raw('extract(month from created_at)'),$month );
			$holdingsset_confirm	= $holdingsset_confirm->where( DB::raw('extract(month from created_at)'),$month );
		}

		if (Input::has('year') && (Input::get('year')!='*')) {
			$year = Input::get('year');
			$holdings_ok 					= $holdings_ok->where( DB::raw('extract(year from created_at)'),$year );
			$holdings_annotated 	= $holdings_annotated->where( DB::raw('extract(year from created_at)'),$year );
			$holdingsset_confirm 	= $holdingsset_confirm->where( DB::raw('extract(year from created_at)'),$year );
		}

		$data['holdingsset_confirm'] 	= $holdingsset_confirm->take(10)->get();
		$data['holdings_ok'] 					= $holdings_ok->take(10)->get();
		$data['holdings_annotated'] 	= $holdings_annotated->take(10)->get();

		$data['total'] 							= $holdings->count();
		$data['total_ok'] 					= $holdings_ok->count();
		$data['total_anottated'] 		= $holdings_annotated->count();

		// $data['total'] 					= 1;
		// $data['total_ok'] 				= 2;
		// $data['total_anottated'] 		= 3;


		return View::make('pages.stats', $data);
	}

	public function getHelp(){
		return View::make('pages.help');
	}

	public function getStats(){

		$month = (Input::has('month')) ? Input::get('month') : false;
		$year = (Input::has('year')) ? Input::get('year') : false;

		$stats_size = [

		$this->find_stat( 'ok', 'size'),
		$this->find_stat( 'revised', 'size'),
		$this->find_stat( 'delivery', 'size'),
		$this->find_stat( 'integrated', 'size'),
		$this->find_stat( 'trash', 'size'),
		$this->find_stat( 'burn', 'size'),

		];
		$stats_count = [

		$this->find_stat( 'confirmed', 'count'),
		$this->find_stat( 'ok', 'count'),
		$this->find_stat( 'revised', 'count'),
		$this->find_stat( 'integrated', 'count'),
		$this->find_stat( 'trash', 'count'),
		$this->find_stat( 'burn', 'count'),

		];

		return Response::json([
			'titles'	=> [ 
			'size' =>[
			trans('states.ok'),
			trans('states.revised'),
			trans('states.delivery'), 
			trans('states.integrated'),
			trans('states.trash'),
			trans('states.burn') 
			],
			'count' =>[
			trans('states.confirmed'), 
			trans('states.ok'),
			trans('states.revised'),
			trans('states.integrated'),
			trans('states.trash'),
			trans('states.burn') 
			]
			],
			'count' 	=> $stats_count,
			'size'		=> $stats_size	
				// 'query'		=> Holding::stats($month, $year)->get()->toArray()
			]
			);

	}

	// Build array by state in each library
	// $state = string state
	// $value = stat type: size or count
	// return = array [ [library number 1, value of stat],[library number 2, value of stat] ... ]
	
	private function find_stat( $state, $value ){

		$stats 	=  Holding::stats($month, $year)->get()->toArray();
		$libraries = [ 1=>'AGK', 2=>'HBZ', 3=>'UBB', 4=>'ZBZ', 5=>'ZHB' ];
		$arr = [];

		foreach ( $libraries as $key => $library ) {
			$a = [$key,0];
			foreach ($stats as $stat){
				if (($stat['library']==$library) && ($stat['state']==$state) )
					$a = [ $key, (float) $stat[$value] ];
			}
			$arr[] = $a;
		}

		return $arr;

	}



	/**
	 * Extract data to a csv file
	 *
	 * @return Response
	 */
	public function getExtractData()
	{
		$user = Auth::user();
		if (!$user->hasRole('superuser')) {return Redirect::to('/'); }
		else {
			$data = array();
			$data['allselectablefields'] = ['sys2','g','sys1','852b','866c','852h','245a','245b','245n','245p','260a','260b', '866a','866aupdated','x866a','state' , 'size', 'size_dispatchable'];

			$data['allsearchablefields'] = ['852b','866c','852h','holtype','state'];
			extract(Input::all());

			if ($filtered == 1) {
				
				$query = 'SELECT '.implode(',', $fieldstoshow).' FROM holdings';
				$i = -1;
				$where = ' WHERE ';
				foreach ($data['allsearchablefields'] as $field) {
					switch ($field) {
						case '852b':
						$i++;
							$part = '';
							$t = 0;

							foreach ($f852b as $lib) {
								if ($t == 0)
									$part .= '(';

								$part .= "f852b =  '".$lib."'";

								if ($t < count($f852b) - 1)
									$part .= ' OR ';

								if ($t == count($f852b) - 1)
									$part .= ')';

									$t++;
							}
							if ($part != '') {
								$query .= $where.$OrAndFilter[$i-1].' '.$part;
								$where = ' ';
							}

							break;

						case 'holtype':
							$i++;
							# code...
							break;
							
						case 'state':
							$i++;
							$part = '';
							$t = 0;
							foreach ($state as $st) {
								if ($t == 0)
									$part .= '(';

								$part .= "state = '".$st."'";

								if ($t < count($state) - 1)
									$part .= ' OR ';

								if ($t == count($state) - 1)
									$part .= ')';

									$t++;
							}
							if ($part != '') {
								$query .= $where.$OrAndFilter[$i-1].' '.$part;
								$where = ' ';
							}

							break;

						
						default:
							# code...
							break;
					}
				}

				if ($fromajax = 1) {
					echo $query;
					die();
				}
			}
			else {				
				return View::make('pages/extractdata', $data);
			}
		}
	}	

	/**
	 * Extract data to a csv file
	 *
	 * @return Response
	 */
	public function postExtractData()
	{
		var_dump(Input::all());
		die();
	}	



	/**
	 * Clear all cookies
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function getClearCookies($id)
	{
		// unset cookies
		if (isset($_SERVER['HTTP_COOKIE'])) {
			$cookies = explode(';', $_SERVER['HTTP_COOKIE']);
			foreach($cookies as $cookie) {
				$parts = explode('=', $cookie);
				$name = trim($parts[0]);
				setcookie($name, '', time()-1000);
				setcookie($name, '', time()-1000, '/');
			}
		}
		return Redirect::to('/');
	}


}
