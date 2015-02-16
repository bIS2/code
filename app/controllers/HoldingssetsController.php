<?php
/*
*
*	Controls workflow with Holdings Set (HOS)
*
*/
$hop_no           	= 0;         // number of parts
$hol_nrm          	= '';        // saved hol f866a result normalized
$fld_list         	= array();   // All names of Knowledge Groups
$know_gr          	= '';        // knowledge group
$know             	= array();   // contains all knowledgeable elements for recognizing HOP
$hol_info          	= array();   // collect info about holding string
$hop_info         	= array();   // collect info about holding part
$hol_info['proc']  	= '';        // collects info about processing hol
$starttime        	= sprintf("%s", date("Y-m-d H:i:s"));
$stat             	= array();   // statistical info
$con              	= '';   // statistical info
$do_show_pattern	= '';   // statistical info
$do_give_info		= '';   // statistical info
$ho_val_prev		= '';   // statistical info
$con				= '';   // statistical info
$do_control			= '';   // statistical info
$do_show_know		= '';   // statistical info
$fld				= '';   // statistical info
$repl				= '';   // statistical info
$upper				= '';   // statistical info
$write_val			= '';   // statistical info

class HoldingssetsController extends BaseController {
	protected $layout = 'layouts.default';


	public function __construct() {
		// $this->beforeFilter( 'auth' );
	}

	/**
	 * Display a listing of the Holdings Set (HOS).
	 *
	 * @return Response
	 */
	public function Index()
	{

		/* SHOW/HIDE FIELDS IN HOLDINGS TABLES DECLARATION
		-----------------------------------------------------------*/

		define('ALL_FIELDS', 'actions;state;sys2;ocrr_ptrn;holtype;f008x;f008y;f022a;f072a;f245a;f245b;f245c;f245n;f245p;f246a;f260a;f260b;f260c;f300a;f300b;f300c;f310a;f362a;f500a;f505a;f710a;f710b;f770t;f772t;f780t;f785t;f852b;f852h;f852j;f866a;fe866a;fx866a;f866c;f866z;years;size;exists_online;is_current;has_incomplete_vols');
		define('GENERAL', 'actions;state;sys2;ocrr_ptrn;holtype;f008x;f008y;f022a;f072a;f245a;f245b;f245c;f245n;f245p;f246a;f260a;f260b;f260c;f300a;f300b;f300c;f310a;f362a;f500a;f505a;f710a;f710b;f770t;f772t;f780t;f785t;f852b;f852h;f852j;f866a;fe866a;fx866a;f866c;f866z;years;size;exists_online;is_current;has_incomplete_vols');
		define('TITLE', 'actions;f008x;f008y;f022a;f245a;f245b;f245n;f245p;f710a;f710b;f780t;785t');
		define('COMPARE', 'actions;sys2;ocrr_ptrn;f866a;f866aupdated;fx866a');

		/* User vars */
		$uUserName = Auth::user()->username;
		$uUserLibrary = Auth::user()->library;
		$uUserLibraryId = Auth::user()->library->id;

		// General View
		$cprofile = $_COOKIE[$uUserName.'_current_profile'];
		if ((!$cprofile) || ($cprofile == '')) {
			$cprofile = 'general';
		}
		setcookie($uUserName.'_current_profile', $cprofile, time()+60*60*24*3650);
		Session::put($uUserName.'_current_profile', $cprofile);

		// if (!$_COOKIE[$uUserName.'_general_fields'])
		setcookie($uUserName.'_general_fields', GENERAL, time()+60*60*24*3650);
		Session::put($uUserName.'_general_fields', GENERAL);

		// if (!$_COOKIE[$uUserName.'_title_fields'])
		setcookie($uUserName.'_title_fields', TITLE, time()+60*60*24*3650);
		Session::put($uUserName.'_title_fields', TITLE);

		// if (!$_COOKIE[$uUserName.'_compare_fields'])
		setcookie($uUserName.'_compare_fields', COMPARE, time()+60*60*24*3650);
		Session::put($uUserName.'_compare_fields', COMPARE);			
		
		if (Input::has('onlyprofiles')) {
			return View::make('holdingssets/profiles');
			die();
		}
		if (Input::has('holcontent')) {
			$holdingsset = Holdingsset::find(Input::get('holdingsset_id'));
			if ($holdingsset->recalled == 0)  {
				holdingsset_recall(Input::get('holdingsset_id'));
				$holdingsset->recalled = 1;
				$holdingsset->save();
			}
			$this->data['holdingssets'] = Holdingsset::whereId(Input::get('holdingsset_id'))->paginate(1);			
			return View::make('holdingssets/hols', $this->data);
		}
		else { 
			/* SEARCH ADVANCED FIELDS OPTIONS
			----------------------------------------------------------------*/
			define('ALL_SEARCHEABLESFIELDS', 'sys1;sys2;f008x;f008y;f022a;f072a;f245a;f245b;f245c;f245n;f245p;f246a;f260a;f260b;f260c;f300a;f300b;f300c;f310a;f362a;f500a;f505a;f710a;f710b;f770t;f772t;f780t;f785t;f852b;f852h;f852j;f866a;f866c;f866z;years;size;exists_online;is_current;has_incomplete_vols');

			// Is Filter
			$allsearchablefields = ALL_SEARCHEABLESFIELDS;
			$allsearchablefields = explode(';', $allsearchablefields);
			$is_filter = (Input::get('is_filter') == '1');
			if ((Input::get('owner') == 1) || (Input::get('aux') == 1) || (Input::get('white') == 1)) $is_filter = true;
			$this->data['is_filter'] = $is_filter;

			if (Input::get('clearorderfilter') == 1) {
				Session::put($uUserName.'_sortinghos_by', null);
				Session::put($uUserName.'_sortinghos', null);
			}

			$orderby = (Session::get($uUserName.'_sortinghos_by') != null) ? Session::get($uUserName.'_sortinghos_by') : 'f245a';
			$order 	= (Session::get($uUserName.'_sortinghos') != null) ? Session::get($uUserName.'_sortinghos') : 'ASC';

			$libraryusers = Library::find($uUserLibraryId)->users->lists('id');
			$libraryusers[] = -1;

			// Groups
			$this->data['groups'] = Group::orderby('name', 'ASC')->whereIn('user_id', $libraryusers)->get();

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
				if ($state == 'noreserveds') 
					$holdingssets = $holdingssets->noreserveds();
			}

			if ($this->data['is_filter']) {
				// Take all holdings
				$holdings = -1;
				// If filter by owner or aux
				if ((Input::get('owner') == 1) || (Input::get('aux') == 1)  || (Input::get('white') == 1) ) {
					$lockedsids = Locked::orderBy('id')->lists('holding_id');
					$lockedsids[] = -1;
					if ((Input::has('owner')) && (!(Input::has('aux')))) $holdings = Holding::whereLibraryId($uUserLibraryId) -> whereIsOwner('t') -> whereNotIn('id', $lockedsids)-> where('state', 'not like', '%reserved%');
					if (!(Input::has('owner')) && ((Input::has('aux')))) $holdings = Holding::whereLibraryId($uUserLibraryId) -> whereIsAux('t') -> whereNotIn('id', $lockedsids)-> where('state', 'not like', '%reserved%');
					if ((Input::has('owner')) && ((Input::has('aux'))))  {
						$holdings =  Holding::where('library_id','=',$uUserLibraryId)->where(function($query) use ($lockedsids) {
							$query->where('is_owner', '=', 't') -> whereNotIn('id', $lockedsids) -> where('state', 'not like', '%reserved%')
							->orWhere('is_aux', '=', 't');
						});						
					}
					if (Input::has('white')) { 
						$holdings =  Holding::whereLibraryId($uUserLibraryId) -> where('is_aux', '!=', 't')->where('is_owner', '!=', 't') -> whereNotIn('id', $lockedsids)-> where('state', 'not like', '%reserved%'); 
					}
					$hlist = array();
					$hlist = $holdings->select('holdings.holdingsset_id')->lists('holdings.holdingsset_id');
					$ids = (count($hlist) > 0) ? $hlist : [-1];
					$holdingssets = $holdingssets->whereIn('holdingssets.id', $ids);
					unset($hlist);
				}

				$openfilter = 0;
				$filtersys1 = false;
				$OrAndFilter = Input::get('OrAndFilter');
				// Verify if some value for advanced search exists.
				$holdings = -1;
				if (Input::get('filtered') == 1) {
					if ($holdings == -1) $holdings = DB::table('holdings');
					foreach ($allsearchablefields as $field) {
						if (Input::has($field)) {
							$value = Input::get($field);
							if ($value != '') {
								// var_dump($OrAndFilter);
								// var_dump($field);
								// var_dump($openfilter);
								$orand 		= $OrAndFilter[$openfilter-1];
								$compare 	= Input::get($field.'compare');
								$format 	= Input::get($field.'format');

								if ($field == 'sys1') {
									$filtersys1 = true;
									$sys1format = $format;
									$sys1compare = $compare;
									$sys1field = Input::get($field);
									$sys1orand = ($OrAndFilter[0] == 'OR') ? 'OR' : 'AND';
									$openfilter++; 
								}
								else {	
									$holdings = ($orand == 'OR') ? 	$holdings->OrWhereRaw( sprintf( $format, $compare, pg_escape_string(addslashes(strtolower( Input::get($field) ) ) )) ) :  
									$holdings->WhereRaw( sprintf( $format, $compare, pg_escape_string(addslashes(strtolower( Input::get($field) ) ) ) ) );  
									$openfilter++;		
									if ($field == 'f866a') {
										$format1 = str_replace('f866a', 'f866aupdated', $format);
										$compare1 = str_replace('f866a', 'f866aupdated', $compare);
										$holdings = $holdings->OrWhereRaw( sprintf( $format1, $compare1, pg_escape_string(addslashes(strtolower( Input::get($field) ) ) )) );
									}				
								}
							}
						}
					}

					if ($filtersys1 == true) {
						if (($sys1orand == 'OR') || ($openfilter == 1)) {
							if ($openfilter == 1) { 
								$ids = [-1];
							}
							else {
								$hlist = array();
								$hlist = $holdings->select('holdings.holdingsset_id')->lists('holdings.holdingsset_id');
								$ids = (count($hlist) > 0) ? $hlist : [-1];
								array_splice($ids, 65500);
							}
							$holdingssets = $holdingssets->where( function($query) use ($sys1format, $sys1compare, $sys1field, $ids) { 
								$query->WhereRaw( sprintf( $sys1format, $sys1compare, pg_escape_string(addslashes(strtolower( $sys1field ) ) ) ) )->orWhereIn('holdingssets.id', $ids); 
							});							
						}
						else {
							$hlist = array();
							$hlist = $holdings->select('holdings.holdingsset_id')->lists('holdings.holdingsset_id');
							$ids = (count($hlist) > 0) ? $hlist : [-1];							
							array_splice($ids, 65500);
							$holdingssets = $holdingssets->where( function($query) use ($sys1format, $sys1compare, $sys1field, $ids) { 
								$query->WhereRaw( sprintf( $sys1format, $sys1compare, pg_escape_string(addslashes(strtolower( $sys1field ) ) ) ) )->WhereIn('holdingssets.id', $ids); 
							});		
						}
					}
					else {
						$hlist = array();
						$hlist = $holdings->select('holdings.holdingsset_id')->lists('holdings.holdingsset_id');
						$ids = (count($hlist) > 0) ? $hlist : [-1];
						array_splice($ids, 65500);
						$holdingssets = $holdingssets->whereIn('holdingssets.id', $ids);
					}
					unset($holdings);
				}
				if ($openfilter == 0)  $this->data['is_filter'] = false;
			}

			define(HOS_PAGINATE, 50);

			if (Input::get('hos-1-hol') == 1) 
				$holdingssets = $holdingssets->whereHoldingsNumber(1);

			$this->data['holdingssets'] = $holdingssets->orderBy($orderby, $order)->orderBy('id', 'ASC')->with('holdings')->paginate(HOS_PAGINATE);
			unset($holdingssets);
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
	 * Show the form for creating a new Holdings Set (HOS).
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('holdingssets.create');
	}

	/**
	 * Store a newly created Holdings Set (HOS) in storage.
	 *
	 * @return Response
	 */
	public function store()	{
		$uUserName = Auth::user()->username;
		if (Input::has('urltoredirect')) {

			if (Input::has('restarprofile')) {
				$nprofile = Input::get('profile');
				setcookie($uUserName.'_'.$nprofile.'_fields', $fieldlist, time()-1600);	
				setcookie($uUserName.'_'.$nprofile.'_fields_showed', $fieldlist, time()-1600);	
				setcookie($uUserName.'_'.$nprofile.'_size_of_fields', $fieldlist, time()-1600);	
				setcookie($uUserName.'_current_profile', 'general', time()-1600);
				
				Session::forget($uUserName.'_'.$nprofile.'_fields');
				Session::forget($uUserName.'_'.$nprofile.'_fields_showed');
				Session::forget($uUserName.'_'.$nprofile.'_size_of_fields');
				Session::forget($uUserName.'_current_profile');

				$noDefprofiles = $_COOKIE[$uUserName.'_noDefprofiles'];
				$noDefprofiles = str_replace($nprofile.';', '', $noDefprofiles);
				$noDefprofiles = str_replace($nprofile, '', $noDefprofiles);
				setcookie($uUserName.'_noDefprofiles', $noDefprofiles, time()+60*60*24*3650);
			}
			else {

			// Files to show
				$newfields	= Input::get('fieldstoshow');
				$fieldlist 	= '';
				if ($newfields != '') {
					$fieldlist = implode(';', $newfields);
				}

			// All fields sizes
				$fieldsizes	= Input::get('sizes');
				$fieldlistsize 	= '';
				if ($fieldsizes != '') {
					$fieldlistsize = implode(';', $fieldsizes);
				}

			// Current Profile
				$cprofile = $_COOKIE[Auth::user()->username.'_current_profile'];
				$cprofile = (($cprofile == '') || ($cprofile == null) || ($cprofile)) ? Session::get(Auth::user()->username.'_current_profile') : $cprofile ;

			// Change to profile
				$nprofile = Input::get('profile');

				if ($cprofile == $nprofile) {
					setcookie($uUserName.'_'.$cprofile.'_fields_showed', $fieldlist, time()+60*60*24*3650);				
					setcookie($uUserName.'_'.$cprofile.'_size_of_fields', $fieldlistsize, time()+60*60*24*3650);
				}

			// If New Profile
				if (Input::get('new_profile')) {	
				// If the name is not the same to the current one			
					if (strtolower(Input::get('new_profile')) != $cprofile) {

						$allfields = $_COOKIE[$uUserName.'_'.$cprofile.'_fields'];

						$nprofile = strtolower(Input::get('new_profile'));

						setcookie($uUserName.'_'.$nprofile.'_fields', $allfields, time()+60*60*24*3650);
						Session::put($uUserName.'_'.$nprofile.'_fields', $allfields);				
						setcookie($uUserName.'_'.$nprofile.'_fields_showed', $fieldlist, time()+60*60*24*3650);				
						Session::put($uUserName.'_'.$nprofile.'_fields_showed', $fieldlist);				
						setcookie($uUserName.'_'.$nprofile.'_size_of_fields', $fieldlistsize, time()+60*60*24*3650);
						Session::put($uUserName.'_'.$nprofile.'_size_of_fields', $fieldlistsize);				

					// Custom profiles.
						$noDefprofiles = $_COOKIE[$uUserName.'_noDefprofiles'];

					// Fix to avoid repeated names form Custom profiles. 
						$noDefprofiles = str_replace($nprofile.';', '', $noDefprofiles);
						$noDefprofiles = str_replace($nprofile, '', $noDefprofiles);
						$noDefprofiles .= ($noDefprofiles == '') ? $nprofile : ';'.$nprofile;

						setcookie($uUserName.'_noDefprofiles', $noDefprofiles, time()+60*60*24*3650);
					}
				}

				setcookie($uUserName.'_current_profile', $nprofile, time()+60*60*24*3650);
				Session::put($uUserName.'_current_profile', $nprofile);

			}

			// var_dump($cprofile);die();
			// $cprofile = $_COOKIE[$uUserName.'_current_profile'];
			// var_dump(Input::get('sortinghos'));die();

			setcookie($uUserName.'_sortinghos_by', Input::get('sortinghos_by'), time()+60*60*24*3650);
			setcookie($uUserName.'_sortinghos', Input::get('sortinghos'), time()+60*60*24*3650);
			Session::put($uUserName.'_sortinghos_by', Input::get('sortinghos_by'));
			Session::put($uUserName.'_sortinghos', Input::get('sortinghos'));
			if (Input::get('reload') == 1) {	
				// var_dump(Input::all());
				$urltoredirect = str_replace('?onlyprofiles=1', '', Input::get('urltoredirect'));	
				// die($urltoredirect);	
				return Redirect::to($urltoredirect);
			}
			else {
				echo 'ok';
				die();
			}
		}
	}

	/**
	 * Display the specified Holdings Set (HOS).
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
	 * Show the form for editing the specified Holdings Set (HOS).
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		return View::make('holdingssets.edit');
	}

	/**
	 * Update the specified Holdings Set (HOS) in storage.
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
	 * Update the specified Holdings Set (HOS) in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function updatecustom()
	{
		// die(var_dump(Input::all()));
		$field = Input::get('field');
		$holdingsset = Holdingsset::find(Input::get('holdingsset'));
		$holdingsset->$field = Input::get('value');
		$holdingsset->save();
	}

	/**
	 * Update the specified Holdings Set (HOS) in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function recallallhos()
	{
		

		// $HOSS = DB::select('select * from holdingssets ORDER BY id LIMIT '.$init.' OFFSET 1')->get();
		// $HOSS = DB::table('users')->skip($init)->take(1)->get();

		// $HOSS = DB::select('select id from holdingssets ORDER BY id where');//->get();
		// $exclude = Holdingsset::where('holdings_number', '>', '101')->select('id')->lists('id');
		$HOSS = Holdingsset::where('holdings_number', '<', '30')->whereRecalledbylocks(0)->orderby('id', 'ASC')->select('id')->lists('id');
		foreach ($HOSS as $HOS) {
			// var_dump($HOS->id);
			// var_dump($HOS->holdings_number);
			holdingsset_recall($HOS);
			Holdingsset::find($HOS)->update(['recalled' => 1, 'recalledbylocks' => 1]);
		}
		// $urltoredirect = '/sets/recallallhos/'.($init + 1);
		// if ($HOSS) {
		// 	return Redirect::to($urltoredirect);
		// }
		// else {
			return 'OK';
		// }
	}


	/**
	 * Update the specified Holdings Set (HOS) in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function addthelockeds()
	{
		// $db_config = Config::get('database');
		// var_dump($db_config['connections']['pgsql']['database']);die();
		$HOLS = DB::select('select holding_id, user_id from lockeds_err ORDER BY id');//->get();
		foreach ($HOLS as $HOL) {
			// var_dump($HOL->holding_id);
			$oldHOLs = DB::select('select sys2, g from holdings_err where id = '.$HOL->holding_id);
			foreach ($oldHOLs as $oldHOL) {
				// var_dump($oldHOL->sys2);
				$holdings = Holding::whereSys2($oldHOL->sys2)->whereG($oldHOL->g)->where('state', 'not like', '%reserve%');
				if($holdings->count() > 0) {
					$holdings->update(['state' => 'blank_reserved']);
					$holdingsOK = $holdings -> paginate(100);
					foreach ($holdingsOK as $holding) {
					// var_dump($holding->id);
						$locked = new Locked;
						$locked -> holding_id = $holding->id;
						$locked -> user_id = $HOL->user_id;
						$locked->save();
					}
				}
				// die('ya');
			}
		}
		return 'OK';
	}

	/**
	 * Update the specified Holdings Set (HOS) in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function recallallhosnorecalled()
	{
		
		$HOSS = Holdingsset::where('holdings_number', '<', '101')->where('recalled', '!=', '1')->where('recalledbylocks', '!=', '1')->orderby('id', 'ASC')->select('id')->lists('id');
		foreach ($HOSS as $HOS) {
			holdingsset_recall($HOS);
			Holdingsset::find($HOS)->update(['recalled' => 1]);
		}
			return 'OK';
	}

	/**
	 * Update the specified Holdings Set (HOS) in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function recallallholholnrm()
	{
		$counter = 1;
		while ($counter > 0) {
			$holdings = DB::select('select * from holdings where recallholnrm != 1 LIMIT 100');//->get();
			$counter = count($holdings);
			foreach ($holdings as $holding) { 
				$sys2 = $holding -> sys2;
				$new866a = ($holding->f866aupdated == '') ? $holding->f866a : $holding->f866aupdated ;
				$new866a .= ' ';
				$newhol_nrm = normalize866a($new866a, $sys2);
				Holding::find($holding->id)->update(['f866aupdated'=>$new866a, 'hol_nrm' => $newhol_nrm, 'recallholnrm' => 1]);
			}
		}
		return 'OK';
	}

	/**
	 * Update the specified Holdings Set (HOS) in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function recallhoswidthlockeds()
	{
		
		// $HOSS = DB::select('select holding_id from lockeds');//->get();
		// $holsid = array();
		// foreach ($HOSS as $HOS) {
		// 	$holding_id = $HOS -> holding_id;
		// 	$holsid[] = $holding_id;
		// }
		$holsid = Locked::where('id', '>', '0')->select('holding_id')->lists('holding_id');
		// Holding::whereIn('id', $holsid)->update(['state' => 'reserved']);
		$holsid[] = -1;
		$HOSS = Holding::whereIn('id', $holsid)->select('holdingsset_id')->lists('holdingsset_id');
		$HOSS = array_unique($HOSS);
		foreach ($HOSS as $HOS) {
			$holdingsset = Holdingsset::find($HOS);
			if (($holdingsset->recalledbylocks != 1) && ($HOS != -1) && ($holdingsset->holdings_number < 101)) {
					holdingsset_recall($HOS);
					Holdingsset::find($HOS)->update(['recalledbylocks' => 1]);
			}
		}

		return 'Update Locked Info Successfully';
	}

	/**
	 * Remove the specified Holdings Set (HOS) from storage.
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
			$this -> data['hosholsid']  = Holdingsset::find($holding->holdingsset_id)->holdings()->select('id')->lists('id');
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
	Create a new HOS from a Holding
	------------------------------------------
	Params:
		$id: Holding id
		-----------------------------------------------------------------------------------*/
		public function putNewHOS($id) {
			$holdingsset_id = Input::get('holdingsset_id');
			
			if (Input::has('holding_id')) {
				$ids = implode(';',Input::get('holding_id'));
				$idsAux = explode('?',$ids);
				$idsA = $idsAux[0];
				$ids = explode(';',$idsA);
				// $ids = Input::get('holding_id');
				if (Input::has('update_hos') && (Input::get('update_hos') == 1)) {
					$ids[] = -1;
					Holding::whereIn('id', $ids)->update(['holdingsset_id'=>$holdingsset_id]);
					Holdingsset::find($holdingsset_id)->increment('holdings_number', count($ids));
					$recalled = array();
					foreach ($ids as $hol_id) {
						if ($hol_id != -1) {							
							$hos_id = Holding::find($hol_id)->holdingsset_id;						 
							Holdingsset::find($hos_id)->decrement('holdings_number');
							if (!(in_array($hos_id, $recalled))) { 
								holdingsset_recall($hos_id);
								$recalled[] = $hol_id;
							}
						}
					}
					holdingsset_recall($holdingsset_id);
					$holdingssets[] = Holdingsset::find($holdingsset_id);
				}
				else {
					$newhos_id = createNewHos($ids[0]);
					$ids[] = -1;
					Holding::whereIn('id', $ids)->update(['holdingsset_id'=>$newhos_id]);
					Holdingsset::find($holdingsset_id)->decrement('holdings_number', count($ids));
					Holdingsset::find($newhos_id)->update(['holdings_number' => count($ids), 'groups_number'=>0]);
					holdingsset_recall($holdingsset_id);
					if (Holdingsset::find($holdingsset_id)->holdings()->count() == 1) {
						Confirm::create([ 'holdingsset_id' => $holdingsset_id, 'user_id' => Auth::user()->id ]);
						// Holdingsset::find($holdingsset_id)->update(['state' => 'ok']);
					}
					holdingsset_recall($newhos_id);
					if (Holdingsset::find($newhos_id)->holdings()->count() == 1) {
						Confirm::create([ 'holdingsset_id' => $newhos_id, 'user_id' => Auth::user()->id ]);
						// Holdingsset::find($holdingsset_id)->update(['state' => 'ok']);
					}
					// Confirm::create([ 'holdingsset_id' => $newhos_id, 'user_id' => Auth::user()->id ]);
					// Holdingsset::find($newhos_id)->update(['state' => 'ok']);
					
					$holdingssets[] = Holdingsset::find($holdingsset_id);
					$holdingssets[] = Holdingsset::find($newhos_id);
				}
			}
			else {
				Holdingsset::find($holdingsset_id)->decrement('holdings_number');
				$newhos_id = createNewHos($id);
				Holdingsset::find($newhos_id)->update(['holdings_number' => 1, 'groups_number' => 0]);
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
			$holdingsset -> holdings()->update(['is_owner' => 'f', 'force_owner' => 'f', 'force_blue' => 'f', 'force_aux' => 'f']);

			Holding::find($id)->update(['is_owner'=>'t', 'force_owner' => 't']);

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
			$holding = Holding::find($id);

			$was_oner = (($holding->is_owner == '1') || ($holding->is_owner == 't')) ? true : false;

			if (Input::get('unique_aux') == '1') {
				$holdingsset = Holdingsset::find($holdingsset_id);
				$ptrn = Input::get('ptrn');
				$empty_ptrn = str_replace('1', '0', $ptrn);
				$holdingsset->holdings()->where('id', '!=', $id)->update(['is_aux' => 'f', 'aux_ptrn' => $empty_ptrn]);
				$holdingsset->holdings()->where('id', '!=', $id)->where('is_owner', 'f')->update(['fx866a' => '']);
				
				$fx866a = '';
				$tmpfx866a = '';
				$painters = explode('|',$holding->c_arr);
				$prtnall = explode('|', $holdingsset->ptrn);
				$auxs = $ptrn;
				$k  = -1;
				$ff = -1;
				$fy = -1;
				$ly = -1;
				for ($ff=0; $ff < strlen($auxs); $ff++) { 
					$aux = $auxs[$ff];
					if ($aux == 1) {
						$k++;
						if ($painters[$ff] == '>') {
							$tmpfx866a .= ($tmpfx866a == '') ? getSquareValue($prtnall[$ff]).' '.date('Y') : '; '.getSquareValue($prtnall[$ff]).' '.date('Y');
						}
						if ($painters[$ff] == ']') {
							$tmpfx866a .= ($tmpfx866a == '') ? (getSquareValue($prtnall[$ff-1]) + 1).' - ' : '; '.(getSquareValue($prtnall[$ff-1]) + 1).' - ';
						}
						else {						
							$tmpfx866a .= ($tmpfx866a == '') ? getSquareValue($prtnall[$ff]) : '; '.getSquareValue($prtnall[$ff]);
						}
						$fy = ($fy == -1) ? $ff : $fy;
						$ly = $ff;
					}
					else {
						if ($tmpfx866a != '') {
							if (($fy != -1) && ($ly != -1) && ($fy != $ly)) {
								$fx866a .= ($fx866a == '') ? getSquareValue($prtnall[$fy]).' - '.getSquareValue($prtnall[$ly]) : '; '.getSquareValue($prtnall[$fy]).' - '.getSquareValue($prtnall[$ly]);
								$fy = -1;$ly = -1;
								$tmpfx866a = '';
							}
							else {
								$fx866a .= ($fx866a == '') ? $tmpfx866a : '; '.$tmpfx866a;
								$fy = -1;$ly = -1;
								$tmpfx866a = '';
							}
						}
					}				
				}
				if ($tmpfx866a != '') {
					if (($fy != -1) && ($ly != -1) && ($fy != $ly)) {
						$fx866a .= ($fx866a == '') ? getSquareValue($prtnall[$fy]).' - '.getSquareValue($prtnall[$ly]) : '; '.getSquareValue($prtnall[$fy]).' - '.getSquareValue($prtnall[$ly]);
						$fy = -1;$ly = -1;
					}
					else {
						$fx866a .= ($fx866a == '') ? $tmpfx866a : '; '.$tmpfx866a;
						$fy = -1;$ly = -1;
					}			
				}			

				$holdingsset->holdings()->where('id', '=', $id)->update(['is_aux' => 't', 'is_owner' => 'f', 'aux_ptrn' => $ptrn, 'fx866a' => $fx866a]);				
			}
			else {
				$holdingsset = Holdingsset::find($holdingsset_id);
				$fx866a = '';
				$f866a = explode(';', $holding->f866a);
				$ptrn = Input::get('newauxptrn');

				$fx866a = '';
				$tmpfx866a = '';
				$painters = explode('|',$holding->c_arr);
				$prtnall = explode('|', $holdingsset->ptrn);
				$auxs = $ptrn;
				$k  = -1;
				$ff = -1;
				$fy = -1;
				$ly = -1;
				for ($ff=0; $ff < strlen($auxs); $ff++) { 
					$aux = $auxs[$ff];
					if ($aux == 1) {
						$k++;
						if ($painters[$ff] == '>') {
							$tmpfx866a .= ($tmpfx866a == '') ? getSquareValue($prtnall[$ff]).' '.date('Y') : '; '.getSquareValue($prtnall[$ff]).' '.date('Y');
						}
						if ($painters[$ff] == ']') {
							$tmpfx866a .= ($tmpfx866a == '') ? (getSquareValue($prtnall[$ff-1]) + 1).' - ' : '; '.(getSquareValue($prtnall[$ff-1]) + 1).' - ';
						}
						else {						
							$tmpfx866a .= ($tmpfx866a == '') ? getSquareValue($prtnall[$ff]) : '; '.getSquareValue($prtnall[$ff]);
						}
						$fy = ($fy == -1) ? $ff : $fy;
						$ly = $ff;
					}
					else {
						if ($tmpfx866a != '') {
							if (($fy != -1) && ($ly != -1) && ($fy != $ly)) {
								$fx866a .= ($fx866a == '') ? getSquareValue($prtnall[$fy]).' - '.getSquareValue($prtnall[$ly]) : '; '.getSquareValue($prtnall[$fy]).' - '.getSquareValue($prtnall[$ly]);
								$fy = -1;$ly = -1;
								$tmpfx866a = '';
							}
							else {
								$fx866a .= ($fx866a == '') ? $tmpfx866a : '; '.$tmpfx866a;
								$fy = -1;$ly = -1;
								$tmpfx866a = '';
							}
						}
					}				
				}
				if ($tmpfx866a != '') {
					if (($fy != -1) && ($ly != -1) && ($fy != $ly)) {
						$fx866a .= ($fx866a == '') ? getSquareValue($prtnall[$fy]).' - '.getSquareValue($prtnall[$ly]) : '; '.getSquareValue($prtnall[$fy]).' - '.getSquareValue($prtnall[$ly]);
						$fy = -1;$ly = -1;
					}
					else {
						$fx866a .= ($fx866a == '') ? $tmpfx866a : '; '.$tmpfx866a;
						$fy = -1;$ly = -1;
					}			
				}				

				$holding->update(['is_aux'=>'t', 'is_owner'=>'f', 'ocrr_ptrn'=> Input::get('newptrn'), 'aux_ptrn'=> Input::get('newauxptrn'), 'ocrr_nr' => Input::get('count'), 'force_aux' => 't', 'force_owner' => 'f', 'fx866a' => $fx866a]);
			}
			if ($was_oner) holdingsset_recall($holdingsset_id);
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
			$holding = Holding::find($id);
			$auxptrnOriginal = $holding->aux_ptrn;
			$aux = str_replace('1', '0', $auxptrnOriginal);
			$was_oner = (($holding->is_owner == '1') || ($holding->is_owner == 't')) ? true : false;
			$holding->update(['is_aux'=>'f', 'is_owner'=>'f', 'force_blue'=>'t', 'aux_ptrn' => $aux]);
			if ($was_oner) holdingsset_recall($holdingsset_id);
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
		public function putRecallHoldingsset($id) {
			$HOS = Holdingsset::find($id);
			$HOS->holdings()->update(['force_blue' => 'f', 'force_owner' => 'f', 'force_aux' => 'f']);
			$sys1 = $HOS->sys1;

			$holdings = $HOS->holdings()->get();

			// foreach ($holdings as $holding) {
			// 	$hnrm = str_replace(' ', '', $holding->hol_nrm);
			// 	if ($hnrm == '') {
			// 	$sys2 = $holding -> sys2;
			// 	$new866a = ($holding->f866aupdated == '') ? $holding->f866a : $holding->f866aupdated ;
			// 	$new866a .= ' ';
			// 	$newhol_nrm = normalize866a($new866a, $sys2);
			// 	$holding->update(['f866aupdated'=>$new866a, 'hol_nrm' => $newhol_nrm]);
			// 	}
			// }


			holdingsset_recall($id);
			$holdingssets[] = Holdingsset::find($id);
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
			$new866a = ($new866a == '') ?  Input::get('value') : $new866a;
			$holdingsset_id = Holding::find($id)->holdingsset_id;

			$newhol_nrm = normalize866a($new866a, Holding::find($id)->sys2);
			// echo 'Y el resultado es: <br>';
			// die(var_dump($new866a));
			$holding = Holding::find($id)->update(['f866aupdated'=>$new866a, 'hol_nrm' => $newhol_nrm]);

			holdingsset_recall($holdingsset_id);
			
			$holdingssets[] = Holdingsset::find($holdingsset_id);
			$newset = View::make('holdingssets/hos', ['holdingssets' => $holdingssets]);
			return $newset;
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
		$holding  	= Holding::find($id);
		return Holding::where(function($query) {
			$query->where('state','=','blank')->orWhere('state','=','revised_annotated');
			}) 
			->where( function($query) use ($holding) { $query->where('f245a', 'like', '%'.$holding->f245a. '%'); })
		    ->take(100)->get();
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
			$db_config = Config::get('database');
			$database = $db_config['connections']['pgsql']['database'];
			$username = $db_config['connections']['pgsql']['username'];
			$password = $db_config['connections']['pgsql']['password'];
			$conn_string = "host=localhost port=5432 dbname=".$database." user=".$username." password=".$password." options='--client_encoding=UTF8'";
			$con = pg_connect($conn_string);

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
$query = "SELECT ".$select_fld." FROM holdings WHERE sys2 = '$sys' AND state NOT LIKE '%reserved%'";
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
	$db_config = Config::get('database');
	$database = $db_config['connections']['pgsql']['database'];
	$username = $db_config['connections']['pgsql']['username'];
	$password = $db_config['connections']['pgsql']['password'];
	$conn_string = "host=localhost port=5432 dbname=".$database." user=".$username." password=".$password." options='--client_encoding=UTF8'";
	$con = pg_connect($conn_string);

	$query = "SELECT * FROM holdings WHERE holdingsset_id = ".$id." AND state NOT LIKE '%reserve%' ORDER BY sys2, score DESC LIMIT 100";
	$result = pg_query($con, $query) or die("Cannot execute \"$query\"\n".pg_last_error());
	$ta_arr = pg_fetch_all($result);

	$holdingsset_id = $id;

	/*******************************************************************/

	$hos = array();
	$hos['ptrn'] = array();
	$hos['hol'] = array();

	$hos['year_ptrn'] = array(); // ***** NEW! *****
	$hos['timeline'] = array();  // ***** NEW! *****

	/*******************************************************************/

	$ta_amnt = sizeOf($ta_arr);

	for ($i=0; $i<$ta_amnt; $i++) {
		$hol = $ta_arr[$i];
		$sys1  = $hol['sys1'];
		$sys2 = $hol['sys2'];
		$g   = $hol['g'];

		$hol_ptrn = $hol['hol_nrm'];
		
		$hol['ptrn_arr'] = (preg_match('/\w/',$hol_ptrn))?explode(';',$hol_ptrn):array(); // split on ";"

		if ($hol['ptrn_arr']){
			$hol_ptrn_amnt	= sizeOf($hol['ptrn_arr']);
			for ($l=0; $l<$hol_ptrn_amnt; $l++){
				$ptrn_piece = $hol['ptrn_arr'][$l]; // preservar el valor original
				$ptrn_chunks[0] = substr($ptrn_piece, 0, 16); //los primeros 16 carateres 0-15
				$ptrn_chunks[1] = substr($ptrn_piece, 17, 16); // los carateres del 16-32
				$chunks_amnt = sizeOf($ptrn_chunks);
				for ($p=0; $p<$chunks_amnt; $p++){
					if (preg_match('/                n?/',$ptrn_chunks[$p])){ // eliminate the empty chunks
						unset($ptrn_chunks[$p]);
					}
					else {
						$curr_year = substr($ptrn_chunks[$p], 8, 4);
						array_push($hos['ptrn'],$ptrn_chunks[$p]); // and store it in ptrn
						array_push($hos['year_ptrn'], $curr_year); // ***** NEW! *****

						//if (!isset($hos['year_ptrn'][$curr_year])) $hos['year_ptrn'][$curr_year] = array();
						//array_push($hos['year_ptrn'][$curr_year], substr($ptrn_chunks[$p], 0, 4));

					} 
				}				
			}
		}
		$hos['hol'][$i] = $hol;
	}

	$hos['ptrn'] = array_unique($hos['ptrn']);

	$hos['year_ptrn'] = array_unique($hos['year_ptrn']); // ***** NEW! *****
	asort($hos['year_ptrn']);
	$hos['year_ptrn'] = array_values($hos['year_ptrn']);
	// ksort() - sort associative arrays in ascending order, according to the key

	// order parts of pattern ----------------------------
	$tmparr = $hos['ptrn'];
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

	$tmparr = array_map(function($n){ return implode('',$n); },$tmparr); 
	$hos['ptrn'] = array_values($tmparr);

	/***********************************************************************
	 * For each holding (hol)...
	 * 	occurrences pattern
	 * 	completeness pattern
	 * 	weight
	 * 	number of occurrences
	 * 	potential owners by weight
	 * 	potential owners by occurrences
	 ***********************************************************************/

	$hol_amnt = sizeOf($hos['hol']); // number of HOL in HOS
	$ptrn = $hos['ptrn'];
	$ptrn_amnt = sizeOf($ptrn);
	$j_factor = .49;
	$mx_weight = 0;
	$mx_ocrr_nr = 0;
	$owner_index = '';
	$force_owner = -1;
	$pot_owners = array();;
	$posowners = array();	
	$posowners_oc = array();


	for ($i=0; $i<$hol_amnt; $i++) { // <------------------------------------------------- HOS
		$hol = $hos['hol'][$i];
		$ocrr_arr = ($ptrn_amnt>0)?array_fill(0,$ptrn_amnt,0):array(); // patrón de ocurrencias
		$j_arr = ($ptrn_amnt>0)?array_fill(0,$ptrn_amnt,0):array(); // patrón de incompletos
		$res_arr = ($ptrn_amnt>0)?array_fill(0,$ptrn_amnt,0):array(); // patrón de reservados NUEVO
		$c_arr = ($ptrn_amnt>0)?array_fill(0,$ptrn_amnt,"."):array(); // patron de continuidad NUEVO
		$weight = 0;
		if ($hol['ptrn_arr']){
			$hol_ptrn_amnt	= sizeOf($hol['ptrn_arr']);
			for ($l=0; $l<$hol_ptrn_amnt; $l++){ // <------------------------------------- HOL
				$ptrn_piece = $hol['ptrn_arr'][$l]; // preservar el valor original
				$ocrr_bgn = substr($ptrn_piece, 0, 16); //los primeros 16 carateres 0-15
				$ocrr_end = substr($ptrn_piece, 17, 16); // los carateres del 17-32
				$is_j = ($ptrn_piece[33] === 'j')?1:0;
				$is_connected = ($ptrn_piece[16] === '-')?true:false;
				$bgn = get_ptrn_position($ocrr_bgn,$ptrn);
				if ($bgn>=0){ // aquí!!!!!!!!!!!!!!!!
					if ($is_connected) {
						$end = get_ptrn_position($ocrr_end,$ptrn);
						$frst_pos = $bgn;
						$last_pos = ($end > 0)?$end:$ptrn_amnt-1; // aquí!!!!!!!!!!!!!!!!
						for ($pos=$frst_pos; $pos<$last_pos; $pos++){
							$ocrr_arr[$pos] = 1;
							$j_arr [$pos] = $is_j;
							$c_arr [$pos] = '-';
						}
						$c_arr [$frst_pos] = '[';
						$ocrr_arr[$last_pos] = 1;
						$j_arr [$last_pos] = $is_j;
						$c_arr [$last_pos] = ($end > 0)?']':'>';
						// calculando el weight
						$d_year = substr($ptrn[$last_pos], 8, 4)-substr($ptrn[$frst_pos], 8, 4);
						$d_vol = substr($ptrn[$last_pos], 0, 4)-substr($ptrn[$frst_pos], 0, 4);
						$weight = $weight+(($d_year>0)?$d_year:(($d_vol>0)?$d_vol:0))*pow($j_factor,$is_j)+1;
					}
					else {
						//aquí se marca la primera y única ocurrencia
						$ocrr_arr[$bgn] = 1;
						$j_arr [$bgn] = $is_j;
						$c_arr [$bgn] = '*';
						$weight = $weight + pow($j_factor,$is_j);
					}
				}
			}				
		}

		if ($hol['force_owner'] === 't') $force_owner = $i;
		if ($hol['pot_owner'] === 't') array_push($pot_owners,$i);
		
		$ocrr_nr  = array_sum($ocrr_arr);
		if ($ocrr_nr > $mx_ocrr_nr ) { 
			$mx_ocrr_nr = $ocrr_nr;
			$posowners_oc = array();	
			$posowners_oc[0] = $i;
		}
		else if ($ocrr_nr === $mx_ocrr_nr ) {
			array_push($posowners_oc,$i); //<----------------------------- posible owners by occurrences
		}
		
		if ($weight > $mx_weight ) { 
			$mx_weight = $weight;
			$posowners = array();	
			$posowners[0] = $i;
		}
		else if ($weight === $mx_weight ) {
			array_push($posowners,$i); //<------------------------------- posible owners by weight
		}

		$hos['hol'][$i]['ocrr_arr'] = $ocrr_arr;
		$hos['hol'][$i]['j_arr'] = $j_arr;
		$hos['hol'][$i]['c_arr'] = $c_arr;
		$hos['hol'][$i]['res_arr'] = $res_arr;
		$hos['hol'][$i]['weight'] = $weight;
		$hos['hol'][$i]['ocrr_nr'] = $ocrr_nr;
		$hos['hol'][$i]['is_owner'] = 'f';
		$hos['hol'][$i]['is_aux'] = 'f';

	}

	$hos['pot_owners'] = $pot_owners; // index del que está marcao como pot_owner
	$hos['force_owner'] = $force_owner; // index del que está forzao como owner

	/********************************************************************************
	 *
	 * Aquí se calcula el "O W N E R"
	 *
	 ********************************************************************************/
	
	if ($force_owner != -1) { // si hay un OWNER forzado es ese
		$owner_index = $force_owner;
	}

	else if ($pot_owners) { // si hay mas de un OWNER potencial se queda con el de mayor peso
		$owners_amnt = sizeOf ($pot_owners);
		if ($owners_amnt>1){
			for ($i=0; $owner_index<$owners_amnt; $i++){
				$owner_index = $pot_owners[$i];
				if (in_array($pot_owners[$i],$posowners))break;
			}
		}
		else $owner_index =  $pot_owners[0];
	}

	else if ($posowners) { // si hay un OWNER calculado
		$owners_amnt = sizeOf ($posowners);
		if ($owners_amnt>1){
			for ($i=0; $owner_index<$owners_amnt; $i++){
				$owner_index = $posowners[$i];
				if (in_array($posowners[$i],$posowners_oc))break;
			}
		}
		else $owner_index =  $posowners[0];
	}

	$hos['hol'][$owner_index]['is_owner'] = 't';
	$hos['owner_index'] = $owner_index; //solo pa tenerlo a mano en próximos cálculos

	/********************************************************************************
	 *
	 * Aquí se calculan los "A U X"
	 *
	 ********************************************************************************/

	$hol_amnt = sizeOf($hos['hol']); // number of HOL in HOS
	$owner_ocrr_arr = $hos['hol'][$owner_index]['ocrr_arr'];

	$denied_owner = array_map(
		function ($n){
			return intval(!$n);
		},
		$owner_ocrr_arr);

	for ($i=0; $i<$hol_amnt; $i++){ // <------------------------------------------------- HOS
		$ocrr_ptrn = $hos['hol'][$i]['ocrr_arr'];
		$aux_ptrn = array_map(
			function ($n, $m){
				return $n*$m;
			},
			$denied_owner, $ocrr_ptrn);
		$hos['hol'][$i]['aux_ptrn'] = $aux_ptrn;
		$is_aux  = (array_sum($aux_ptrn)>0)?'t':'f';
		$hos['hol'][$i]['is_aux'] = $is_aux;
	}

	/********************************************************************************
	 *
	 * comprobación, donde se imprimen los resultados pa ver cómo está la cosa
	 *
	 ********************************************************************************/

	$prtnall = $hos['ptrn'];
	// var_dump($hos);
	// var_dump($hos['hol'][0]);
	for ($i=0; $i<count($hos['hol']); $i++){
		$hol = $hos['hol'][$i];
		$sys1  = $hol['sys1'];
		$sys2 = $hol['sys2'];
		$g = $hol['g'];
		$hol_ptrn = implode(' ; ',$hol['ptrn_arr']);
		$o = implode($hol['ocrr_arr']);
		$j = implode($hol['j_arr']);
		$a = implode($hol['aux_ptrn']);
		$c = implode($hol['c_arr']);
		$weight = $hol['weight'];
		$is_owner = ($hol['is_owner'] === 't')?'o':' ';
		$pot_owner = ($hol['pot_owner'] === 't')?'p':' ';
		$is_aux = ($hol['is_aux'] === 't')?'a':' ';

		$f866a = ($hol['f866aupdated'] == '') ? explode(';', $hol['f866a']) : explode(';', $hol['f866aupdated']);

		$fx866a = '';
		$tmpfx866a = '';
		if ($hol['is_aux'] == 't') {
			$auxs = $hol['aux_ptrn'];
			$k  = -1;
			$ff = -1;
			$fy = -1;
			$ly = -1;
			foreach ($auxs as $aux) {
				$ff++;
				if ($aux == 1) {
					$k++;
					if ($hol['c_arr'][$ff] == '>') {
						$tmpfx866a .= ($tmpfx866a == '') ? getSquareValue($prtnall[$ff]).' '.date('Y') : '; '.getSquareValue($prtnall[$ff]).' '.date('Y');
					}
					if ($hol['c_arr'][$ff] == ']') {
						$tmpfx866a .= ($tmpfx866a == '') ? (getSquareValue($prtnall[$ff-1]) + 1).' - ' : '; '.(getSquareValue($prtnall[$ff-1]) + 1).' - ';
					}
					else {						
						$tmpfx866a .= ($tmpfx866a == '') ? getSquareValue($prtnall[$ff]) : '; '.getSquareValue($prtnall[$ff]);
					}
					$fy = ($fy == -1) ? $ff : $fy;
					$ly = $ff;
				}
				else {
					if ($tmpfx866a != '') {
						if (($fy != -1) && ($ly != -1) && ($fy != $ly)) {
							$fx866a .= ($fx866a == '') ? getSquareValue($prtnall[$fy]).' - '.getSquareValue($prtnall[$ly]) : '; '.getSquareValue($prtnall[$fy]).' - '.getSquareValue($prtnall[$ly]);
							$fy = -1;$ly = -1;
							$tmpfx866a = '';
						}
						else {
							$fx866a .= ($fx866a == '') ? $tmpfx866a : '; '.$tmpfx866a;
							$fy = -1;$ly = -1;
							$tmpfx866a = '';
						}
					}
				}				
			}
			if ($tmpfx866a != '') {
				if (($fy != -1) && ($ly != -1) && ($fy != $ly)) {
					$fx866a .= ($fx866a == '') ? getSquareValue($prtnall[$fy]).' - '.getSquareValue($prtnall[$ly]) : '; '.getSquareValue($prtnall[$fy]).' - '.getSquareValue($prtnall[$ly]);
					$fy = -1;$ly = -1;
				}
				else {
					$fx866a .= ($fx866a == '') ? $tmpfx866a : '; '.$tmpfx866a;
					$fy = -1;$ly = -1;
				}			
			}			
		}
		if ($hol['is_owner'] == 't') {

			$fx866a = ($hol['f866aupdated'] == '') ? $hol['f866a'] : $hol['f866aupdated'];
		}

		// echo '<pre>'.$i.$sp
		// 	.$hol['id'].$sp
		// 	.$sys1.$sp
		// 	.$sys2.$sp
		// 	.$g.$sp
		// 	.$o.$sp
		// 	.$a.$sp
		// 	.$is_owner.$sp
		// 	.$pot_owner.$sp
		// 	.$is_aux.$sp
		// 	.$c.'-->>aaaa'.$sp
		// 	.$weight.$sp
		// 	.$hol['is_aux'].$sp
		// 	.$j.$sp
		// 	.$hol_ptrn
		// 	.$sp.'</pre>';
		if ($hol['id'] > 0)
			Holding::find($hol['id'])->update(['ocrr_nr' => $hol['ocrr_nr'], 'ocrr_ptrn' => $o, 'weight' => $weight, 'j_ptrn' => $j, 'is_owner' => $hol['is_owner'],  'pot_owner' => $hol['pot_owner'], 'aux_ptrn' => $a, 'is_aux' => $hol['is_aux'], 'fx866a' => $fx866a, 'c_arr' => implode('|', $hol['c_arr'])]);
	}
	Holdingsset::find($id)->update(['ptrn' => implode('|', $hos['ptrn'])]);
	// die("\nThat's a better end of the story");
}

function getSquareValue($value) {
	$v1 = intval(substr($value, 0, 4));
	$v2 = intval(substr($value, 4, 4));
	$v3 = intval(substr($value, 8, 4));
	$v4 = intval(substr($value, 12, 4));
	$string = '';    
	if ($v1 > 0) $string .= 'v1';
	if ($v2 > 0) $string .= 'v2';
	if ($v3 > 0) $string .= 'v3';
	if ($v4 > 0) $string .= 'v4';
    // var_dump($string);
	switch ($string) {
		case 'v1':
		return $v1;
		break;
		
		case 'v2':
		return $v2;
		break;
		
		case 'v3':
		return $v3;
		break;
		
		case 'v4':
		return $v4;
		break;
		
		case 'v1v3':
		return $v1.'('.$v3.')';
		break;
		
		case 'v2v4':
		return $v2.'('.$v4.')';
		break;
		
		case 'v1v3v4':
		return $v1.'('.$v3.'/'.$v4.')';
		break;   

		case 'v2v3v4':
		return $v2.'('.$v3.'/'.$v4.')';
		break;

		case 'v3v4':
		return $v3.' - '.$v4;
		break;
		
		case 'v1v2v3v4':
		return $v1.'('.$v3.') - '.$v2.'('.$v4.')';
		break; 

		default:
		return '';
		break;
	}
	
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
	$db_config = Config::get('database');
	$database = $db_config['connections']['pgsql']['database'];
	$username = $db_config['connections']['pgsql']['username'];
	$password = $db_config['connections']['pgsql']['password'];
	$conn_string = "host=localhost port=5432 dbname=".$database." user=".$username." password=".$password." options='--client_encoding=UTF8'";
	$con = pg_connect($conn_string);

	$query  = "DROP TABLE IF EXISTS $tab_name; ";
	$query .= "CREATE TABLE $tab_name (sys1 char(10), sys2 char(10), score integer, flag char(1), upd timestamp)";
	$result = pg_query($con, $query); if (!$result) { echo pg_last_error(); exit; }
}

function get_ptrn_position ($ocrr, $ptrn){
	if (in_array($ocrr, $ptrn)){
		$ptrn_size = sizeOf($ptrn);
		for ($i=0; $i<$ptrn_size; $i++){
			if ($ocrr===$ptrn[$i]){
				return $i; 
			}
		}
	}
	else return -1;
}

$hop_no           	= 0;         // number of parts
$hol_nrm          	= '';        // saved hol f866a result normalized
$fld_list         	= array();   // All names of Knowledge Groups
$know_gr          	= '';        // knowledge group
$know             	= array();   // contains all knowledgeable elements for recognizing HOP
$hol_info          	= array();   // collect info about holding string
$hop_info         	= array();   // collect info about holding part
$current_year     	= '2014';
$hol_info['proc']  	= '';        // collects info about processing hol
$starttime        	= sprintf("%s", date("Y-m-d H:i:s"));
$stat             	= array();   // statistical info
$con              	= '';   // statistical info
$do_show_pattern	= '';   // statistical info
$do_give_info		= '';   // statistical info
$ho_val_prev		= '';   // statistical info
$con				= '';   // statistical info
$do_control			= '';   // statistical info
$do_show_know		= '';   // statistical info
$fld				= '';   // statistical info
$repl				= '';   // statistical info
$upper				= '';   // statistical info
$write_val			= '';   // statistical info
$filecontrol		= '';   // File control
$filename			= '';   // File control

function normalize866a($new866a, $sys2) {

	// error_reporting(E_ALL);
/* 
Project: SP2 - bIS
Function:
  get a single f886a
	recognizes the content using knowledge from TABLE hol_values
	writes elements recognized into hop_info
	normalizes every hop (voB1voB2yeB1yeB2voE1voE2yeE1yeE2IO) to in the form: VVVVvvvvYYYYyyyyVVVVvvvvYYYYyyyyIO
    - Explanation: vo = volume ; ye = year;  B = begin; E = end; 1= start period ; 2 = en period; I = incomplete; O = online

DevNotes:
  2012-10-01 08:35 pgt Start
	...
	2013-12-10 Adapted to bIS
	*/

/* ************************************************
 * Global variables
 ************************************************ */

date_default_timezone_set('America/Los_Angeles');  // correct
global $hop_no;         // number of parts
global $hol_nrm;        // saved hol f866a result normalized
global $fld_list;   // All names of Knowledge Groups
global $know_gr;        // knowledge group
global $know;   // contains all knowledgeable elements for recognizing HOP
global $hol_info;   // collect info about holding string
global $hop_info;   // collect info about holding part
global $starttime;
global $stat;   // statistical info
global $con;   // statistical info
global $do_show_pattern;
global $do_give_info;
global $ho_val_prev;
global $con;
global $current_year;
global $do_control;
global $do_show_know;
global $fld;
global $repl;
global $upper;
global $write_val;
global $filecontrol;
global $filename;

$filename = $_SERVER['DOCUMENT_ROOT'].'/'.$sys2.'.txt';

if (file_exists($filename)) {
	unlink($filename);
}

// $filecontrol = fopen($filename, "w+");

$hop_no           	= 0;         // number of parts
$hol_nrm          	= '';        // saved hol f866a result normalized
$fld_list         	= array();   // All names of Knowledge Groups
$know_gr          	= '';        // knowledge group
$know             	= array();   // contains all knowledgeable elements for recognizing HOP
$hol_info          	= array();   // collect info about holding string
$hop_info         	= array();   // collect info about holding part
$hol_info['proc']  	= '';        // collects info about processing hol
$starttime        	= sprintf("%s", date("Y-m-d H:i:s"));
$stat             	= array();   // statistical info

$db_config 		= Config::get('database');
$database 		= $db_config['connections']['pgsql']['database'];
$username 		= $db_config['connections']['pgsql']['username'];
$password 		= $db_config['connections']['pgsql']['password'];
$conn_string 	= "host=localhost port=5432 dbname=".$database." user=".$username." password=".$password." options='--client_encoding=UTF8'";
$con 			= pg_connect($conn_string);

// collect knowledge
$know['hG'] = acquire_knowledge('h', 'G', '');  // clearly recognizable strings at HOL level
$know['pF'] = acquire_knowledge('p', 'F', '');  // clearly recognizable string that could disturb in further processing
$know['pG'] = acquire_knowledge('p', 'G', '');  // clearly recognizable strings at HOP level
$know['pK'] = acquire_knowledge('p', 'K', '');  // strings at HOP level that can affect if used at start
$know['pL'] = acquire_knowledge('p', 'L', '');  // // strings at HOP level to eliminate not at last
$know['pN'] = acquire_knowledge('p', 'N', '');// strings at HOP level to eliminate at last

// if ($proc_flag['debug']) show_process_info($res_list);   // show information about current process

/* ================================================================== *
 * HOL 
 * ================================================================== */
// extract $ta de $res_list
$recno = 0;
$stat['A Record retrieved'] = 1; // !! we are looking ony at 1 HOL
$hol_str      = $new866a;       // get holding string. This string we will changed
$hol_sys      = $sys2;

// <--------------------------------- JUMP HEAR
RESTART_WITH_COMMA_REPLACED: // after , has been changed to ;

$hol_info['proc'] = '';            // init

// if ($proc_flag['show'])  printf("\n\n=== %s             :|%s|", $hol_sys, $hol_str);

/* ================================================================== *
* change things at HOL string level *
* ================================================================== */
// for each knowledge element
$know_gr = 'hG'; $uses = sizeof($know[$know_gr]['uses']); for ($c=0; $c < $uses; $c++) $hol_str = val_replace($hol_str);
// var_dump($hol_str);
// die('siguiendo holstr');

/* ======================================================================== *
* modify entire hol_str
* ======================================================================== */
// modify [L= ...; ...] to {L~ ...} so it will be kept together and not be split by later regex operations
$hol_str_prev = $hol_str;
$fld = 'LN='; $hol_str = save_LN($fld, $hol_str);  // save [L=...; ...; N=...] so it will be not split
// if ($hol_str_prev <> $hol_str) do_control('vLN', '', $hol_str_prev, '=>', $hol_str);

/* --------------------------------------------- *
 * Deal with the normal case                     *
 * --------------------------------------------- */
$ho_part = array(); // init
if (preg_match("/ *; */", $hol_str, $elem)) {  // check if we have something to do
	$hol_info['proc'] = 'Split by ;|';
	$ho_part = preg_split("/ *; */", $hol_str);
	$hop_no = 0; // init parts counter
} else $ho_part[0] = $hol_str;

/* ---------------------------------------------
* Loop through every HOP
* --------------------------------------------- */
$stat['A_Parts of holdings'] = 0;
for ($hop_no = 0; $hop_no < count($ho_part); $hop_no++) {
	$hop = $ho_part[$hop_no];           // we work on this string
	$hop_info[$hop_no] = array();     	// collect info about holding
	$hop_info[$hop_no]['NSER'] = 0;     // serial number starts at 0 (N.F. etc)
	$hop_info[$hop_no]['proc'] = '';    // init information collector of processing the hop
	$hop_orig = $hop;                   // save original string for later comparison
	$hop = trim($hop);                  // trim hop
	$stat['A_Parts of holdings']++;


	/* ***********************************************
	 * extract information elements
	 *********************************************** */
	// put {L=...} etc. in a separate variable
	// EX: 178(1994)-262(2006) {N~1-3| 8| 11| 32| 49}
	$hop_before = $hop;
	$know["L=N="] = "/^(.*) *(\{[NL]~[^\}]+\})$/";  // give regex pattern here
	if (preg_match($know["L=N="], $hop, $elem)) {  // do we have something to do?
		$hop_info["L=N="] = $elem[2];
		$hop  = $elem[1];
		$hop_info[$hop_no]["proc"] .= "cut {L=N=}|";
		$hop_info[$hop_no]['ICPL'] = 'j';
	}

	// Recognize and delete well recognizable but disturbing information  (F)
	$know_gr = "pF"; for ($c=0; $c < sizeof($know[$know_gr]['uses']); $c++) $hop = val_replace($hop);

	// put = ... in a separate variable
		 // but: What to do with : 42=1(1975)-45=4(1978)
		if ($hop == '==RECOGNIZED==' or $hop == '_VOID_' ) goto SKIP_TO_LAST_THINGS;  // shorten recognition path
		if (stripos($hop, '=') !== false) $hop = cut_holding_equivalent($hop);

	// Recognize and delete well recognizable information  (G)
		$know_gr = "pG"; $uses = sizeof($know[$know_gr]['uses']); for ($c=0; $c < $uses; $c++) $hop = val_replace($hop);
		if ($hop == '==RECOGNIZED==' or $hop == '_VOID_' ) goto SKIP_TO_LAST_THINGS;
		
	//$know_gr = "pH"; $uses = sizeof($know[$know_gr]['uses']); for ($c=0; $c < $uses; $c++) $hop = val_replace($hop);
	//$know_gr = "pI"; $uses = sizeof($know[$know_gr]['uses']); for ($c=0; $c < $uses; $c++) $hop = val_replace($hop);
	//$know_gr = "pJ"; $uses = sizeof($know[$know_gr]['uses']); for ($c=0; $c < $uses; $c++) $hop = val_replace($hop);
	//if ($hop == '==RECOGNIZED==' or $hop == '_VOID_' ) goto SKIP_TO_LAST_THINGS;
		
	// Recognize well recognized element at BOL or EOL
		$know_gr = "pK"; $uses = sizeof($know[$know_gr]['uses']); for ($c=0; $c < $uses; $c++) $hop = val_replace($hop);
		if ($hop == '==RECOGNIZED==' or $hop == '_VOID_' ) goto SKIP_TO_LAST_THINGS;

	// Recognize and delete less recognizable information 
		$know_gr = "pL"; $uses = sizeof($know[$know_gr]['uses']); for ($c=0; $c < $uses; $c++) $hop = val_replace($hop);
		$know_gr = "pN"; $uses = sizeof($know[$know_gr]['uses']); for ($c=0; $c < $uses; $c++) $hop = val_replace($hop);
		if ($hop == '==RECOGNIZED==' or $hop == '_VOID_' ) goto SKIP_TO_LAST_THINGS;
		
	// Clean string once more
		$know_gr = "pG"; $uses = sizeof($know[$know_gr]['uses']); for ($c=0; $c < $uses; $c++) $hop = val_replace($hop);

	// Third cleaning
		$know_gr = "pG"; $uses = sizeof($know[$know_gr]['uses']); for ($c=0; $c < $uses; $c++) $hop = val_replace($hop);

	// JUMP HERE <------------
	SKIP_TO_LAST_THINGS:   // jump here if work is already done
	

	// **********************************
	// check and adapt values for output
	// **********************************
	// avoid non numeric entries
	if (isset($hop_info[$hop_no]['yeB1']) && (!preg_match('/[0-9]+/', $hop_info[$hop_no]["yeB1"]) > 0)) $hop_info[$hop_no]["yeB1"] = '-';
	if (isset($hop_info[$hop_no]['yeB2']) && (!preg_match('/[0-9]+/', $hop_info[$hop_no]["yeB2"]) > 0)) $hop_info[$hop_no]["yeB2"] = '-';
	if (isset($hop_info[$hop_no]['yeE1']) && (!preg_match('/[0-9]+/', $hop_info[$hop_no]["yeE1"]) > 0)) $hop_info[$hop_no]["yeE1"] = '-';
	if (isset($hop_info[$hop_no]['yeE2']) && (!preg_match('/[0-9]+/', $hop_info[$hop_no]["yeE2"]) > 0)) $hop_info[$hop_no]["yeE2"] = '-';

	// reformat year if shorter than 4 digits
	if (isset($hop_info[$hop_no]['yeB2']) && (strlen($hop_info[$hop_no]['yeB2']) < 4)) $hop_info[$hop_no]['yeB2'] = reformat_val2($hop_info[$hop_no]['yeB1'], $hop_info[$hop_no]['yeB2']); 
	if (isset($hop_info[$hop_no]['yeE2']) && (strlen($hop_info[$hop_no]['yeE2']) < 4)) $hop_info[$hop_no]['yeE2'] = reformat_val2($hop_info[$hop_no]['yeE1'], $hop_info[$hop_no]['yeE2']); 

	// put number of month instead of the name
	if (isset($hop_info[$hop_no]['moB1'])) $hop_info[$hop_no]['moB1'] = &convert_month($hop_info[$hop_no]['moB1']);
	if (isset($hop_info[$hop_no]['moB2'])) $hop_info[$hop_no]['moB2'] = &convert_month($hop_info[$hop_no]['moB2']);
	if (isset($hop_info[$hop_no]['moE1'])) $hop_info[$hop_no]['moE1'] = &convert_month($hop_info[$hop_no]['moE1']);
	if (isset($hop_info[$hop_no]['moE2'])) $hop_info[$hop_no]['moE2'] = &convert_month($hop_info[$hop_no]['moE2']);


	// if type is empty, we recognized nothing
	if (!isset($hop_info[$hop_no]['type'])) {
		$hop_info[$hop_no]['type'] = '==UNKNOWN==';
		// do_control('vR!', '', $hop, '', '### '.$hop_info[$hop_no]['type']);
		// !!!! If "," in a too long hop encountered, assume ";" and RESTART
		if (preg_match('/\(?[0-9 \(\)-]{4,14}\)? *, *\(?[0-9]{1,4}\)?/', $hop, $elem)) { // Special cases: check if we should replace "," by ";"
			$hol_str = preg_replace('/ *, */', '; ', $hol_str);  // ### test this thoroughly. Until now it's a cheap patch!!!
			// do_control('vR2', 'RESTART', $hop, '', '>>>'.$hop);
			// unset ($hop_info[$hop_no]);
			goto RESTART_WITH_COMMA_REPLACED;
		}
	}
	// adapt recognition information
	// do_control('vRu', '', $hop, '))', $hop_info[$hop_no]['type']);
	if (!((strcmp($hop,'==RECOGNIZED==') == 0) or (strcmp($hop,'_VOID_') == 0))) {  // if $hop has not been recognized ...
		isset($stat['Z_UNKNOWN']) ? $stat['Z_UNKNOWN']++ : $stat['Z_UNKNOWN']=1;
		// do_control('vR!', '', $hop, '', '>> '.$hop_info[$hop_no]['type']);
	} else {
		if (!substr($hop_info[$hop_no]['type'],0,4) == 'MDL ')	$hop_info[$hop_no]['type'] = '==RECOGNIZED=+';
	}
	if ((strcmp($hop,'==RECOGNIZED==') == 0) and (!strcmp(substr($hop_info[$hop_no]['type'],0,4),'MDL ') == 0)) // if $hop has not been recognized ...
	$hop_info[$hop_no]['type'] = '==RECOGNIZED=+';

		// add "2014" into yeE1 when ta is ongoing
	if ($hop_no == count($ho_part) -1) {
		if (   isset($hop_info[$hop_no]['HY']) 
	    		&& isset($hop_info[$hop_no]['yeB1'])  // not only volumes, but also a year
	    		&& ! isset($hop_info[$hop_no]['yeE1'])
	    		)
			$hop_info[$hop_no]['yeE1'] = $current_year;
		// do_control('vCY', '', $hop, '=>', $hop_info[$hop_no]['type']);
	}

} // <- end of hop loop

// var_dump($hop_info);
$hol_nrm = normalize_result($hop_info);
// var_dump($hol_nrm);

// die();
// The End
// global $filecontrol;
// global $filename;

// fclose($filecontrol);

return $hol_nrm;
}


/* ======================================================================== *
 *                                Functions                                 *
 * ======================================================================== */

// ------------------------------------------------------------------------
function do_control($marker1, $model, $str_before, $marker2, $str_after) {
// ------------------------------------------------------------------------
// Purpose: prints manipulation a a string to the screen
	global $do_control, $proc_flag;
	global $filecontrol;
  // if ($proc_flag['control']) 
	fprintf($filecontrol, "\n%-3s %-25s : %-70s %2s %s", $marker1, $model, $str_before, $marker2, $str_after);
	fprintf($filecontrol, "\r");
}

// ------------------------------------------------------------------------
function val_replace($ho_val) {
// ------------------------------------------------------------------------
	global $know, $know_gr, $proc_flag, $do_show_pattern, $do_give_info, $hop_info, $hop_no, $stat;
	if ($ho_val == '==RECOGNIZED==') return $ho_val;  // already recognized, so go back

	for ($c=0; $c < count($know[$know_gr]['srch']); $c++) {  // for each regular expression in the group ...
		$regex = '/'.$know[$know_gr]['srch'][$c].'/'.$know[$know_gr]['uppe'][$c];  // build regex string. Add i for search case insensitive (uppe)
		$ho_val_prev = $ho_val;
		// do_control('vR~', '', $regex, '', '');
		if (preg_match($regex, $ho_val, $elem)) {  // check if we have something to do
			$ho_val = preg_replace($regex, $know[$know_gr]['repl'][$c], $ho_val);
			if ($ho_val_prev <> $ho_val) {
				// do_control('vR~', '', $regex, '', '');
				// do_control('vR^', $know[$know_gr]['mode'][$c], $ho_val_prev, '', $ho_val);
				// do_control('vRv', '', $know[$know_gr]['writ'][$c], '', '****');
			}
			if ($know[$know_gr]['writ'][$c] > '') {  // use the variables given with the regex string
				$vars = explode(';', $know[$know_gr]['writ'][$c]);
				for ($c1=0; $c1<count($vars); $c1++) {
					list($var, $val) = explode("=", $vars[$c1]);
					switch($var) { // store the information recognized
						case 'AUFBEWAHRUNG':
						switch ($val) {
							case '$1': // increment by 1
							$hop_info[$hop_no][$var]=$elem[1];
							// do_control('vRn', $var, $hop_info[$hop_no][$var], '', '$1');
							break;
							default:
							$hop_info[$hop_no][$var]=$val;
						}	  
						break;
						case 'NSER':  // NF = nSer
						switch ($val) {
							case 'NF++': // increment by 1
							$hop_info[$hop_no][$var]++;
							// do_control('vRn', $var, $hop_info[$hop_no][$var], '', '++');
							break;
							case '$1': // increment by 1
							$hop_info[$hop_no][$var]=$elem[1];
							// do_control('vRn', $var, $hop_info[$hop_no][$var], '', '$1');
							break;
							default:
							$hop_info[$hop_no][$var]=1;
						}
						// do_control('vRn', $var, $hop_info[$hop_no][$var], '', '??');
						break; // end NF++
						case 'UNIT':
						if (isset($hop_info[$hop_no][$var])) $hop_info[$hop_no][$var].= '; '.$val; else $hop_info[$hop_no][$var] = $val;
						break;
						default: $hop_info[$hop_no][$var] = $val;
						break;
					}
					// do_control('vRV', $var, $hop_info[$hop_no][$var], '', '**');
				}
			}
			if (substr($know[$know_gr]['mode'][$c],0,3) == 'MDL') {  // We have an MDL (= model)
			  $mdl = substr($know[$know_gr]['mode'][$c],4);          // cut pattern. Ex: MDL V(JJJJ)
			  $mdl = str_replace('-','(HY)', $mdl);                  // for better handling of "-"
			  $mdl = str_replace('m','M', $mdl);                     // handling of month m
			  $pom = preg_split("/[^A-Z0-9]+/", $mdl.' X');         // split model into it's parts. Add a recognizable last elem
			  if ($pom[0] == '') array_shift($pom);                  // cut empty one
			  array_pop($pom); // remove last element X
			  if (count($elem) > 1) array_shift($elem);              // remove hop entry at [0] (is whole string)
			  // prepare output  volB1 volB2 yearE1 yearE2 etc.
				$count['B'] = array ('vo' => 1, 'ye' => 1, 'he' => 1, 'mo' => 1, 'xx' => 1 );  // init B counter for every element
				$count['E'] = array ('vo' => 1, 'ye' => 1, 'he' => 1, 'mo' => 1, 'xx' => 1 );  // init E counter for every element
			  $phase = 'B'; // Format receiving field variables. Set B for begin
			  for($c2=0; $c2<count($pom); $c2++) {
			  	switch ($pom[$c2]) {
			  		case 'MM'  : $pom[$c2] = sprintf("%s%s%d", 'mo', $phase, $count[$phase]['mo']++);	break;
			  		case 'M'   : $pom[$c2] = sprintf("%s%s%d", 'mo', $phase, $count[$phase]['mo']++);	break;
			  		case 'V'   : $pom[$c2] = sprintf("%s%s%d", 'vo', $phase, $count[$phase]['vo']++); break;
			  		case 'VE1' : $pom[$c2] = sprintf("%s%s%d", 'vo', 'E',    '1'                   ); break;
			  		case 'N'   : $pom[$c2] = sprintf("%s%s%d", 'he', $phase, $count[$phase]['he']++); break;
			  		case 'JJJJ': $pom[$c2] = sprintf("%s%s%d", 'ye', $phase, $count[$phase]['ye']++);	break;
			  		case 'JJ'  : $pom[$c2] = sprintf("%s%s%d", 'ye', $phase, $count[$phase]['ye']++);	break;
			      case 'TT'  : $pom[$c2] = sprintf("%s%s%d", 'da', $phase, $count[$phase]['ta']++);	break; // ### Field not exists. Ok?
			      case 'HY'  : 
						  $elem_hy = array('-'); array_splice($elem, $c2, 0, $elem_hy); // insert - at HY position
							$phase = 'E';  // Set E for End after reaching HY
							break;
							default    : $pom[$c2] = sprintf("%s%s%d", $pom[$c2], '_', '0'); break;
						}
						if ($proc_flag['debug']) printf("(%d)   =: %-6s = %-20s\n", $hop_no, $pom[$c2], $elem[$c2]);
						if ($pom[$c2] > '') $hop_info[$hop_no][$pom[$c2]] =	$elem[$c2];
					}
					// do_control('MDL', $mdl, implode('|', $pom), '', implode('|', $elem));
				}
				if ($ho_val == '') {
					$ho_val = '==RECOGNIZED==';
					isset($stat['Z_RECOGNIZED']) ? $stat['Z_RECOGNIZED']++ : $stat['Z_RECOGNIZED']=1;
				}
				collect_proc_info($hop_info, $know[$know_gr]['mode'][$c], $hop_no, $ho_val, $elem[0]);
				// do_control('vR+', $know[$know_gr]['mode'][$c], $ho_val_prev, '', '|'.$ho_val.'|   {'.$know[$know_gr]['writ'][$c].')');
			} else {
				// do_control('vR-', $know[$know_gr]['mode'][$c], $ho_val_prev, '', $ho_val);
			}
		}
	//echo "@:"; print_r($hop_info[$hop_no]); echo ":@"; 
		return $ho_val;
	}

// ------------------------------------------------------------------------
	function cut_holding_equivalent($hop) {
// ------------------------------------------------------------------------
		global $hop_info;
		if ($equ_list = preg_split("/ *= */", $hop)) {
			$hop_prev = $hop;
			$hop = array_shift($equ_list);
			do_control('EQU', '', $hop_prev, '', $hop.'  {'.implode('|', $equ_list).'}');
		}
		return $hop;
	}

// ------------------------------------------------------------------------
	function collect_proc_info($hop_info, $model, $hop_no, $hop, $trigger) {
// ------------------------------------------------------------------------
		global $stat, $hop_info;
  // $trigger: what remains for recognition ????
		$hop_info[$hop_no]['type'] = $model;
		if (substr($model,0,3) == 'MDL') $model_s = 'T_'.$model; else $model_s = 'S_'.$model;
		isset($stat[$model_s]) ? $stat[$model_s]++ : $stat[$model_s] = 1;
  if (strcmp($hop,'_VOID_') == 0) // if $hop has been recognized as _VOID_
		isset($stat['Z_RECOGNIZED']) ? $stat['Z_RECOGNIZED']++ : $stat['Z_RECOGNIZED'] = 1;  // _VOID_ is ==RECOGNIZED==
		isset($hop_info[$hop_no]['proc']) ? $hop_info[$hop_no]['proc'] .= $model.": '".$trigger."' {".$hop."}| " : $hop_info[$hop_no]['proc'] = $model.": '".$trigger."' {".$hop."}| ";
		// do_control('STA', $model, $stat[$model_s], '', '');
	}

// ------------------------------------------------------------------------
	function reformat_val2($val1, $val2) {
// ------------------------------------------------------------------------
		if (strlen($val2) > 0) {
			$lng = strlen($val1) - strlen($val2);
			$valPrefix = substr($val1,0,$lng);
			if (substr($val1,$lng,strlen($val2)) > $val2) {
				$valPrefix++;
			}      
			if ($lng > 0) $val2 = substr($valPrefix,0,$lng).$val2;
		}
	// 1899-00
		return $val2;
	}

// ------------------------------------------------------------------------
	function convert_month($month) {
// ------------------------------------------------------------------------
// convert month string to something useful
		if (isset($month)) {
			switch ($month) {
    // season
				case (preg_match("/^(Frühling)$/", $month, $elem) ? true : false) :                     $month = 'fr';      break;
				case (preg_match("/^(Sommer)$/", $month, $elem) ? true : false) :                                 $month = 'so';      break;
				case (preg_match("/^(Herbst)$/", $month, $elem) ? true : false) :                                 $month = 'he';      break;
				case (preg_match("/^(Winter)$/", $month, $elem) ? true : false) :                                 $month = 'wi';      break;
    // semester
				case (preg_match("/^(Sommersemester|Sommerhalbjahr|S\.-S\.|S\.S\.|SS|SH)$/", $month, $elem) ? true : false) : $month = 'SS';  break;  
				case (preg_match("/^(Wintersemester|Winterhalbjahr|W\.-S\.|W\.-S\.|WS|WH)$/", $month, $elem) ? true : false): $month = 'WS';  break;
    // month
				case (preg_match("/^(January|Januar|gennaio|Jan\.?)$/", $month, $elem) ? true : false):                   $month = '01';      break;
				case (preg_match("/^(February|Februar|février|Feb\.?)$/", $month, $elem) ? true : false):                 $month = '02';      break;
				case (preg_match("/^(March|März|Mrz\.?)$/", $month, $elem) ? true : false):  $month = '03';      break;
				case (preg_match("/^(April|Apr\.?)$/", $month, $elem) ? true : false) :                           $month = '04';      break;
				case (preg_match("/^(May|Mai)$/", $month, $elem) ? true : false) :                                $month = '05';      break;
				case (preg_match("/^(June|Juni|Jun\.?)$/", $month, $elem) ? true : false) :                       $month = '06';      break;
				case (preg_match("/^(July|Juli|juillet|Jul\.?)$/", $month, $elem) ? true : false) :               $month = '07';      break;
				case (preg_match("/^(August|Aug\.?)$/", $month, $elem) ? true : false) :            $month = '08';      break;
				case (preg_match("/^(September|Sept\.?|Sep\.?)$/", $month, $elem) ? true : false):  $month = '09';      break;
				case (preg_match("/^(October|Oktober|Okt\.?|Oct\.?)$/", $month, $elem) ? true : false): $month = '10';  break;
				case (preg_match("/^(November|Nov\.?)$/", $month, $elem) ? true : false) :          $month = '11';      break;
				case (preg_match("/^(Dezember|December|Dec\.?|Dez\.?)$/", $month, $elem) ? true : false) :  $month = '12'; break;
				default:	                                                                          $month = "??";      break;
			}
			return $month;
		}
	}

// ------------------------------------------------------------------------
	function save_LN($fld, $ho_val) {
// ------------------------------------------------------------------------
  // change ; to , within [L=...] or [N=...]
		global $hol_info, $hop_info, $hop_no, $ho_val_prev;
		$know['L=N='] = "/^(.*) *(\[[NL]=[^\]]+\]) *(.*)$/";
		if (preg_match($know['L=N='], $ho_val, $elem)) {
    $elem[2] = preg_replace("/=/", '~', $elem[2]);  // replace 
    $elem[2] = preg_replace("/;/", '|', $elem[2]);  // replace
    $elem[2] = preg_replace("/\[/", '{', $elem[2]); // replace [] by {}
    $elem[2] = preg_replace("/\]/", '}', $elem[2]); // replace [] by {}
    $ho_val  = $elem[1].$elem[2].$elem[3];
    $hol_info['L=N='] = $elem[2];   // collect info about holdings
    collect_proc_info($hop_info, $fld, $hop_no, $ho_val, $elem[1]);
    // do_control('LN1', $fld, $ho_val_prev, '',$ho_val);
}
  if (preg_match($know['L=N='], $ho_val, $elem)) {   // do it twice for second [.=...]
    $elem[2] = preg_replace("/=/", '~', $elem[2]);  // replace ; by ,
    $elem[2] = preg_replace("/;/", '|', $elem[2]);  // replace ; by ,
    $elem[2] = preg_replace("/\[/", '{', $elem[2]); // replace [] by {}
    $elem[2] = preg_replace("/\]/", '}', $elem[2]); // replace [] by {}
    $ho_val = $elem[1].$elem[2].$elem[3];
    $hol_info['L=N='] = $elem[2];   // collect info about holdings
    collect_proc_info($hop_info, $fld, $hop_no, $ho_val, $elem[1]);
    // do_control('LN2', $fld, $ho_val_prev, '',$ho_val);
}
  // correct missing ]   14 rows
if (preg_match("/\[[LN]=/", $ho_val, $elem)) $ho_val .= ']';
  if (preg_match($know['L=N='], $ho_val, $elem)) {   // do it twice for second [.=...]
    $elem[2] = preg_replace("/=/", '~', $elem[2]);  // replace ; by ,
    $elem[2] = preg_replace("/;/", '|', $elem[2]);  // replace ; by ,
    $elem[2] = preg_replace("/\[/", '{', $elem[2]); // replace [] by {}
    $elem[2] = preg_replace("/\]/", '}', $elem[2]); // replace [] by {}
    $ho_val = $elem[1].$elem[2].$elem[3];
    $hol_info['L=N='] = $elem[2];   // collect info about holdings
    collect_proc_info($hop_info, $fld, $hop_no, $ho_val, $elem[1]);
    // do_control('LN3', $fld, $ho_val_prev, '',$ho_val);
}
return $ho_val;
}

// ------------------------------------------------------------------------
function show_statistics() {
// ------------------------------------------------------------------------
	global $stat;
	printf("\n\n************ %s: *********************\n", "Statistics");
	$group_prev = '';
	ksort($stat);
	foreach ($stat as $key => $val) {
		$group = substr($key,0,1);
		if ($group <> $group_prev)
			switch ($group) {
				case 'A' : printf("General Information:\n"); break;
				case 'S' : printf("Executed recognition steps:\n"); break;
				case 'T' : printf("Recognized Patterns:\n"); break;
				case 'Z' : printf("Recognition state:\n"); break;
			}
			$percentage = 100/$stat['A_Parts of holdings'] * $val;
			printf("  %-35s : %6d  %3d%%\n", substr($key,2), $val, $percentage);
			$group_prev = $group;
		}
	}

// ------------------------------------------------------------------------
	function write_to_screen() {
// ------------------------------------------------------------------------
  // show normalized string on screen
		global $hol_nrm;
		printf("NRM: %s\n", $hol_nrm);
	}	

// ------------------------------------------------------------------------
	function acquire_knowledge($use, $priority, $model) {
// ------------------------------------------------------------------------
  //acquire knowledge from database
		global $con, $do_show_know;
		$know = array();
		if ($use == '')
			$query = "SELECT use, prio, model, srch_a, srch, repl, upper, write_val FROM hol_values WHERE model ~* '$model' ORDER BY prio, model";
		else
			$query = "SELECT use, prio, model, srch_a, srch, repl, upper, write_val FROM hol_values WHERE model ~* '$model' AND use='$use' AND prio = '$priority' ORDER BY prio, model";
		pg_query($con, $query) or die("Cannot execute \"$query\"\n");
		$result = pg_query($con, $query); if (!$result) { echo 'Error executing '.$query."\n"; exit; }
		$know['uses'] = pg_fetch_all_columns($result, 0);
		$know['prio'] = pg_fetch_all_columns($result, 1);
		$know['mode'] = pg_fetch_all_columns($result, 2);
		$know['srca'] = pg_fetch_all_columns($result, 3);
		$know['srch'] = pg_fetch_all_columns($result, 4);
		$know['repl'] = pg_fetch_all_columns($result, 5);
		$know['uppe'] = pg_fetch_all_columns($result, 6);
		$know['writ'] = pg_fetch_all_columns($result, 7);
		if ($do_show_know) {
			printf("\n*** %s - Criteria: %s|%s|%s|\n", 'Show Knowledge', $use, $priority, $model);
			for ($c=0; $c < sizeof($know['uses']); $c++) {
				printf("%1s %1s %1s %-30s : %-80s %30s   (%s)\n", $know['uses'][$c], $know['prio'][$c], $know['uppe'][$c], $know['mode'][$c], $know['srch'][$c], $know['repl'][$c], $know['writ'][$c]);
			}
		}
		return $know;
	}

// ------------------------------------------------------------------------
	function do_know_control($marker) {
// ------------------------------------------------------------------------
		global $do_control, $do_show_know, $fld, $know, $repl, $upper, $write_val;
		if ($do_show_know) {
			printf("%-3s %-25s\n", $marker, $fld);
			for ($c=0; $c < sizeof($know[$fld]); $c++) {
      // printf("    %02d %s\n", $c, $know[$fld][$c]);
				printf("    %02d P:%-30s R:%-30s U:%1s W:%s\n", $c, 
					$know[$fld][$c], 
					$repl[$fld][$c], 
					$upper[$fld][$c], 
					$write_val[$fld][$c]);
			}
		}
	}


// ------------------------------------------------------------------------
	function normalize_result($hop_info) {
// ------------------------------------------------------------------------
  // normalize every hop. Pattern: VVVVvvvvYYYYyyyyVVVVvvvvYYYYyyyyIO
		$hol_nrm = array();
		$size = sizeof($hop_info);

		for ($i=0; $i < $size; $i++) {
		// write normalized string
			$hol_nrm[$i] = sprintf("%4s%4s%4s%4s%1s%4s%4s%4s%4s%1s",
				substr('    '.(isset($hop_info[$i]['voB1'])?$hop_info[$i]['voB1']:'    '),-4,4),
				substr('    '.(isset($hop_info[$i]['voB2'])?$hop_info[$i]['voB2']:'    '),-4,4),
				substr('    '.(isset($hop_info[$i]['yeB1'])?$hop_info[$i]['yeB1']:'    '),-4,4),
				substr('    '.(isset($hop_info[$i]['yeB2'])?$hop_info[$i]['yeB2']:'    '),-4,4),
				substr(   ' '.(isset($hop_info[$i]['HY']  )?$hop_info[$i]['HY']  :' '   ),-1,1),
				substr('    '.(isset($hop_info[$i]['voE1'])?$hop_info[$i]['voE1']:'    '),-4,4),
				substr('    '.(isset($hop_info[$i]['voE2'])?$hop_info[$i]['voE2']:'    '),-4,4),
				substr('    '.(isset($hop_info[$i]['yeE1'])?$hop_info[$i]['yeE1']:'    '),-4,4),
				substr('    '.(isset($hop_info[$i]['yeE2'])?$hop_info[$i]['yeE2']:'    '),-4,4),
				substr(   ' '.(isset($hop_info[$i]['ICPL'])?$hop_info[$i]['ICPL']:'    '),-1,1));
		}
	// var_dump(substr('    '.(isset($hop_info[0]['voB1'])?$hop_info[0]['voB1']:'    '),-4,4));
	// var_dump($hop_info);
	// var_dump($hol_nrm);
		return implode(';',$hol_nrm);
	}

// ------------------------------------------------------------------------
	function dt_diff($date1, $date2) {
// ------------------------------------------------------------------------
  // show time elapsed
		return $diff = abs(strtotime($date2) - strtotime($date1));
		return sprintf("%d years, %d months, %d days\n", $years, $months, $days, $hours, $mins, $secs);
	}




	/* NUEVO P3H */
//------------------------------------------------------------------------------------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------------------------------------------------------------------------------------//

	function backgroundPost($url) {
		$parts = parse_url ( $url );

		$fp = fsockopen ( $parts ['host'], isset ( $parts ['port'] ) ? $parts ['port'] : 80, $errno, $errstr, 30 );

		if (! $fp) {
			return false;
		} else {
			$out = "POST " . $parts ['path'] . " HTTP/1.1\r\n";
			$out .= "Host: " . $parts ['host'] . "\r\n";
			$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
			$out .= "Content-Length: " . strlen ( $parts ['query'] ) . "\r\n";
			$out .= "Connection: Close\r\n\r\n";
			if (isset ( $parts ['query'] ))
				$out .= $parts ['query'];

			fwrite ( $fp, $out );
			fclose ( $fp );

			return true;
		}
	}

//$r = new HTTPRequest('http://www.example.com'); 
//echo $r->DownloadToString(); 

	class HTTPRequest 
	{ 
    var $_fp;        // HTTP socket 
    var $_url;        // full URL 
    var $_host;        // HTTP host 
    var $_protocol;    // protocol (HTTP/HTTPS) 
    var $_uri;        // request URI 
    var $_port;        // port 
    
    // scan url 
    function _scan_url() 
    { 
    	$req = $this->_url; 

    	$pos = strpos($req, '://'); 
    	$this->_protocol = strtolower(substr($req, 0, $pos)); 

    	$req = substr($req, $pos+3); 
    	$pos = strpos($req, '/'); 
    	if($pos === false) 
    		$pos = strlen($req); 
    	$host = substr($req, 0, $pos); 

    	if(strpos($host, ':') !== false) 
    	{ 
    		list($this->_host, $this->_port) = explode(':', $host); 
    	} 
    	else 
    	{ 
    		$this->_host = $host; 
    		$this->_port = ($this->_protocol == 'https') ? 443 : 80; 
    	} 

    	$this->_uri = substr($req, $pos); 
    	if($this->_uri == '') 
    		$this->_uri = '/'; 
    } 
    
    // constructor 
    function HTTPRequest($url) 
    { 
    	$this->_url = $url; 
    	$this->_scan_url(); 
    } 
    
    // download URL to string 
    function DownloadToString() 
    { 
    	$crlf = "\r\n";
        $response =""; //added by PB
        
        // generate request 
        $req = 'GET ' . $this->_uri . ' HTTP/1.0' . $crlf 
        .    'Host: ' . $this->_host . $crlf 
        .    $crlf; 
        
        // fetch 
        $this->_fp = fsockopen(($this->_protocol == 'https' ? 'ssl://' : '') . $this->_host, $this->_port); 
        fwrite($this->_fp, $req); 
        while(is_resource($this->_fp) && $this->_fp && !feof($this->_fp)) 
        	$response .= fread($this->_fp, 1024); 
        fclose($this->_fp); 
        
        // split header and body 
        $pos = strpos($response, $crlf . $crlf); 
        if($pos === false) 
        	return($response); 
        $header = substr($response, 0, $pos); 
        $body = substr($response, $pos + 2 * strlen($crlf)); 
        
        // parse headers 
        $headers = array(); 
        $lines = explode($crlf, $header); 
        foreach($lines as $line) 
        	if(($pos = strpos($line, ':')) !== false) 
        		$headers[strtolower(trim(substr($line, 0, $pos)))] = trim(substr($line, $pos+1)); 

        // redirection? 
        	if(isset($headers['location'])) 
        	{ 
        		$http = new HTTPRequest($headers['location']); 
        		return($http->DownloadToString($http)); 
        	} 
        	else 
        	{ 
        		return($body); 
        	} 
        } 
    } 
/*
if(json_decode($xnpl) == NULL) {
	echo $xnpl." not valid json!";
}
else {
	$exemplars = json_decode($xnpl, true);
}
*/

function perfTree ($branch,$class='',$tpeof=''){
	$lang = isset($_SESSION['lang'])?$_SESSION['lang']:'en';
	$key = array_keys($branch);
	$size = sizeOf($key);
	for ($i=0; $i<$size; $i++){
		$child = (isset($branch[$key[$i]]['children']))?$branch[$key[$i]]['children']:array();
		$title = (isset($branch[$key[$i]]['title_'.$lang]))?$branch[$key[$i]]['title_'.$lang]:$branch[$key[$i]]['title_en'];
		echo '<li id="'.$key[$i].'" typeof="'.$tpeof.'">'.$title;
		if ($child) {
			echo '<ul class="'.$class.'">';
			perfTree($child,'categories toggle',$tpeof);
			echo '</ul>';
		}
		echo '</li>';
	}	
	return null;
}
