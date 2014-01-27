<?php


class HoldingssetsController extends BaseController {
	protected $layout = 'layouts.default';


	public function __construct() {
		$this->beforeFilter( 'auth' );
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function Index()
	{
		

		if (Input::has('holcontent')) {

			$this->data['holdingssets'] = Holdingsset::whereId(Input::get('holdingsset_id'))->paginate(1);

			return View::make('holdingssets/hols', $this->data);
		}
		else { 
			/* SEARCH ADVANCED FIELDS OPTIONS
			----------------------------------------------------------------*/
			define('ALL_SEARCHEABLESFIELDS', 'sys1;sys2;022a;245a;245b;245c;246a;260a;260b;260c;300a;300b;300c;310a;362a;500a;505a;710a;710b;770t;772t;780t;785t;852b;852c;852h;852j;866a;866z;008x;008y;size;exists_online;is_current;has_incomplete_vols');

			// Is Filter
			$allsearchablefields = ALL_SEARCHEABLESFIELDS;
			$allsearchablefields = explode(';', $allsearchablefields);
			$is_filter = false;
			foreach ($allsearchablefields as $field) {
				$value = (($field != 'exists_online') && ($field != 'is_current') && ($field != 'has_incomplete_vols') && ($field != 'size') && ($field != 'sys1') && ($field != 'sys2')) ? Input::get('f'.$field) : Input::get($field);
				if ($value != '') {
					$is_filter = true;
					break;
				}
			}
			if ((Input::get('owner') == 1) || (Input::get('aux') == 1)) $is_filter = true;
			$this->data['is_filter'] = $is_filter;

			/* SHOW/HIDE FIELDS IN HOLDINGS TABLES DECLARATION
			-----------------------------------------------------------*/
			define('DEFAULTS_FIELDS', 'sys2;245a;245b;ocrr_ptrn;022a;260a;260b;260c;362a;710a;710b;310a;246a;505a;770t;772t;780t;785t;852c;852j;866a;866z;008x;008y;size;exists_online;is_current;has_incomplete_vols');
			define('ALL_FIELDS', 'sys2;245a;245b;ocrr_ptrn;022a;260a;260b;260c;362a;710a;710b;310a;246a;505a;770t;772t;780t;785t;852c;852j;866a;866z;008x;008y;size;exists_online;is_current;has_incomplete_vols');

			/* User vars */
			$uUserName = Auth::user()->username;
			$uUserLibrary = Auth::user()->library;
			$uUserLibraryId = Auth::user()->library->id;
			// $uGroupname
			if (!isset($_COOKIE[$uUserName.'_fields_to_show_ok'])) {
				if (Session::get($uUserName.'_fields_to_show_ok') == 'ocrr_ptrn') {
					setcookie($uUserName.'_fields_to_show_ok', DEFAULTS_FIELDS, time() + (86400 * 30));
					Session::put($uUserName.'_fields_to_show_ok', DEFAULTS_FIELDS);
				}
				else {
					setcookie($uUserName.'_fields_to_show_ok', Session::get($uUserName.'_fields_to_show_ok'), time() + (86400 * 30));
				}
			}

			if ((Session::get($uUserName.'_fields_to_show_ok') == 'ocrr_ptrn') || (Session::get($uUserName.'_fields_to_show_ok') == '')) {
				setcookie($uUserName.'_fields_to_show_ok', DEFAULTS_FIELDS, time() + (86400 * 30));
				Session::put($uUserName.'_fields_to_show_ok', DEFAULTS_FIELDS);
			}
			if (Input::get('clearorderfilter') == 1) {
				Session::put($uUserName.'_sortinghos_by', null);
				Session::put($uUserName.'_sortinghos', null);
			}

			$orderby = (Session::get($uUserName.'_sortinghos_by') != null) ? Session::get($uUserName.'_sortinghos_by') : 'f245a';
			$order 	= (Session::get($uUserName.'_sortinghos') != null) ? Session::get($uUserName.'_sortinghos') : 'ASC';

			// Groups
			$this->data['groups'] = Auth::user()->groups;

			$this->data['group_id'] = (in_array(Input::get('group_id'), $this->data['groups']->lists('id'))) ? Input::get('group_id') : '';
			$holdingssets = ($this->data['group_id'] != '') ? Group::find(Input::get('group_id'))->holdingssets() : Holdingsset::where('holdings_number','<',101);

			$state = Input::get('state');

			if (isset($state)) {
				if ($state == 'ok') 
					$holdingssets = $holdingssets->corrects();
				if ($state == 'pending')
					$holdingssets = $holdingssets->pendings();
				if ($state == 'annotated') 
					$holdingssets = $holdingssets->annotated();	
				if ($state == 'incorrects') 
					$holdingssets = $holdingssets->incorrects();					
				if ($state == 'receiveds') 
					$holdingssets = $holdingssets->receiveds();					
				if ($state == 'reserveds') 
					$holdingssets = $holdingssets->reserveds();
			}

			if ($this->data['is_filter']) {
				// Take all holdings
				$holdings = -1;
				// If filter by owner or aux
				if ((Input::get('owner') == 1) || (Input::get('aux') == 1)) {
					if ((Input::has('owner')) && (!(Input::has('aux')))) $holdings = $uUserLibrary-> holdings() -> whereLibraryId($uUserLibraryId) -> whereIsOwner('t');
					if (!(Input::has('owner')) && ((Input::has('aux')))) $holdings = $uUserLibrary-> holdings() -> whereLibraryId($uUserLibraryId) -> whereIsAux('t');
					if ((Input::has('owner')) && ((Input::has('aux'))))  {
						$holdings = $uUserLibrary->holdings()->where('library_id','=',$uUserLibraryId)->where(function($query) {
							$query->where('is_owner', '=', 't')
							->orWhere('is_aux', '=', 't');
						});
					}		
				}

				$openfilter = 0;
				$OrAndFilter = Input::get('OrAndFilter');
				// Verify if some value for advanced search exists.
				if ($holdings == -1) $holdings = DB::table('holdings')->orderBy('is_owner', 'DESC');

				foreach ($allsearchablefields as $field) {

					$value = (!(($field == 'exists_online') || ($field == 'is_current')  || ($field == 'has_incomplete_vols')  || ($field == 'size') || ($field == 'sys1')  || ($field == 'sys2'))) ? Input::get('f'.$field) : Input::get($field);
					
					if ($value != '') {
						$orand 		= $OrAndFilter[$openfilter-1];
						$compare 	= ($field == '008x') ? 'f'.$field : 'LOWER('.'f'.$field.')';
						$compare 	= (($field == 'sys1') || ($field == 'sys2')) ? 'LOWER('.$field.')' : $compare;
						$compare 	= (!(($field == 'exists_online') || ($field == 'is_current') || ($field == 'has_incomplete_vols') || ($field == 'size'))) ? $compare : $field;		
						$format 	= (!(($field == 'exists_online') || ($field == 'is_current') || ($field == 'has_incomplete_vols'))) ? Input::get('f'.$field.'format') : '%s = %';		
						$format 	= (!(($field == 'size') || ($field == 'sys1') || ($field == 'sys2'))) ? $format : Input::get($field.'format');
						$value 		= (!(($field == 'exists_online') || ($field == 'is_current') || ($field == 'has_incomplete_vols'))) ? $value : 't';
						$var 		= (!(($field == 'exists_online') || ($field == 'is_current') || ($field == 'has_incomplete_vols') || ($field == 'size') || ($field == 'sys1') || ($field == 'sys2'))) ? 'f'.$field : $field;
						if ($field == 'sys1') {

							$hos = Holdingsset::WhereRaw( sprintf( $format, $compare, pg_escape_string(addslashes(strtolower( Input::get($var) ) ) ) ) )->select('id')->lists('id');
							$hos[] = -1;
							$newholdings = Holding::whereIn('holdingsset_id', $hos)->select('id')->lists('id');
							$newholdings[] = -1;

							$holdings = ($orand == 'OR') ? $holdings->orWhereIn('id', $newholdings) : $holdings->whereIn('id', $newholdings);
							$openfilter++; 
						}
						else {
							if (!(($field == 'exists_online') || ($field == 'is_current') || ($field == 'has_incomplete_vols'))) { 
								$holdings = ($orand == 'OR') ? 	$holdings->OrWhereRaw( sprintf( $format, $compare, pg_escape_string(addslashes(strtolower( Input::get($var) ) ) )) ) :  
								$holdings->WhereRaw( sprintf( $format, $compare, pg_escape_string(addslashes(strtolower( Input::get($var) ) ) ) ) );  
								$openfilter++; 
							}
							else {
								$holdings = ($orand == 'OR') ? $holdings->orWhere($field, '=', 't') : $holdings->where($field, '=', 't');
								$openfilter++; 
							}

						}
					}
				}
				if ($openfilter == 0)  $this->data['is_filter'] = false;
				$holList = $holdings->select('holdings.holdingsset_id')->lists('holdings.holdingsset_id');
				$ids = (count($holList) > 0) ? $holList : [-1];
				$holdingssets = $holdingssets->whereIn('holdingssets.id', $ids);
				unset($holdings);
			}

			define(HOS_PAGINATE, 20);
			$this->data['holdingssets'] = $holdingssets->orderBy($orderby, $order)->orderBy('id', 'ASC')->with('holdings')->paginate(HOS_PAGINATE);
			unset($holdingssets);
			// die('before call the view');
			// $this->data['holdingssets'] = $holdingssets->paginate(20);
			if (isset($_GET['page']))  {
				$this->data['page'] = $_GET['page'];
				return View::make('holdingssets/hos', $this->data);
			}
			else  { 
				$this->data['page'] = 1;
				return View::make('holdingssets/index', $this->data);
			}
		}
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('holdingssets.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()	{

		if (Input::has('urltoredirect'))	{
			$newfields	= Input::get('fieldstoshow');
			$fieldlist 	= '';
			$i 					= 0;
			if ($newfields != '') {
				foreach ($newfields as $field) {
					$fieldlist .= $field;
					$i++;
					if (count($newfields) > $i) $fieldlist .= ';';
				}
			}
			// var_dump(Input::get('sortinghos_by'));
			// var_dump(Input::get('sortinghos'));die();
			$uUserName = Auth::user()->username;
			setcookie($uUserName.'_fields_to_show_ok', $fieldlist, time() + (86400 * 30));
			Session::put($uUserName.'_fields_to_show_ok', $fieldlist);
			Session::put($uUserName.'_sortinghos_by', Input::get('sortinghos_by'));
			Session::put($uUserName.'_sortinghos', Input::get('sortinghos'));
			return Redirect::to(Input::get('urltoredirect'));
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$this->data['holdingsset'] = Holdingsset::find($id);
		return View::make('holdingssets.show', $this->data);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		return View::make('holdingssets.edit');
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$inputs = Input::all();
		Holdingsset::find($id)->update($inputs);
		if (Input::has('ok') )
			return Response::json([ 'ok'=>$id ]);
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{

	}

/* ---------------------------------------------------------------------------------
	Del Group Tab from HOSG View
	--------------------------------------
	Params:
		$id: HOS Group id 
		-----------------------------------------------------------------------------------*/
		public function putDelTabgroup($id) {
			$uUserName = Auth::user()->username;
			$groupsids = Session::get($uUserName.'_groups_to_show');
			$newgroupsids = str_replace($id, '', $groupsids);
			$newgroupsids = str_replace(';;', ';', $newgroupsids);
			Session::put($uUserName.'_groups_to_show', $newgroupsids);
		// $group = Group::find($id)->delete();
			return Response::json( ['groupDelete' => [$id]] );
		}	

/* ---------------------------------------------------------------------------------
	Get Holding Data from Original System
	--------------------------------------
	Params:
		$id: Holding id
		-----------------------------------------------------------------------------------*/
		public function getFromLibrary($id) {
			$holding = Holding::find($id);
			$this->data['library'] = $holding->library->externalurl;
			$this->data['holding'] = substr($holding->sys2, 4, 9);
			return View::make('holdingssets.externalholding', $this -> data);
		}

/* ---------------------------------------------------------------------------------
	Recall Holdings from a specific holding
	--------------------------------------
	Params:
		$id: Holding id
		-----------------------------------------------------------------------------------*/
		public function getRecallHoldings($id) {
			$holding = Holding::find($id);
			$this -> data['holdings']  = recall_holdings($id);
			$this -> data['holdingsset_id']  = $holding->holdingsset_id;
			$this -> data['hosholsid']  = Holdingsset::find($this -> data['holdingsset_id'])->holdings()->select('id')->lists('id');
			$this -> data['hol']  = $holding;
			return View::make('holdingssets.recallingholdings', $this -> data);
		}

	/* ---------------------------------------------------------------------------------
	Recall Holdings from a specific holding
	--------------------------------------
	Params:
		$id: Holding id
		-----------------------------------------------------------------------------------*/
		public function getSimilaritySearch($id) {
			$holding = Holding::find($id);
			$res = similarity_search($holding->sys2);

			$ids  = Holdingsset::pendings()->select('id')->lists('id');
			$ids[] = -1;

			$this -> data['res']  = $res;
			$this -> data['hospendingsid']  = $ids;
			$this -> data['holdings']  = Holding::where('holdingsset_id','=',$holding->holdingsset_id)->select('id')->lists('id');
			$this -> data['holdingsset_id']  = $holding->holdingsset_id;
			$this -> data['hol']  = $holding;
			return View::make('holdingssets.similarityresults', $this -> data);
		}

/* ---------------------------------------------------------------------------------
	Create a new HOS from only from a Holding
	------------------------------------------
	Params:
		$id: Holding id
		-----------------------------------------------------------------------------------*/
		public function putNewHOS($id) {
			$holdingsset_id = Input::get('holdingsset_id');
			if (Input::has('holding_id')) {
				$ids = Input::get('holding_id');
				if (Input::has('update_hos') && (Input::get('update_hos') == 1)) {
					Holding::whereIn('id', $ids)->update(['holdingsset_id'=>$holdingsset_id]);
					Holdingsset::find($holdingsset_id)->increment('holdings_number', count($ids));
					$recalled = array();
					foreach ($ids as $hol_id) {
						$hos_ids = Holding::find($hol_id)->take(1)->lists('holdingsset_id');
						$hos_id = $hos_ids[0];
						Holdingsset::find($hos_id)->decrement('holdings_number');
						if (!(in_array($hos_id, $recalled))) { 
							holdingsset_recall($hos_id);
							$recalled[] = $hol_id;
						}
					}
					holdingsset_recall($holdingsset_id);
					$holdingssets[] = Holdingsset::find($holdingsset_id);
				}
				else {
					$newhos_id = createNewHos($ids[0]);
					Holding::whereIn('id', $ids)->update(['holdingsset_id'=>$newhos_id]);
					Holdingsset::find($holdingsset_id)->decrement('holdings_number', count($ids));
					Holdingsset::find($newhos_id)->update(['holdings_number' => count($ids), 'groups_number'=>0]);
					holdingsset_recall($holdingsset_id);
					if (Holdingsset::find($holdingsset_id)->holdings()->count() == 1) {

						Confirm::create([ 'holdingsset_id' => $holdingsset_id, 'user_id' => Auth::user()->id ]);
						// Holdingsset::find($holdingsset_id)->update(['state' => 'ok']);

					}
					holdingsset_recall($newhos_id);

					Confirm::create([ 'holdingsset_id' => $newhos_id, 'user_id' => Auth::user()->id ]);
					// Holdingsset::find($newhos_id)->update(['state' => 'ok']);
					
					$holdingssets[] = Holdingsset::find($holdingsset_id);
					$holdingssets[] = Holdingsset::find($newhos_id);
				}
			}
			else {
				Holdingsset::find($holdingsset_id)->decrement('holdings_number');
				$newhos_id = createNewHos($id);
				Holdingsset::find($newhos_id)->update(['holdings_number' => 1, 'groups_number'=>0]);
				holdingsset_recall($holdingsset_id);
				holdingsset_recall($newhos_id);
				$holdingssets[] = Holdingsset::find($holdingsset_id);
				$holdingssets[] = Holdingsset::find($newhos_id);
			}
			$newset = View::make('holdingssets/hos', ['holdingssets' => $holdingssets]);
			return $newset;
		// return Response::json( ['newhosok' => [$id]] );
		}	

/* ---------------------------------------------------------------------------------
	Force a Holdins to be HOs owner
	--------------------------------------
	Params:
		$id: Holdings id 
		$holdingsset_id: Holdingssset id 
		-----------------------------------------------------------------------------------*/
		public function putForceOwner($id) {
			$holdingsset_id = Input::get('holdingsset_id');
			$holdingsset = Holdingsset::find($holdingsset_id);
			$holdingsset -> holdings()->update(['is_owner' => 'f', 'force_owner' => 'f']);
			Holding::find($id)->update(['is_owner'=>'t', 'is_aux'=>'f', 'force_owner' => 't', 'force_aux' => 'f']);

			holdingsset_recall($holdingsset_id);
			$holdingssets[] = $holdingsset;
			$newset = View::make('holdingssets/hos', ['holdingssets' => $holdingssets]);
			return $newset;
		}		

/* ---------------------------------------------------------------------------------
	Force a Holdins to be HOs owner
	--------------------------------------
	Params:
		$id: Holdings id 
		$holdingsset_id: Holdingssset id 
		-----------------------------------------------------------------------------------*/
		public function putForceAux($id) {
		// die($id);
		// aux_ptrn-- poner en 1 los marcados
		// ocrr_ptrn-- poner en 1 los marcados

		// is_aux -> t
		// is_owner -> false
		// weight Cantidad de 1
		// ocrr_nr Cantidad de ocurrencias

			$holdingsset_id = Input::get('holdingsset_id');
			if (Input::get('unique_aux') == 1) {
				$holdingsset = Holdingsset::find($holdingsset_id);
				$ptrn = Input::get('ptrn');
				$empty_ptrn = str_replace('1', '0', $ptrn);
				$holdingsset->holdings()->where('id', '!=', $id)->update(['is_aux' => 'f', 'aux_ptrn' => $empty_ptrn ]);
				$holdingsset->holdings()->where('id', '=', $id)->update(['is_aux' => 't', 'aux_ptrn' => $ptrn]);
			}
			else {
				Holding::find($id)->update(['is_aux'=>'t', 'is_owner'=>'f', 'ocrr_ptrn'=> Input::get('newptrn'), 'aux_ptrn'=> Input::get('newauxptrn'), 'ocrr_nr' => Input::get('count'), 'force_aux' => 't', 'force_owner' => 'f']);
			}
			$holdingssets[] = Holdingsset::find($holdingsset_id);
			$newset = View::make('holdingssets/hos', ['holdingssets' => $holdingssets]);
			return $newset;
		}	

/* ---------------------------------------------------------------------------------
	Force a Holdins to be HOs owner
	--------------------------------------
	Params:
		$id: Holdings id 
		$holdingsset_id: Holdingssset id 
		-----------------------------------------------------------------------------------*/
		public function putForceBlue($id) {
			$holdingsset_id = Input::get('holdingsset_id');
			$auxptrnOriginal = Holding::find($id)->select('aux_ptrn')->get();
			foreach ($auxptrnOriginal as $aux1) {
				$aux = str_replace('1', '0', $aux1->aux_ptrn);
			}
			Holding::find($id)->update(['is_aux'=>'f', 'is_owner'=>'f', 'aux_ptrn' => $aux]);
		// holdingsset_recall($holdingsset_id);
			$holdingssets[] = Holdingsset::find($holdingsset_id);
			$newset = View::make('holdingssets/hos', ['holdingssets' => $holdingssets]);
			return $newset;
		}	

/* ---------------------------------------------------------------------------------
	Move a Hos to Other Hos Group 
	--------------------------------------
	Params:
		$id: HOS id 
		-----------------------------------------------------------------------------------*/
		public function putMoveHosToOthergroup($id) {
			$origingroup 	= str_replace('group', '', Input::get('origingroup'));
			$newgroup 		= str_replace('group', '', Input::get('newgroup'));
			$holdingsset 	= DB::select('select * from group_holdingsset where holdingsset_id = ? AND group_id = ?', array($id, $newgroup));
			if ($origingroup == '') {
			// Copying from all holdingssets to a determinate HOS Groups
				if (count($holdingsset) >= 1) {
				// The holdings is already on destiny group.				
					return Response::json( ['nothingtodo' => [1]] );
				}
				else {
					DB::insert('insert into group_holdingsset (group_id, holdingsset_id, created_at, updated_at) values (?, ?, NOW(), NOW())', array($newgroup, $id));
					$count = Holdingsset::find($id)->groups->count();
					Holdingsset::find($id)->update(['groups_number'=>$count]);
					return Response::json( ['ingroups' => $count] );
				}
			}
			else {
			// Moving from a HOS group to a other HOS Group
				if (count($holdingsset) >= 1) {
				// The holdings is already on destiny group.
					$holdingsset = DB::delete('delete from group_holdingsset where holdingsset_id = ? AND group_id = ?', array($id, $origingroup));
					$count = Holdingsset::find($id)->groups->count();
					Holdingsset::find($id)->update(['groups_number'=>$count]);
					return Response::json( ['ingroups' => $count] );		
				}
				else {
					DB::update('update group_holdingsset set group_id = '.$newgroup.' where holdingsset_id = ? AND group_id = ?', array($id, $origingroup));
				}
				return Response::json( ['removefromgroup' => [$id]] );
			}
		}	

/* ---------------------------------------------------------------------------------
	Get Holding Data from Original System
	--------------------------------------
	Params:
		$id: Holding id
		-----------------------------------------------------------------------------------*/
		public function putDeleteHosFromGroup($id) {
			Group::find(Input::get('group_id'))->holdingssets()->detach($id);
			Holdingsset::find($id)->decrement('groups_number');
			return Response::json( ['removefromgroup' => [$id]] );
		}

/* ---------------------------------------------------------------------------------
	Update the 866a from a holding
	--------------------------------------
	Params:
		$id: Holding id 
		-----------------------------------------------------------------------------------*/
		public function putUpdateField866aHolding($id) {


			$new866a = Input::get('new866a');

			$new866a = normalize866a($new866a);

			$holding = Holding::find($id)->update(['f866aupdated'=>$new866a, 'hol_nrm' => $new866a]);
			holdingsset_recall($holding->holdingsset_id);

			return Response::json( ['save866afield' => [$id]] );

		}	
	}

	function createNewHos($id) {
		$holding = Holding::find($id);
		$lastId = Holdingsset::orderBy('id', 'DESC')->take(1)->get();
		$key = '';
		foreach ($lastId as $key) {
		}
		$hol_ptrn = $holding -> hol_nrm;
		$newHos = new Holdingsset;
		$newHos ->	id 	= $key -> id + 1;
		$newHos ->	sys1 = $holding -> sys2;
		$newHos ->	f245a = $holding -> f245a;
		$newHos ->	holdings_number 	= 0; 
		$newHos ->	groups_number 		= 0; 
		$newHos ->	f008x 	= $holding -> f008x; 
		$newHos ->	save();
		$holding = Holding::find($id)->update(['holdingsset_id'=>$newHos -> id, 'is_owner' => 't', 'is_aux' => 'f']);
		return $newHos -> id;
	}

	function recall_holdings($id) {
		$holding  = Holding::find($id);
		$ids  = Holdingsset::pendings()->select('id')->lists('id');
		$ids[] = -1;	
		// echo count($ids);
	// die();
		return Holding::whereIn('holdingsset_id', $ids)->where(function($query) use ($holding) {	
			$query = ($holding->f245a != '') ? $query->where('f245a', 'like', '%'.$holding->f245a. '%') : $query;
			$query = ($holding->f245b != '') ? $query->orWhere('f245a', 'like', '%'.$holding->f245b. '%') : $query;
		})->take(100)->get();
	// $queries = DB::getQueryLog();
	// die(var_dump(end($queries)));
	}


/* ---------------------------------------------------------------------------------
	Search similaritys holdings from a hol
	--------------------------------------
	Params:
		$str: sys2 of the holdings
		-----------------------------------------------------------------------------------*/

	function similarity_search($sys2) {

		$conn_string = "host=localhost port=5433 dbname=bis user=postgres password=postgres+bis options='--client_encoding=UTF8'";
		$conn_string1 = "host=localhost port=5432 dbname=bis user=postgres password=postgres+bis options='--client_encoding=UTF8'";
		$con = pg_connect($conn_string) or ($con = pg_connect($conn_string1));

		date_default_timezone_set('America/New_York');
		define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
		$date_start = $date = new DateTime('now', new DateTimeZone('America/New_York'));

		$ta_sim_name      = 'ta_sim';    // result table
		$select_fld       = 'id,sys2,f022a,f245a,f245a_e,f245b_e,f245c_e,f_tit_e,f260a_e,f260b_e,f310a_e,f362a_e,f710a_e,f780t_e,f785t_e,f008x,f008y';  // fields 
		$fld_ta           = array();         // field list of table ta
		$ta_sim_fields    = '';              // fields for ta_sim_test
		$fld_sim          = array();         // field list of table_cmp


		$freq_tit  = array();  // fill $tit_freq with all frequent titles
		$query = "SELECT f245a FROM tit_freq";
		$result = pg_query($con, $query); if (!$result) { echo "Error executing".$query."\n".pg_last_error(); exit; }
		$tmp_arr = pg_fetch_all($result);
		foreach ($tmp_arr as $tmp) $freq_tit[] = $tmp['f245a'];

		$weight_model     = 0;               // general weight model
		$fld_weight_model = array();         // model list of weights for every field
		$fld_weight       = array();         // currently used list of weights for every field
		$max_score        = 0;               // remember top score
		$treshold_score   = 45;              // discriminating similar and different   !!! recheck this value 
		$is_freq_tit      = false;           // remember if a title is a frequent title defined in tit_freq
		//$mult_f022a     = ' ';             // mark ISSN if there are several
		$rno              = 0;               // records number
		$sys_reference    = '';              // 
		//$sys_compared     = array();         // collect all sys1 o sys2 that already have been put into sets

		// prepare list of fields to be used for comparison
		  // fields of a ta

		$fld_ta = array (
			'sys',      'f008x',    'f008y',    'f008l',    'f008s',
			'f022a',    'f245a',    'f245b',    'f245c',    'f245d',    'f246i',
			'f260a',    'f260b',    'f260c',    'f300c',
			'f310a',    'f362a',    'f500a',
			'f710a',    'f710b',    'f730a',    'f770t',    'f780t',    'f780w',
			'f852a',    'f852h',    'f856u',    'f866a',
			'f949j',    'f949z'
			);
		  // fields with the results of every field compared (values 0..1)
		$fld_sim = array (
			's_f008x',  's_f008y',
			's_f022a',  's_f245a',  's_f245b',  's_f245c',  's_f_tit',
			's_f260a',  's_f260b',
			's_f310a',  's_f362a',
			's_f710a',  's_f780a'
			);

		  // output fields
		$cmp_sim_fields  = "sys1,sys2, score, flag, f022a,s_f022a, f245a,s_f245a, f245b,s_f245b, f245c,s_f245c, f_tit, s_f_tit"; 
		$cmp_sim_fields .= ",f260a,s_f260a, f260b,s_f260b";
		$cmp_sim_fields .= ",f310a,s_f310a, f362a,s_f362a, f710a,s_f710a, f780t,s_f780t, f785t,s_f785t, f008x, s_f008x, f008y, s_f008y, proc_cmp, run_cmp";
		$cmp_sim_fields .= ", off, no";

		  // Weight model with values for match, similar, no-match. Values can be negative
			// balanced weighting
		$fld_weight_model[0] = array (
			'f008x' => array('equ' =>  3, 'sim' =>  0, 'dif' => -3),
			'f008y' => array('equ' =>  3, 'sim' =>  0, 'dif' => -3),
			'f022a' => array('equ' => 10, 'sim' =>  0, 'dif' => -6),
			'f245a' => array('equ' => 15, 'sim' =>  1, 'dif' => -3),
			'f245b' => array('equ' =>  1, 'sim' =>  1, 'dif' =>  0),
			'f245c' => array('equ' => 15, 'sim' =>  0, 'dif' =>  0),
			'f_tit' => array('equ' =>  3, 'sim' =>  1, 'dif' =>  0),
			'f260a' => array('equ' =>  5, 'sim' =>  3, 'dif' => -2),
			'f260b' => array('equ' =>  5, 'sim' =>  3, 'dif' => -2),
			'f260c' => array('equ' =>  1, 'sim' =>  1, 'dif' =>  0),
			'f300c' => array('equ' =>  1, 'sim' =>  1, 'dif' =>  0),
			'f310a' => array('equ' =>  5, 'sim' =>  1, 'dif' => -3),
			'f362a' => array('equ' =>  7, 'sim' =>  2, 'dif' => -7),
			'f710a' => array('equ' => 10, 'sim' =>  3, 'dif' => -5),
			'f780t' => array('equ' => 10, 'sim' =>  3, 'dif' =>  0),
			'f785t' => array('equ' => 10, 'sim' =>  3, 'dif' =>  0),
			'f852a' => array('equ' =>  1, 'sim' =>  1, 'dif' =>  0),
			);
			// weights institution (7xx) even more
		$fld_weight_model[1] = array (
			'f008x' => array('equ' =>  3, 'sim' =>  0, 'dif' => -3),
			'f008y' => array('equ' =>  3, 'sim' =>  0, 'dif' => -3),
			'f022a' => array('equ' => 10, 'sim' =>  0, 'dif' => -6),
			'f245a' => array('equ' => 10, 'sim' =>  1, 'dif' => -3),
			'f245b' => array('equ' =>  1, 'sim' =>  1, 'dif' =>  0),
			'f245c' => array('equ' => 10, 'sim' =>  0, 'dif' =>  0),
			'f_tit' => array('equ' =>  3, 'sim' =>  1, 'dif' =>  0),
			'f260a' => array('equ' =>  5, 'sim' =>  3, 'dif' => -2),
			'f260b' => array('equ' =>  5, 'sim' =>  3, 'dif' => -2),
			'f260c' => array('equ' =>  1, 'sim' =>  1, 'dif' =>  0),
			'f300c' => array('equ' =>  1, 'sim' =>  1, 'dif' =>  0),
			'f310a' => array('equ' =>  5, 'sim' =>  1, 'dif' => -3),
			'f362a' => array('equ' =>  7, 'sim' =>  2, 'dif' => -7),
			'f710a' => array('equ' => 20, 'sim' =>  3, 'dif' => -5),
			'f780t' => array('equ' => 20, 'sim' =>  3, 'dif' =>  0),
			'f785t' => array('equ' => 20, 'sim' =>  3, 'dif' =>  0),
			'f852a' => array('equ' =>  1, 'sim' =>  1, 'dif' =>  0),
		);  

					// weighting issn (022) more
		$fld_weight_model[2] = array (
			'f008x' => array('equ' =>  3, 'sim' =>  0, 'dif' => -3),
			'f008y' => array('equ' =>  3, 'sim' =>  0, 'dif' => -3),
			'f022a' => array('equ' => 20, 'sim' =>  0, 'dif' => -6),
			'f245a' => array('equ' => 10, 'sim' =>  1, 'dif' => -3),
			'f245b' => array('equ' =>  1, 'sim' =>  1, 'dif' =>  0),
			'f245c' => array('equ' => 10, 'sim' =>  0, 'dif' =>  0),
			'f_tit' => array('equ' =>  3, 'sim' =>  1, 'dif' =>  0),
			'f260a' => array('equ' =>  5, 'sim' =>  3, 'dif' => -2),
			'f260b' => array('equ' =>  5, 'sim' =>  3, 'dif' => -2),
			'f260c' => array('equ' =>  1, 'sim' =>  1, 'dif' =>  0),
			'f300c' => array('equ' =>  1, 'sim' =>  1, 'dif' =>  0),
			'f310a' => array('equ' =>  5, 'sim' =>  1, 'dif' => -3),
			'f362a' => array('equ' =>  7, 'sim' =>  2, 'dif' => -7),
			'f710a' => array('equ' => 10, 'sim' =>  3, 'dif' => -5),
			'f780t' => array('equ' => 10, 'sim' =>  3, 'dif' =>  0),
			'f785t' => array('equ' => 10, 'sim' =>  3, 'dif' =>  0),
			'f852a' => array('equ' =>  1, 'sim' =>  1, 'dif' =>  0),
		);                    // create $fld_ta

		create_table($ta_sim_name);
		$sys = $sys2;

		// get reference record
		$query = "SELECT ".$select_fld." FROM holdings WHERE sys2 = '$sys'";
		$result = pg_query($con, $query) or die(pg_last_error()); //; if (!$result) { echo "Error executing".$query."\n"; exit; }
		$tas = pg_fetch_all($result);
		// echo $query."<br>";
		$ta = $tas[0];
		// var_dump($ta);

		// ************************************************
		// COMPARE WITH OTHERS
		// ************************************************
		$is_freq_tit = false;  // later set to true of title occurs many times
		// initialize collect process information
		$proc_info = array('equ' => 0, 'sim' => 0, 'try' => 0, 'dif' => 0, 'AGKB' => 0, 'BSUB' => 0, 'LUZB' => 0, 'SGHG' => 0, 'ZHUZ' => 0, 'ZHZB' => 0);

		// get current time for process measurement
		if ($proc_flag['time']) $date_start_cycle = new DateTime('now', new DateTimeZone('America/New_York'));

		$sys_reference = $ta['sys2']; // ex. bib_sys

		// **** check if is_tit_freq  
		// break f245a the same way as the titles in tit_freq
		$query = "SELECT regexp_split_to_array(lower('".pg_escape_string($ta['f245a'])."'), E'[\- \.,:;\(\){}\"\']+') f245a_s";
		$result = pg_query($con, $query)  or die(pg_last_error()); // if (!$result) echo "Error executing".$query."\n";
		// echo $query."<br>";
		$tit = pg_fetch_all($result);
		$tit = substr($tit[0]['f245a_s'], 1, strlen($tit[0]['f245a_s'])-2);  // cut ()
		$tit = implode(' ', explode(',',$tit));
		$tit = str_replace(" \"\"", "", $tit);
		$tit = preg_replace("/[\[\]]/", "", $tit);
		if (in_array($tit, $freq_tit)) { // check if normalized title is a frequent title
			$is_freq_tit = true;
			if ($proc_flag['debug']) echo " !! FREQ(".$ta['f245a'].")\n";
		}

		// create comparison query. If value is '' the result will be 0, so we do not compare this field
		$query  = "SELECT id, sys2,";
		$query .= "\n f022a,         "; ($ta['f022a']   > '') ? $query .= " similarity(f022a,  '".pg_escape_string($ta['f022a'])."'  ) s_f022a," : $query .= " 0::integer s_f022a,";
		$query .= "\n f245a, f245a_e,"; ($ta['f245a_e'] > '') ? $query .= " similarity(f245a_e,'".pg_escape_string($ta['f245a_e'])."') s_f245a," : $query .= " 0::integer s_f245a,";
		$query .= "\n f245b, f245b_e,"; ($ta['f245b_e'] > '') ? $query .= " similarity(f245b_e,'".pg_escape_string($ta['f245b_e'])."') s_f245b," : $query .= " 0::integer s_f245b,";
		$query .= "\n f245c,         "; ($ta['f245c_e'] > '') ? $query .= " similarity(f245c_e,'".pg_escape_string($ta['f245c_e'])."') s_f245c," : $query .= " 0::integer s_f245c,";
		$query .= "\n f_tit,         "; ($ta['f_tit_e'] > '') ? $query .= " similarity(f_tit_e,'".pg_escape_string($ta['f_tit_e'])."') s_f_tit," : $query .= " 0::integer s_f_tit,";
		$query .= "\n f260a, f260a_e,"; ($ta['f260a_e'] > '') ? $query .= " similarity(f260a_e,'".pg_escape_string($ta['f260a_e'])."') s_f260a," : $query .= " 0::integer s_f260a,";
		$query .= "\n f260b,         "; ($ta['f260b_e'] > '') ? $query .= " similarity(f260b_e,'".pg_escape_string($ta['f260b_e'])."') s_f260b," : $query .= " 0::integer s_f260b,";
		$query .= "\n f310a,         "; ($ta['f310a_e'] > '') ? $query .= " similarity(f310a_e,'".pg_escape_string($ta['f310a_e'])."') s_f310a," : $query .= " 0::integer s_f310a,";
		$query .= "\n f362a, f362a_e, similarity(
			array_to_string(regexp_split_to_array(f362a_e, E'[^0-9]+'),';','*'),
			array_to_string(regexp_split_to_array('".pg_escape_string($ta['f362a_e'])."', E'[^0-9]+'),';','*')) s_f362a,";
		$query .= "\n f710a, f710a_e,"; ($ta['f710a_e'] > '') ? $query .= " similarity(f710a_e,'".pg_escape_string($ta['f710a_e'])."') s_f710a," : $query .= " 0::integer s_f710a,";
		$query .= "\n f780t, f780t_e,"; ($ta['f780t_e'] > '') ? $query .= " similarity(f780t_e,'".pg_escape_string($ta['f780t_e'])."') s_f780t," : $query .= " 0::integer s_f780t,";
		$query .= "\n f785t, f785t_e,"; ($ta['f785t_e'] > '') ? $query .= " similarity(f785t_e,'".pg_escape_string($ta['f785t_e'])."') s_f785t," : $query .= " 0::integer s_f785t,";
		$query .= "\n f008x,         "; ($ta['f008x']   > '') ? $query .= " similarity(f008x  ,'".pg_escape_string($ta['f008x'])  ."') s_f008x," : $query .= " 0::integer s_f008x,";
		$query .= "\n f008y,         "; ($ta['f008y']   > '') ? $query .= " similarity(f008y  ,'".pg_escape_string($ta['f008y'])  ."') s_f008y"  : $query .= " 0::integer s_f008y";
		$query .= "\n FROM holdings";
		if ($is_freq_tit) { // for frequent titles include filters
			$query .= "\n  WHERE similarity(f245a_e,'".pg_escape_string($ta['f245a_e'])."') = 1";  // same title
			if (($ta['f710a_e'] > '') and ($ta['f245c_e'] > '')) {
		 			$query .= " AND (similarity(f710a_e,'".pg_escape_string($ta['f710a_e'])."') > 0.9";  // similiar organisation
					$query .= "\n OR similarity(f245c_e,'".pg_escape_string($ta['f245c_e'])."') > 0.8)";
			} else {
				if (($ta['f710a_e'] >  '') AND ($ta['f245c_e'] == ''))
					$query .= " AND similarity(f710a_e,'".pg_escape_string($ta['f710a_e'])."') > 0.9";  // similiar organisation (710a)
				if (($ta['f710a_e'] == '') AND ($ta['f245c_e'] >  ''))
						$query .= " AND similarity(f245a_e,'".pg_escape_string($ta['f245a_e'])."') > 0.8";  // similar organisation (245c)
				}
		} else {
				$query .= "\n  WHERE similarity(f245a_e,'".pg_escape_string($ta['f245a_e'])."') > 0.6";
				$query .= "\n     OR similarity(f710a_e,'".pg_escape_string($ta['f710a_e'])."') > 0.8";
		}
			$query .= "\n  ORDER BY s_f245a DESC, f245a_e";
		//printf("%s\n", $query);
			$result = pg_query($con, $query) or die(pg_last_error());// if (!$result) { echo "Error executing".$query."\n"; exit; }
			$ta_res_sim = pg_fetch_all($result);
			$size_r = sizeof($ta_res_sim);

			// echo $query."<br>";
			// die();
			if (!$ta_res_sim) $size_r = 0;

			$proc_info['found'] = $size_r;
			if ($proc_flag['debug']) printf(" FOUND: %s\n", $proc_info['found']);


			// -------------------- analyse and optimize result
			for ($rno = 0; $rno < $size_r; $rno++) {
			  // prepare score evaluation
				$ta_res_sim[$rno]['score']      = 0;
			  $ta_res_sim[$rno]['flag']       = '_'; // initialize with '_'
			  if (!isset($ta_res_sim[$rno]['s_f022a']) or $ta_res_sim[$rno]['s_f022a'] == 'NaN') $ta_res_sim[$rno]['s_f022a']  = 0;
			  if (!isset($ta_res_sim[$rno]['s_f245a']) or $ta_res_sim[$rno]['s_f245a'] == 'NaN') $ta_res_sim[$rno]['s_f245a']  = 0;
			  if (!isset($ta_res_sim[$rno]['s_f245b']) or $ta_res_sim[$rno]['s_f245b'] == 'NaN') $ta_res_sim[$rno]['s_f245b']  = 0;
			  if (!isset($ta_res_sim[$rno]['s_f245c']) or $ta_res_sim[$rno]['s_f245c'] == 'NaN') $ta_res_sim[$rno]['s_f245c']  = 0;
			  if (!isset($ta_res_sim[$rno]['s_f_tit']) or $ta_res_sim[$rno]['s_f_tit'] == 'NaN') $ta_res_sim[$rno]['s_f_tit']  = 0;
			  if (!isset($ta_res_sim[$rno]['s_f260a']) or $ta_res_sim[$rno]['s_f260a'] == 'NaN') $ta_res_sim[$rno]['s_f260a']  = 0;
			  if (!isset($ta_res_sim[$rno]['s_f260b']) or $ta_res_sim[$rno]['s_f260b'] == 'NaN') $ta_res_sim[$rno]['s_f260b']  = 0;
			  if (!isset($ta_res_sim[$rno]['s_f310a']) or $ta_res_sim[$rno]['s_f310a'] == 'NaN') $ta_res_sim[$rno]['s_f310a']  = 0;
			  if (!isset($ta_res_sim[$rno]['s_f362a']) or $ta_res_sim[$rno]['s_f362a'] == 'NaN') $ta_res_sim[$rno]['s_f362a']  = 0;
			  if (!isset($ta_res_sim[$rno]['s_f710a']) or $ta_res_sim[$rno]['s_f710a'] == 'NaN') $ta_res_sim[$rno]['s_f710a']  = 0;
			  if (!isset($ta_res_sim[$rno]['s_f780t']) or $ta_res_sim[$rno]['s_f780t'] == 'NaN') $ta_res_sim[$rno]['s_f780t']  = 0;
			  if (!isset($ta_res_sim[$rno]['s_f785t']) or $ta_res_sim[$rno]['s_f785t'] == 'NaN') $ta_res_sim[$rno]['s_f785t']  = 0;
			  if (!isset($ta_res_sim[$rno]['s_f008x']) or $ta_res_sim[$rno]['s_f008x'] == 'NaN') $ta_res_sim[$rno]['s_f008x']  = 0;
			  if (!isset($ta_res_sim[$rno]['s_f008y']) or $ta_res_sim[$rno]['s_f008y'] == 'NaN') $ta_res_sim[$rno]['s_f008y']  = 0;

			  $ta_res_sim[$rno]['s_f008x'] = compare_field('f008x', $ta['f008x']  , $ta_res_sim[$rno]['f008x'], $ta_res_sim, $rno);
			  $ta_res_sim[$rno]['s_f008y'] = compare_field('f008y', $ta['f008y']  , $ta_res_sim[$rno]['f008y'], $ta_res_sim, $rno);
			  $ta_res_sim[$rno]['s_f022a'] = compare_field('f022a', $ta['f022a']  , $ta_res_sim[$rno]['f022a'], $ta_res_sim, $rno); // check if ISSN contained
			  $ta_res_sim[$rno]['s_f260a'] = compare_field('f260a', $ta['f260a_e'], $ta_res_sim[$rno]['f260a_e'], $ta_res_sim, $rno);
			  $ta_res_sim[$rno]['s_f780t'] = compare_field('f780t', $ta['f780t_e'], $ta_res_sim[$rno]['f780t_e'], $ta_res_sim, $rno);
			  $ta_res_sim[$rno]['s_f785t'] = compare_field('f785t', $ta['f785t_e'], $ta_res_sim[$rno]['f785t_e'], $ta_res_sim, $rno);

			  $ta_res_sim[$rno]["score"] += weight_every_fld("f022a", $weight_model,$ta_res_sim, $rno, $fld_weight_model);
			  $ta_res_sim[$rno]["score"] += weight_every_fld("f245a", $weight_model,$ta_res_sim, $rno, $fld_weight_model);
			  $ta_res_sim[$rno]["score"] += weight_every_fld("f245c", $weight_model,$ta_res_sim, $rno, $fld_weight_model);
			  $ta_res_sim[$rno]["score"] += weight_every_fld("f_tit", $weight_model,$ta_res_sim, $rno, $fld_weight_model);
			  $ta_res_sim[$rno]["score"] += weight_every_fld("f260a", $weight_model,$ta_res_sim, $rno, $fld_weight_model);
			  $ta_res_sim[$rno]["score"] += weight_every_fld("f260b", $weight_model,$ta_res_sim, $rno, $fld_weight_model);
			  $ta_res_sim[$rno]["score"] += weight_every_fld("f310a", $weight_model,$ta_res_sim, $rno, $fld_weight_model);
			  $ta_res_sim[$rno]["score"] += weight_every_fld("f362a", $weight_model,$ta_res_sim, $rno, $fld_weight_model);
			  $ta_res_sim[$rno]["score"] += weight_every_fld("f710a", $weight_model,$ta_res_sim, $rno, $fld_weight_model);
			  $ta_res_sim[$rno]["score"] += weight_every_fld("f780t", $weight_model,$ta_res_sim, $rno, $fld_weight_model);
			  $ta_res_sim[$rno]["score"] += weight_every_fld("f785t", $weight_model,$ta_res_sim, $rno, $fld_weight_model);
			  $ta_res_sim[$rno]["score"] += weight_every_fld("f008x", $weight_model,$ta_res_sim, $rno, $fld_weight_model);
			  $ta_res_sim[$rno]["score"] += weight_every_fld("f008y", $weight_model,$ta_res_sim, $rno, $fld_weight_model);


			  if ($ta_res_sim[$rno]['score'] >= $max_score) $max_score = $ta_res_sim[$rno]['score'];  // remember highest score
			}

			if ($proc_flag['debug']) printf("SCORE: %s %s\n", $treshold_score, $max_score); // show SCORE


			// now assign a similarity category for each TA
			for ($rno = 0; $rno < $size_r; $rno++) {				
				if ($ta_res_sim[$rno]['score'] >= ($max_score - $treshold_score)) $ta_res_sim[$rno]['flag'] = '*'; else $ta_res_sim[$rno]['flag'] = '-'; // mark treshold
				// adjust categorization of TA if f245a or f710a are nearly equal

				if ($is_freq_tit) { // for frequent title use a stricter comparison
					if (($ta_res_sim[$rno]['s_f245a'] == 1) && ($ta_res_sim[$rno]['s_f710a'] == 1)) $ta_res_sim[$rno]['flag'] = '*'; // must correspond
				} else { // normal case
					if ($ta_res_sim[$rno]['s_f245a'] > 0.9) $ta_res_sim[$rno]['flag'] = '*'; // title must correspond a bit less
				}	
				if ($sys_reference == $ta_res_sim[$rno]['sys2']) $ta_res_sim[$rno]['flag'] = '='; // mark original record with '='
			}

		// -------------------- sort results in similarity order
		usort($ta_res_sim, 'cmp_score'); // *** sort result by score type for output

		// get number of last good ta candidate		
		$ta_sim_last_good = 0;
		for ($rno = 0; $rno < sizeof($ta_res_sim); $rno++) {
			if ($ta_res_sim[$rno]['flag'] == '*' || $ta_res_sim[$rno]['flag'] == '=') { // included in the result set
				$ta_sim_last_good = $rno;  // remember last selected record
			} else { break; }
		}
		$last_ta_in_set =  $ta_sim_last_good;
		var_dump($last_ta_in_set);
		usort($ta_res_sim, 'cmp_flag_score'); // *** sort result by flag, score for output
		 
		return $ta_res_sim;
	}

/* ---------------------------------------------------------------------------------
	Truncate a string
	--------------------------------------
	Params:
		$str: String to truncate
		$length: Lenght of new string
		$trailing:  Final Trailing
		-----------------------------------------------------------------------------------*/
		function truncate($str, $length, $trailing = '...') {
			$length-=strlen($trailing);
			if (strlen($str) > $length) {
				$res = substr($str, 0, $length);
				$res .= $trailing;
			}
			else {
				$res = $str;
			}
			return $res;
		}


/* ---------------------------------------------------------------------------------
	Recall a holdingsset.
	--------------------------------------
	Params:
		$id: HOS id
		$Notice: Parameters to used in recall
						- force_owner(int: Holding id): Fix a Holdings that has to be owner of the HOS
						- 866aupdated if 866aupdated != '';
						- lockeds holdings can't be used to the algoritm

						-----------------------------------------------------------------------------------*/
function holdingsset_recall($id) {

	$conn_string = "host=localhost port=5433 dbname=bis user=postgres password=postgres+bis options='--client_encoding=UTF8'";
	$conn_string1 = "host=localhost port=5432 dbname=bis user=postgres password=postgres+bis options='--client_encoding=UTF8'";
	$con = pg_connect($conn_string) or ($con = pg_connect($conn_string1));

	$query = "SELECT * FROM holdings WHERE holdingsset_id = ".$id." ORDER BY sys2, score DESC LIMIT 500";
	$result = pg_query($con, $query) or die("Cannot execute \"$query\"\n".pg_last_error());

	$ta_arr = pg_fetch_all($result);

	$ta_amnt = sizeOf($ta_arr);

	/***********************************************************************
	 * Se forman los grupos y se calculan los valores
	 ***********************************************************************/

	$index				= -1;
	$forceowner_index	= -1;
	$blockeds_hols		= array();
	$curr_ta			= '';
	$ta_hol_arr		= array();

	for ($i=0; $i<$ta_amnt; $i++) {
		$ta_res_arr   = array(); //<------------------------------------------ Collects res
		
		$ta = $ta_arr[$i]['sys1'];
		$hol = $ta_arr[$i]['sys2'];
		$g = $ta_arr[$i]['g'];

		if ($ta !== $curr_ta) {
			$index++;
			$curr_ta = $ta;
			$ta_hol_arr[$index]['hol']= array();
			$ta_hol_arr[$index]['ptrn']= array();
		}
		
		/******************************************************************
		 * Aqui se genera el patron y se le pega a cada < ta >  OK
		 * hay que generar un patron de incompletos (pa pintar después)
		 ******************************************************************/
		
		$hol_ptrn = $ta_arr[$i]['hol_nrm'];
		//si tiene algo se parte por el ;
		$ta_arr[$i]['ptrn_arr'] = (preg_match('/\w/',$hol_ptrn))?explode(';',$hol_ptrn):array();

		if ($ta_arr[$i]['ptrn_arr']){
			$ptrn_amnt	= sizeOf($ta_arr[$i]['ptrn_arr']);
			for ($l=0; $l<$ptrn_amnt; $l++){
				$ptrn_piece = $ta_arr[$i]['ptrn_arr'][$l]; //preservar el valor original
				//aqui se quita la j que no sirve pa comparar
				$ptrn_piece = preg_replace('/[j]/', ' ', $ptrn_piece);
				$ptrn_piece = preg_replace('/\s$/', '',$ptrn_piece);
				
				$ptrn_piece = preg_replace('/[n]/', '',$ptrn_piece); //<---------- parche!!!!!!!!!!!!!!!!!!!!!!!!!!!
				
				$ptrn_piece[16] = '-'; //esto es un parche pa poner el - que faltaba en el hol_nrm
				
				if (!preg_match('/\w/',$ptrn_piece)){
					//si el pedacito viene en blanco se borra
					unset($ta_arr[$i]['ptrn_arr'][$l]);
				}
				//si tiene sustancia...
				else {
					//se parte en pedacitos
					$ptrn_chunks = explode ('-',$ptrn_piece);
					$chunks_amnt	= sizeOf($ptrn_chunks);
					for ($p=0; $p<$chunks_amnt; $p++){
						if (!preg_match('/\w/',$ptrn_chunks[$p])){
							//se quitan los que quedan en blanco
							unset($ptrn_chunks[$p]);
						}
						//y se echan pal ptrn
						else array_push($ta_hol_arr[$index]['ptrn'],$ptrn_chunks[$p]);
					}				
				}
			}
		}
		
		//aqui se escribe el ptrn	
		$ta_hol_arr[$index]['ptrn']=array_unique($ta_hol_arr[$index]['ptrn']);
		//aqui se ordenan los pedacitos del patron----------------------------
		$tmparr = $ta_hol_arr[$index]['ptrn'];
		
		$tmparr = array_map(
			function($n){
				return explode('|',substr(chunk_split($n,4,'|'),0,-1));
			}, 
			$tmparr); 
		
		$volume = array();
		$year = array();
		foreach($tmparr as $key => $row){
			$volume[$key] = $row[0];
			$year[$key] = $row[2];
		}
		array_multisort($year,SORT_ASC, $volume,SORT_ASC, $tmparr);
		//$tmparr = array_map('make_onepiece',$tmparr);
		$tmparr = array_map(
			function($n){
				return implode('',$n); 
			}, 
			$tmparr); 
		
		$tmparr = array_values($tmparr);
		
		$ta_hol_arr[$index]['ptrn']  = $tmparr;
		//aqui se van juntando los hol del TA
		array_push($ta_hol_arr[$index]['hol'],$ta_arr[$i]);

		if (Holding::find($ta_arr[$i]['id'])->locked) $blockeds_hols[]['index'] = $i;
		if (Holding::find($ta_arr[$i]['id'])->locked) $blockeds_hols[]['id'] = $ta_arr[$i]['id'];

		unset($ta_arr[$i]);
		unset($tmparr);
		// echo '.';
	}

	foreach ($blockeds_hols as $hol) {
		unset($ta_hol_arr[0]['hol'][$hol['index']]);
	}

	$ta_hol_arr[0]['hol'] = array_values($ta_hol_arr[0]['hol']);

	$hol_amnt = sizeOf($ta_hol_arr[0]['hol']);
	$mishols = $ta_hol_arr[0]['hol'];

	for ($k=0; $k<$hol_amnt; $k++){ //por cada hol
		if ($mishols[$k]['force_owner'] == 't') $forceowner_index = $k;
	}


	//echo EOL.EOL;
	$ta_hol_amnt = sizeOf($ta_hol_arr); //la cantidad de grupos TA

	/***********************************************************************
	 * Function/s :)
	 ***********************************************************************/

	/***********************************************************************
	 * For each group of holdings (TA)...
	 * 	weight pattern
	 * For each holding (hol)...
	 * 	fixes the 16th char (patch)...
	 * 	occurrences pattern
	 * 	completeness pattern
	 * 	weight
	 * 	number of occurrences
	 * 	potential owners by weight
	 * 	potential owners by occurrences
	 ***********************************************************************/
	// var_dump($blockeds_hols);
	for ($i=0; $i<$ta_hol_amnt; $i++){ //<---------------------------------- for each group of holdings (TA)...
		
		//Patron del HOS - como arreglo
		$ptrn = $ta_hol_arr[$i]['ptrn'];

		// Tamaño del arreglo del patrón
		$ptrn_amnt = sizeOf($ptrn);
		
		// Hols del HOS
		$hol_arr = $ta_hol_arr[$i]['hol'];

		// Cantidad de hols
		$hol_amnt = sizeOf($hol_arr);

		$weight_ptrn = array_map(
			function ($n){
				$chunks = explode('|',substr(chunk_split($n,4,'|'),0,-1));
				$d_vol = intval($chunks[1])-intval($chunks[0]);
				$d_year = intval($chunks[3])-intval($chunks[2]);
				return (($d_vol>0)?$d_vol:(($d_year>0)?$d_year:0))+1;
			},
			$ptrn);
		
		$ta_hol_arr[$i]['weight_ptrn'] = $weight_ptrn; //<-------------------- weight pattern

		$mx_ocrr_nr = 0;
		$mx_weight = 0;
		$posowners = array();	
		$posowners_oc = array();
		$owner_index = ''; 
		$ta_hol_arr[$i]['owner'] = '';
		
		for ($k=0; $k<$hol_amnt; $k++){ //<----------------------------------- for each holding (hol)...
			
			$ta = $hol_arr[$k]['sys1'];
			$hol = $hol_arr[$k]['sys2'];
			$g = $hol_arr[$k]['g'];
			
			$weight = 0;
			$ocrr_nr = 0;
			
			$j_factor = .5;

			$ta_hol_arr[$i]['hol'][$k]['ocrr_arr'] = ($ptrn_amnt>0)?array_fill(0,$ptrn_amnt,0):array();
			$ta_hol_arr[$i]['hol'][$k]['j_arr'] = ($ptrn_amnt>0)?array_fill(0,$ptrn_amnt,0):array();
			
			$ocrr = $ta_hol_arr[$i]['hol'][$k]['ptrn_arr'];
			
			if ($ocrr) {
				$ocrr_amnt = sizeOf($ocrr);
				
				for ($l=0; $l<$ocrr_amnt; $l++){ //por cada pedacito
					if (isset($ocrr[$l])){
						//hay pedacito y se puede partir
						$ocrr_piece = $ocrr[$l];
						
						$is_j = preg_match('/[j]/',$ocrr_piece);
						$ocrr_piece[16] = '-'; //<------------------------------------ fixes the 16th char (patch)...			
						$ocrr_piece = preg_replace('/[j]/', ' ', $ocrr_piece);
						$ocrr_piece = preg_replace('/\s$/', '',$ocrr_piece);
						
						$ocrr_piece = preg_replace('/[n]/', '',$ocrr_piece); //<------ parche
						
						$ocrr_xtr = explode('-',$ocrr_piece);
						
						$ocrr_bgn = get_ptrn_position($ocrr_xtr[0],$ptrn);
						$val_bgn = $ocrr_xtr[0];

						if (array_key_exists(1,$ocrr_xtr)){ //<----------------------- vvvvVVVVyyyyYYYY-vvvvVVVVyyyyYYYY
							if (preg_match('/\w/',$ocrr_xtr[1])){
								$ocrr_end = get_ptrn_position($ocrr_xtr[1],$ptrn);
								$val_end = $ocrr_xtr[1];
							}
								else { //<------------------------------------------------ vvvvVVVVyyyyYYYY-
									$ocrr_end = $ptrn_amnt-1;
									$val_end = (isset($ptrn[$ptrn_amnt-1]))?$ptrn[$ptrn_amnt-1]:'';
								}
							}
						else { //<---------------------------------------------------- vvvvVVVVyyyyYYYY
							
							//si el valor solo es un agno buscar hasta donde llega ????
							$tiny_chunks = explode('|',substr(chunk_split($ocrr_bgn,4,'|'),0,-1));
							if (preg_match('/\w/',$tiny_chunks[2])) echo $tiny_chunks[2].EOL;
							$ocrr_end = $ocrr_bgn;
							$val_end = $val_bgn;
						}
						$ta_hol_arr[$i]['hol'][$k]['ocrr_arr'][$ocrr_end] = 1;
						if ($is_j) $ta_hol_arr[$i]['hol'][$ocrr_end]['j_arr'][$h] = 1;
						for ($h=$ocrr_bgn; $h<$ocrr_end; $h++){
							$ta_hol_arr[$i]['hol'][$k]['ocrr_arr'][$h] = 1;
							if ($is_j) $ta_hol_arr[$i]['hol'][$k]['j_arr'][$h] = 1;
						}
					}
					else {
						//no se pudo determinar
					}
				}
			}
			
			$ocrr_ptrn = $ta_hol_arr[$i]['hol'][$k]['ocrr_arr']; //<------------ occurrences pattern
			$j_ptrn = $ta_hol_arr[$i]['hol'][$k]['j_arr']; //<------------------ completeness pattern

			$hol_weight_ptrn = array_map( 
				function($w, $o, $j){
					$j_factor = .5;
					return $w*$o*(($j>0)?$j_factor:1); 
				}, 
				$weight_ptrn, $ocrr_ptrn, $j_ptrn); 
			
			$weight = array_sum($hol_weight_ptrn);  //<------------------------- weight
			$ocrr_nr  = array_sum($ocrr_ptrn);  //<----------------------------- number of occurrences
			
		/******************************************************************
		 * Finding potential owners
		 ******************************************************************/

		if ($weight !== 0 ) {
			if ($weight > $mx_weight ) {
				$mx_weight = $weight;
				$posowners = array();	
				$posowners[0] = $k;
			}
			else if ($weight === $mx_weight ) {
					array_push($posowners,$k); //<---------------------------------- potential owners by weight
				}
			}
			
			if ($ocrr_nr !== 0 ) {
				if ($ocrr_nr > $mx_ocrr_nr ) {
					$mx_ocrr_nr = $ocrr_nr;
					$posowners_oc = array();	
					$posowners_oc[0] = $k;
				}
				else if ($ocrr_nr === $mx_ocrr_nr ) {
					array_push($posowners_oc,$k); //<------------------------------- potential owners by occurrences
				}
			}
			
			$ta_hol_arr[$i]['hol'][$k]['ocrr_nr'] = $ocrr_nr;
			$ta_hol_arr[$i]['hol'][$k]['weight'] = $weight;

		/******************************************************************
		 * UPDATE hol_out
		 * 	ptrn
		 * 	ocrr_nr
		 * 	weight
		 * 	ocrr_ptrn
		 * 	j_ptrn
		 ******************************************************************/

		$ta_res_arr[$ta.$hol.$g]['sys1'] 	  = $ta;
		$ta_res_arr[$ta.$hol.$g]['sys2']      = $hol;
		$ta_res_arr[$ta.$hol.$g]['g']         = $g;
		$ta_res_arr[$ta.$hol.$g]['ptrn']      = implode('|',$ptrn);
		$ta_res_arr[$ta.$hol.$g]['ocrr_nr']   = $ocrr_nr;
		$ta_res_arr[$ta.$hol.$g]['ocrr_ptrn'] = implode('',$ocrr_ptrn);
		$ta_res_arr[$ta.$hol.$g]['weight']    = $weight;
		$ta_res_arr[$ta.$hol.$g]['j_ptrn']    = implode('',$j_ptrn);

		$ta_res_arr[$ta.$hol.$g]['is_owner']  = 'f';
		$ta_res_arr[$ta.$hol.$g]['aux_ptrn']  = '';
		$ta_res_arr[$ta.$hol.$g]['is_aux']    = 'f';

			/*
			$query = "UPDATE hol_out 
								SET ptrn='".implode('|',$ptrn) ."' , ocrr_nr='". $ocrr_nr ."' , ocrr_ptrn='". implode('',$ocrr_ptrn) ."' , weight='". $weight ."' , j_ptrn='". implode('',$j_ptrn) ."'
								WHERE sys1 = '".$ta."' AND sys2 = '".$hol."' AND g = '".$g."'";
			$result = pg_query($conn, $query) or die("Cannot execute \"$query\"\n");
			*/
			
		}

		/******************************************************************
		 * Finding "the owner" according to the following criteria:
		 * 	preferred
		 * 	heaviest
		 * 	highest occurrences number
		 ******************************************************************/
		
		if ($posowners) {
			$owners_amnt = sizeOf ($posowners);
			if ($owners_amnt>1){
				for ($o_index=0; $o_index<$owners_amnt; $o_index++){
					$is_pref = $ta_hol_arr[$i]['hol'][$o_index]['is_pref'];
					$owner_index = $posowners[$o_index];
					if ($is_pref=='t')break;
					else if (in_array($posowners[$o_index],$posowners_oc))break;
				}
			}
			else $owner_index =  $posowners[0];
		}

		$owner_index = ($forceowner_index != -1)  ? $forceowner_index : $owner_index;
		$ta_hol_arr[$i]['owner'] = ($forceowner_index != -1)  ? $forceowner_index : $owner_index;
		$mishols[$i]['owner'] = ($forceowner_index != -1)  ? $forceowner_index : $owner_index;

		
		/******************************************************************
		 * UPDATE hol_out
		 * 	is_owner
		 ******************************************************************/

		if ($owner_index !== '') {
			
			$ta = $mishols[$owner_index]['sys1'];
			$hol = $mishols[$owner_index]['sys2'];
			$g = $mishols[$owner_index]['g'];
			
			$ta_res_arr[$ta.$hol.$g]['is_owner'] = 't';
			/*
			$query = "UPDATE hol_out SET is_owner='". 1 ."' 
								WHERE sys1 = '".$ta."' AND sys2 = '".$hol."' AND g = '".$g."'";
			$result = pg_query($conn, $query) or die("Cannot execute \"$query\"\n");
			*/
		}
	}

	/***********************************************************************
	 * Aqui se encuentra la biblioteca de apoyo a partir del owner
	 * la aux se calcula completando con la que tiene mayor peso/ocurrencia
	 ***********************************************************************/

	for ($i=0; $i<$ta_hol_amnt; $i++){ //por cada grupo...
		$hol_arr = $ta_hol_arr[$i]['hol'];
		$hol_amnt = sizeOf($hol_arr); //la cantidad de hol
		$mx_weight = 0;
		$weight = 0;
		$ocrr_nr  = 0;
		
		if($mishols[$i]['owner']) {

			$owner_ocrr_arr = $hol_arr[$ta_hol_arr[$i]['owner']]['ocrr_arr'];
			$owner_ocrr_amnt = sizeOf($owner_ocrr_arr);
			$weight_ptrn = $ta_hol_arr[$i]['weight_ptrn'];
			$ptrn = $ta_hol_arr[$i]['ptrn'];
			$ptrn_amnt = sizeOf($ptrn);
			$potaux_array = array();
			$potaux_array = array_fill(0,$hol_amnt,0);
			
			$denied_owner = array_map(
				function ($n){
					return intval(!$n);
				},
				$owner_ocrr_arr);

			for ($k=0; $k<$hol_amnt; $k++){ //por cada hol
				
				if (isset($hol_arr[$k]['ocrr_arr'])){ //esto e un parche porque falta una fila

					$ocrr_ptrn = $hol_arr[$k]['ocrr_arr'];
					$j_ptrn = $hol_arr[$k]['j_arr'];

					$aux_ptrn = array_map(
						function ($n, $m){
							return $n*$m;
						},
						$denied_owner, $ocrr_ptrn);

					$aux_weight_ptrn = array_map( 
						function($w, $a, $j){
							$j_factor = .5;
							return $w*$a*(($j>0)?$j_factor:1); 
						}, 
						$weight_ptrn, $aux_ptrn, $j_ptrn); 

					$aux_weight = array_sum($aux_weight_ptrn);
					$ocrr_nr  = array_sum($aux_ptrn);

			//se juntan los aul de mayor peso
					if ($aux_weight !== 0 ) {
						if ($aux_weight > $mx_weight ) {
							$mx_weight = $aux_weight;
							$potaux_array = array_fill(0,$hol_amnt,0);
							$potaux_array[$k] = 1;
						}
						else if ($aux_weight === $mx_weight ) {
							$potaux_array[$k] = 1;
						}
					}

					$ta = $hol_arr[$k]['sys1'];
					$hol = $hol_arr[$k]['sys2'];
					$g = $hol_arr[$k]['g'];

					$ta_res_arr[$ta.$hol.$g]['aux_ptrn'] = implode($aux_ptrn);


			/*
			$query = "UPDATE hol_out 
								SET aux_ptrn='". implode($aux_ptrn) ."'
								WHERE sys1 = '".$ta."' AND sys2 = '".$hol."' AND g = '".$g."'";
			$result = pg_query($conn, $query) or die("Cannot execute \"$query\"\n");	
			*/		

			}//fin del parche porque falta una fila
			
		}
		$ta_hol_arr[$i]['potaux_array'] = $potaux_array;
	}


}

		/******************************************************************
		* Aqui se escribe aux_ptrn en la tabla y si is_aux
		******************************************************************/

	for ($i=0; $i<$ta_hol_amnt; $i++){ //por cada grupo...
		
		$hol_arr = $ta_hol_arr[$i]['hol'];
		
		$hol_amnt = sizeOf($hol_arr); //la cantidad de hol
		
		if($ta_hol_arr[$i]['owner']) {
			
			$potaux_array = $ta_hol_arr[$i]['potaux_array'];

			for ($k=0; $k<$hol_amnt; $k++){ //por cada hol
				
				if (isset($hol_arr[$k]['ocrr_arr'])){ //esto e un parche porque falta una fila
					
					$ta = $hol_arr[$k]['sys1'];
					$hol = $hol_arr[$k]['sys2'];
					$g = $hol_arr[$k]['g'];

					$ta_res_arr[$ta.$hol.$g]['is_aux'] = $potaux_array[$k];

				/*
				$query = "UPDATE hol_out 
									SET is_aux='". $potaux_array[$k] ."'
									WHERE sys1 = '".$ta."' AND sys2 = '".$hol."' AND g = '".$g."'";
				$result = pg_query($conn, $query) or die("Cannot execute \"$query\"\n");
				*/

				}//fin del parche porque falta una fila
			}
		}
	}

	//printf("<br>Updating table hol_out: <br> ");

	// print_r($ta_res_arr);

	$ta_res_amnt = sizeof($ta_res_arr);
	$ta_nr = 0;
	foreach ($ta_res_arr as $key => $value){ // foreach sys1,sys2,g  write result in table hol_out_ptrn
		$value['is_owner'] = (($value['is_owner'] == '0') || ($value['is_owner'] == 'f')) ? 'f' : 't';
		$value['is_aux'] = (($value['is_aux'] == '0') || ($value['is_aux'] == 'f')) ? 'f' : 't';
	  // var_dump($value);
		Holding::find($mishols[$ta_nr]['id'])->update(['ocrr_nr' => $value['ocrr_nr'], 'ocrr_ptrn' => $value['ocrr_ptrn'], 'weight' => $value['weight'], 'j_ptrn' => $value['j_ptrn'], 'is_owner' => $value['is_owner'], 'aux_ptrn' => $value['aux_ptrn'], 'is_aux' => $value['is_aux']]);
		$finalptrn = $value['ptrn'];

		$ta_nr++;
		//if (($ta_nr % $trigger) == 0) echo $ta_nr.'|';
	}
	// die("\nThat's a better end of the story");
	Holdingsset::find($id)->update(['ptrn' => $finalptrn]);
}



// ***********************************************
function last_similar_ta_in_set( $ta_res_sim) {
// ***********************************************
// get number of last good ta candidate

	$ta_sim_last_good = 0;
	for ($rno = 0; $rno < sizeof($ta_res_sim); $rno++) {
		if ($ta_res_sim[$rno]['flag'] == '*' || $ta_res_sim[$rno]['flag'] == '=') { // included in the result set
			$ta_sim_last_good = $rno;  // remember last selected record
		} else { break; }
	}
	return $ta_sim_last_good;
}



// ***********************************************
function compare_field ($fld, $valO, $valC, $ta_res_sim, $rno) {
// ***********************************************
  // adapt weight for specific fields
  $similar = 0; // in case that both fields are empty
  if ($valO == '' and $valC == '') return 0;
  switch ($fld) {
  	case 'f008x' :
      if ($valC == 'uuuu' || $valO == 'uuuu') return 0; // don't compare incomplete information
      if ($valC == $valO) return 1; return 0;
      break;
      case 'f008y' :
      if ($valC == 'uuuu' || $valO == 'uuuu') return 0; // don't compare incomplete information
      if ($valC == $valO) return 1; return 0;
      break;
      case 'f022a' :
        // we have to compare from 1 to 19 occurrences. Every single match wins all
      $valO_arr = preg_split("/ *¬ */", $valO, null, PREG_SPLIT_NO_EMPTY);
      $valC_arr = preg_split("/ *¬ */", $valC, null, PREG_SPLIT_NO_EMPTY);
        if (count(array_intersect($valC_arr, $valO_arr)) > 0) { // check if at least one of the ISSN coincides
        	return 1;
        }
        break;
        case 'f260a' :
        // we have to compare 
        $valO_arr = preg_split("/ *¬ */", $valO, null, PREG_SPLIT_NO_EMPTY);
        $valC_arr = preg_split("/ *¬ */", $valC, null, PREG_SPLIT_NO_EMPTY);
        if (count(array_intersect($valC_arr, $valO_arr)) > 0) // check if at least one of the ISSN coincides
        return 1;
        else return $ta_res_sim[$rno]['s_'.$fld];
        break;
        case 'f780t' :
        // we have to compare 
        $valO_arr = preg_split("/ *¬ */", $valO, null, PREG_SPLIT_NO_EMPTY);
        $valC_arr = preg_split("/ *¬ */", $valC, null, PREG_SPLIT_NO_EMPTY);
        if (count(array_intersect($valC_arr, $valO_arr)) > 0) // check if at least one of the ISSN coincides
        return 1;
        else return $ta_res_sim[$rno]['s_'.$fld];
        break;
        case 'f785t' :
        // we have to compare 
        $valO_arr = preg_split("/ *¬ */", $valO, null, PREG_SPLIT_NO_EMPTY);
        $valC_arr = preg_split("/ *¬ */", $valC, null, PREG_SPLIT_NO_EMPTY);
        if (count(array_intersect($valC_arr, $valO_arr)) > 0) // check if at least one of the ISSN coincides
        return 1;
        else return $ta_res_sim[$rno]["s_".$fld];
        break;
        default :
        break;
    }
    return $similar;
}

// ***********************************************
function weight_every_fld($fld, $weight_model,$ta_res_sim, $rno, $fld_weight_model,$ta_res_sim, $rno, $fld_weight_model) {
// ***********************************************
	// calculate score value for $fld

	$fld_weight = $fld_weight_model[$weight_model];
	$score_delta = 0;
	$s_fld = 's_'.$fld; // form field name for similarity values
  if ($ta_res_sim[$rno][$s_fld] == 1) $score_delta = $ta_res_sim[$rno][$s_fld] * $fld_weight[$fld]['equ'];          // full match
  else if ($ta_res_sim[$rno][$s_fld] >= 0.8 ) $score_delta = $ta_res_sim[$rno][$s_fld] * $fld_weight[$fld]['sim'];  // presumable match
    else if ($ta_res_sim[$rno][$s_fld] < 0.8 ) $score_delta = $ta_res_sim[$rno][$s_fld] * $fld_weight[$fld]['dif']; // insecure or false match
    return $score_delta;
}




// ***********************************************
function show_time($date_from) {
// ***********************************************
  // show current time
	$date_now = new DateTime('now', new DateTimeZone('America/New_York'));
	$date_interval = $date_from->diff($date_now);
	return $date_interval->format('%H:%I:%S');
}

// ***********************************************
function cmp_score($a, $b) {
// ***********************************************
  // comparison operation
	if ($a['score'] == $b['score']) return 0;
	return ($a['score'] > $b['score']) ? -1 : 1;
}

// ***********************************************
function cmp_flag_score($a, $b) {
// ***********************************************
  // comparison operation
	if ($a['flag'] == '*') $a['flag'] = '<'; // replace for correct sorting
	if ($b['flag'] == '*') $b['flag'] = '<'; // replace for correct sorting
  $a_srt = sprintf("%1s%02d", $a['flag'], 500-$a['score']);  // sort with descending score
  $b_srt = sprintf("%1s%02d", $b['flag'], 500-$b['score']);  // sort with descending score
  if ($a_srt == $b_srt) return 0;
  return ($a_srt > $b_srt) ? -1 : 1;
}

function create_table($tab_name) {

	$conn_string = "host=localhost port=5433 dbname=bis user=postgres password=postgres+bis options='--client_encoding=UTF8'";
	$conn_string1 = "host=localhost port=5432 dbname=bis user=postgres password=postgres+bis options='--client_encoding=UTF8'";
	$con = pg_connect($conn_string) or ($con = pg_connect($conn_string1));

	$query  = "DROP TABLE IF EXISTS $tab_name; ";
	$query .= "CREATE TABLE $tab_name (sys1 char(10), sys2 char(10), score integer, flag char(1), upd timestamp)";
	$result = pg_query($con, $query); if (!$result) { echo "Error executing".$query."\n"; exit; }
}



function get_ptrn_position ($ocrr,$ptrn){
	$ptrn_size = sizeOf($ptrn);
	for ($i=0; $i<$ptrn_size; $i++){
		if ($ocrr===$ptrn[$i]) {
			return $i;
		}
	}
	return '?';
}


function normalize866a() {

	return true;
}