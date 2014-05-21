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
		$this->beforeFilter( 'auth' );
	}

	/**
	 * Display a listing of the Holdings Set (HOS).
	 *
	 * @return Response
	 */
	public function Index()
	{
		
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
			define('ALL_SEARCHEABLESFIELDS', 'sys1;sys2;f008x;f008y;f022a;f072a;f245a;f245b;f245c;f245n;f245p;f246a;f260a;f260b;f260c;f300a;f300b;f300c;f310a;f362a;f500a;f505a;f710a;f710b;f770t;f772t;f780t;f785t;f852b;f852h;f852j;f866a;866c;f866z;years;size;exists_online;is_current;has_incomplete_vols');

			// Is Filter
			$allsearchablefields = ALL_SEARCHEABLESFIELDS;
			$allsearchablefields = explode(';', $allsearchablefields);
			$is_filter = (Input::get('is_filter') == '1');
			if ((Input::get('owner') == 1) || (Input::get('aux') == 1)) $is_filter = true;
			$this->data['is_filter'] = $is_filter;

			/* SHOW/HIDE FIELDS IN HOLDINGS TABLES DECLARATION
			-----------------------------------------------------------*/
			define('DEFAULTS_FIELDS', 'sys2;f008x;f008y;f022a;f072a;f245a;f245b;f245c;f245n;f245p;f246a;f260a;f260b;f260c;f300a;f300b;f300c;f310a;f362a;f500a;f505a;f710a;f710b;f770t;f772t;f780t;f785t;f852b;f852h;f852j;f866a;866c;f866z;years;size;exists_online;is_current;has_incomplete_vols');
			define('ALL_FIELDS', 'sys2;f008x;f008y;f022a;f072a;f245a;f245b;f245c;f245n;f245p;f246a;f260a;f260b;f260c;f300a;f300b;f300c;f310a;f362a;f500a;f505a;f710a;f710b;f770t;f772t;f780t;f785t;f852b;f852h;f852j;f866a;866c;f866z;years;size;exists_online;is_current;has_incomplete_vols');

			/* User vars */
			$uUserName = Auth::user()->username;
			$uUserLibrary = Auth::user()->library;
			$uUserLibraryId = Auth::user()->library->id;
			// $uGroupname
			if (!isset($_COOKIE[$uUserName.'_fields_to_show_ok_hos'])) {
				if (Session::get($uUserName.'_fields_to_show_ok_hos') == 'ocrr_ptrn') {
					setcookie($uUserName.'_fields_to_show_ok_hos', DEFAULTS_FIELDS, time() + (86400 * 30));
					Session::put($uUserName.'_fields_to_show_ok_hos', DEFAULTS_FIELDS);
				}
				else {
					setcookie($uUserName.'_fields_to_show_ok_hos', Session::get($uUserName.'_fields_to_show_ok_hos'), time() + (86400 * 30));
				}
			}

			if ((Session::get($uUserName.'_fields_to_show_ok_hos') == 'ocrr_ptrn') || (Session::get($uUserName.'_fields_to_show_ok_hos') == '')) {
				setcookie($uUserName.'_fields_to_show_ok_hos', DEFAULTS_FIELDS, time() + (86400 * 30));
				Session::put($uUserName.'_fields_to_show_ok_hos', DEFAULTS_FIELDS);
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
				if ($state == 'noreserveds') 
					$holdingssets = $holdingssets->noreserveds();
			}

			if ($this->data['is_filter']) {
				// Take all holdings
				$holdings = -1;
				// If filter by owner or aux
				if ((Input::get('owner') == 1) || (Input::get('aux') == 1)) {
					if ((Input::has('owner')) && (!(Input::has('aux')))) $holdings = $uUserLibrary-> holdings() -> whereLibraryId($uUserLibraryId) -> whereIsOwner('t') -> whereNotIn('id', Locked::orderBy('id')->lists('holding_id'));
					if (!(Input::has('owner')) && ((Input::has('aux')))) $holdings = $uUserLibrary-> holdings() -> whereLibraryId($uUserLibraryId) -> whereIsAux('t') -> whereNotIn('id', Locked::orderBy('id')->lists('holding_id'));
					if ((Input::has('owner')) && ((Input::has('aux'))))  {
						$holdings = $uUserLibrary->holdings()->where('library_id','=',$uUserLibraryId)->where(function($query) {
							$query->where('is_owner', '=', 't') -> whereNotIn('id', Locked::orderBy('id')->lists('holding_id'))
							->orWhere('is_aux', '=', 't');
						});
					}		
				}

				$openfilter = 0;
				$OrAndFilter = Input::get('OrAndFilter');
				// Verify if some value for advanced search exists.
				if ($holdings == -1) $holdings = DB::table('holdings');//->orderBy('is_owner', 'DESC');

				foreach ($allsearchablefields as $field) {

					$value = Input::get($field);
					
					if ($value != '') {
						$orand 		= $OrAndFilter[$openfilter-1];
						$compare 	= Input::get($field.'compare');
						$format 	= Input::get($field.'format');

						if ($field == 'sys1') {
							$hos = Holdingsset::WhereRaw( sprintf( $format, $compare, pg_escape_string(addslashes(strtolower( Input::get($field) ) ) ) ) )->select('id')->lists('id');
							$hos[] = -1;
							$newholdings = Holding::whereIn('holdingsset_id', $hos)->select('id')->lists('id');
							$newholdings[] = -1;

							$holdings = ($orand == 'OR') ? $holdings->orWhereIn('id', $newholdings) : $holdings->whereIn('id', $newholdings);
							$openfilter++; 
						}
						else {	
								// var_dump(sprintf( $format, $compare, pg_escape_string(addslashes(strtolower( Input::get($field) ) ) ) ));die();
								$holdings = ($orand == 'OR') ? 	$holdings->OrWhereRaw( sprintf( $format, $compare, pg_escape_string(addslashes(strtolower( Input::get($field) ) ) )) ) :  
								$holdings->WhereRaw( sprintf( $format, $compare, pg_escape_string(addslashes(strtolower( Input::get($field) ) ) ) ) );  
								$openfilter++;						
						}
					}
				}
				if ($openfilter == 0)  $this->data['is_filter'] = false;
				$holList = $holdings->select('holdings.holdingsset_id')->lists('holdings.holdingsset_id');
				$ids = (count($holList) > 0) ? $holList : [-1];
				$holdingssets = $holdingssets->whereIn('holdingssets.id', $ids);
				unset($holdings);
			}

			define(HOS_PAGINATE, 50);
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
			setcookie($uUserName.'_fields_to_show_ok_hos', $fieldlist, time() + (86400 * 30));
			Session::put($uUserName.'_fields_to_show_ok_hos', $fieldlist);
			Session::put($uUserName.'_sortinghos_by', Input::get('sortinghos_by'));
			Session::put($uUserName.'_sortinghos', Input::get('sortinghos'));
			return Redirect::to(Input::get('urltoredirect'));
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
			if (Input::get('unique_aux') == 1) {
				$holdingsset = Holdingsset::find($holdingsset_id);
				$ptrn = Input::get('ptrn');
				$empty_ptrn = str_replace('1', '0', $ptrn);
				$holdingsset->holdings()->where('id', '!=', $id)->update(['is_aux' => 'f', 'aux_ptrn' => $empty_ptrn ]);
				$holdingsset->holdings()->where('id', '=', $id)->update(['is_aux' => 't', 'is_owner' => 'f', 'aux_ptrn' => $ptrn]);
			}
			else {

				Holding::find($id)->update(['is_aux'=>'t', 'is_owner'=>'f', 'ocrr_ptrn'=> Input::get('newptrn'), 'aux_ptrn'=> Input::get('newauxptrn'), 'ocrr_nr' => Input::get('count'), 'force_aux' => 't', 'force_owner' => 'f']);
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
			Holdingsset::find($id)->holdings()->update(['force_blue' => 'f', 'force_owner' => 'f', 'force_aux' => 'f']);
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
		$holding  = Holding::find($id);
		$ids  = Holdingsset::pendings()->select('id')->lists('id');
		$ids[] = -1;	
		// echo count($ids);
	// die();
		// return Holding::whereIn('holdingsset_id', $ids)->where(function($query) use ($holding) {	
		// 	$query = ($holding->f245a != '') ? $query->where('f245a', 'like', '%'.$holding->f245a. '%') : $query;
		// 	$query = ($holding->f245b != '') ? $query->orWhere('f245a', 'like', '%'.$holding->f245b. '%') : $query;
		// })->take(100)->get();
		return Holding::whereIn('holdingsset_id', $ids)->where('f245a', 'like', '%'.$holding->f245a. '%')->take(100)->get();
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
		$conn_string = "host=localhost port=5432 dbname=".$database." user=bispgadmin password=%^$-*/-bIS-2014*-% options='--client_encoding=UTF8'";
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
	$conn_string = "host=localhost port=5432 dbname=".$database." user=bispgadmin password=%^$-*/-bIS-2014*-% options='--client_encoding=UTF8'";
	$con = pg_connect($conn_string);

	$query = "SELECT * FROM holdings WHERE holdingsset_id = ".$id." ORDER BY sys2, score DESC LIMIT 100";
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
				
				// 2014-04-08 18:40 pgt -- commented out
				// $ptrn_piece[16] = '-'; //esto es un parche pa poner el - que faltaba en el hol_nrm
				
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

		// if ((Holding::find($ta_arr[$i]['id'])->locked) || (Holding::find($ta_arr[$i]['id'])->force_blue == 't') || (Holding::find($ta_arr[$i]['id'])->force_blue == '1')) {
		// 	$blockeds_hols[]['index'] = $i;
		// 	$blockeds_hols[]['id'] = $ta_arr[$i]['id'];
		// }

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
						// 2014-04-08 18:40 pgt -- commented out
						//$ocrr_piece[16] = '-'; //<------------------------------------ fixes the 16th char (patch)...			
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
						$ocrr_bgn = ($ocrr_bgn == '?') ? 0 : $ocrr_bgn;
						$ocrr_end = ($ocrr_end == '?') ? 0 : $ocrr_end;
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
	// die('toy aqui ahora');
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
	$db_config = Config::get('database');
	$database = $db_config['connections']['pgsql']['database'];
	$conn_string = "host=localhost port=5432 dbname=".$database." user=bispgadmin password=%^$-*/-bIS-2014*-% options='--client_encoding=UTF8'";
	$con = pg_connect($conn_string);

	$query  = "DROP TABLE IF EXISTS $tab_name; ";
	$query .= "CREATE TABLE $tab_name (sys1 char(10), sys2 char(10), score integer, flag char(1), upd timestamp)";
	$result = pg_query($con, $query); if (!$result) { echo pg_last_error(); exit; }
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

function normalize866a($new866a, $sys2) {
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
global $do_control;
global $do_show_know;
global $fld;
global $repl;
global $upper;
global $write_val;

$hop_no           = 0;         // number of parts
$hol_nrm          = '';        // saved hol f866a result normalized
$fld_list         = array();   // All names of Knowledge Groups
$know_gr          = '';        // knowledge group
$know             = array();   // contains all knowledgeable elements for recognizing HOP
$hol_info          = array();   // collect info about holding string
$hop_info         = array();   // collect info about holding part
$hol_info['proc']  = '';        // collects info about processing hol
$starttime        = sprintf("%s", date("Y-m-d H:i:s"));
$stat             = array();   // statistical info

$db_config = Config::get('database');
$database = $db_config['connections']['pgsql']['database'];
$conn_string = "host=localhost port=5432 dbname=".$database." user=bispgadmin password=%^$-*/-bIS-2014*-% options='--client_encoding=UTF8'";
$con = pg_connect($conn_string) or die('ERROR!!!');

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
	//do_control('vRu', '', $hop, '))', $hop_info[$hop_no]['type']);
	if (!((strcmp($hop,'==RECOGNIZED==') == 0) or (strcmp($hop,'_VOID_') == 0))) {  // if $hop has not been recognized ...
		isset($stat['Z_UNKNOWN']) ? $stat['Z_UNKNOWN']++ : $stat['Z_UNKNOWN']=1;
		//do_control('vR!', '', $hop, '', '>> '.$hop_info[$hop_no]['type']);
	} else {
			if (!substr($hop_info[$hop_no]['type'],0,4) == 'MDL ')	$hop_info[$hop_no]['type'] = '==RECOGNIZED=+';
	}
	if ((strcmp($hop,'==RECOGNIZED==') == 0) and (!strcmp(substr($hop_info[$hop_no]['type'],0,4),'MDL ') == 0)) // if $hop has not been recognized ...
		$hop_info[$hop_no]['type'] = '==RECOGNIZED=+';

} // <- end of hop loop

$hol_nrm = normalize_result($hop_info);

// The End
return $hol_nrm;
}


/* ======================================================================== *
 *                                Functions                                 *
 * ======================================================================== */

// ------------------------------------------------------------------------
// function do_control($marker1, $model, $str_before, $marker2, $str_after) {
// // ------------------------------------------------------------------------
// // Purpose: prints manipulation a a string to the screen
//   global $do_control, $proc_flag;
//   if ($proc_flag['control']) printf("\n%-3s %-25s : %-70s %2s %s", $marker1, $model, $str_before, $marker2, $str_after);
// }

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
			if (substr($know[$know_gr]['mode'][$c],0,3) == 'MDL') {  // recognize data in MDL
			  $mdl = substr($know[$know_gr]['mode'][$c],4);  // ex: MDL V(JJJJ)
			  $mdl = str_replace('-','(HY)', $mdl); // for better handling of "-"
			  $pom = preg_split("/[^A-Z0-9]+/", $mdl.' X');  // split model into it's parts
			  if ($pom[0] == '') array_shift($pom);
			  array_pop($pom); // remove last element X
			  if (count($elem) > 1) array_shift($elem);  // remove hop entry at [0] (is whole string)
			  // prepare output  volB1 volB2 yearE1 yearE2 etc.
				$count['B'] = array ('vo' => 1, 'ye' => 1, 'he' => 1, 'mo' => 1, 'xx' => 1 );  // init B counter for every element
				$count['E'] = array ('vo' => 1, 'ye' => 1, 'he' => 1, 'mo' => 1, 'xx' => 1 );  // init E counter for every element
			  $phase = 'B'; // Format receiving field variables. Set B for begin
			  for($c2=0; $c2<count($pom); $c2++) {
			    switch ($pom[$c2]) {
			      case 'V'   : $pom[$c2] = sprintf("%s%s%d", 'vo', $phase, $count[$phase]['vo']++); break;
			      case 'VE1' : $pom[$c2] = sprintf("%s%s%d", 'vo', 'E',    '1'                   ); break;
			      case 'N'   : $pom[$c2] = sprintf("%s%s%d", 'he', $phase, $count[$phase]['he']++); break;
			      case 'JJJJ': $pom[$c2] = sprintf("%s%s%d", 'ye', $phase, $count[$phase]['ye']++);	break;
			      case 'JJ'  : $pom[$c2] = sprintf("%s%s%d", 'ye', $phase, $count[$phase]['ye']++);	break;
			      case 'TT'  : $pom[$c2] = sprintf("%s%s%d", 'da', $phase, $count[$phase]['ta']++);	break; // ### Field not exists. Ok?
			      case 'MM'  : $pom[$c2] = sprintf("%s%s%d", 'mo', $phase, $count[$phase]['mo']++);	break;
			      case 'm'   : $pom[$c2] = sprintf("%s%s%d", 'mo', $phase, $count[$phase]['mo']++);	break;
			      case 'HY'  : 
						  $elem_hy = array('-'); array_splice($elem, $c2, 0, $elem_hy); // insert - at HY position
							$phase = 'E';  // Set E for End after reaching HY
							break;
						default    : $pom[$c2] = sprintf("%s%s%d", $pom[$c2], '_', '0'); break;
					}
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
		// do_control('EQU', '', $hop_prev, '', $hop.'  {'.implode('|', $equ_list).'}');
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
		$hol_nrm[$i] = sprintf("%4s%4s%4s%4s%1s%4s%4s%4s%4s%1s%1s",
			substr('    '.(isset($hop_info[$i]['voB1'])?$hop_info[$i]['voB1']:'    '),-4,4),
			substr('    '.(isset($hop_info[$i]['voB2'])?$hop_info[$i]['voB2']:'    '),-4,4),
			substr('    '.(isset($hop_info[$i]['yeB1'])?$hop_info[$i]['yeB1']:'    '),-4,4),
			substr('    '.(isset($hop_info[$i]['yeB2'])?$hop_info[$i]['yeB2']:'    '),-4,4),
			substr(   ' '.(isset($hop_info[$i]['hy']  )?$hop_info[$i]['hy']  :' '   ),-1,1),
			substr('    '.(isset($hop_info[$i]['voE1'])?$hop_info[$i]['voE1']:'    '),-4,4),
			substr('    '.(isset($hop_info[$i]['voE2'])?$hop_info[$i]['voE2']:'    '),-4,4),
			substr('    '.(isset($hop_info[$i]['yeE1'])?$hop_info[$i]['yeE1']:'    '),-4,4),
			substr('    '.(isset($hop_info[$i]['yeE2'])?$hop_info[$i]['yeE2']:'    '),-4,4),
			substr(   ' '.(isset($hop_info[$i]['ICPL'])?$hop_info[$i]['ICPL']:'    '),-1,1),
			substr(   ' '.(isset($hop_info[$i]['ONLINE'])?$hop_info[$i]['ONLINE']:'  '),-1,1));
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