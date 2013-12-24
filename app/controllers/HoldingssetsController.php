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
			define('ALL_SEARCHEABLESFIELDS', '022a;245a;245b;245c;246a;260a;260b;300a;300b;300c;310a;362a;500a;505a;710a;770t;772t;780t;785t;852b;852c;852h;852j;866a;866z');

			// Is Filter
			$allsearchablefields = ALL_SEARCHEABLESFIELDS;
			$allsearchablefields = explode(';', $allsearchablefields);
			$is_filter = false;
			foreach ($allsearchablefields as $field) {
				$value = Input::get('f'.$field);
				if ($value != '') {
					$is_filter = true;
					break;
				}
			}
			if ((Input::get('owner') == 1) || (Input::get('aux') == 1)) $is_filter = true;
			$this->data['is_filter'] = $is_filter;

			
			/* SHOW/HIDE FIELDS IN HOLDINGS TABLES DECLARATION
			-----------------------------------------------------------*/
			define('DEFAULTS_FIELDS', '245a;245b;ocrr_ptrn;022a;260a;260b;362a;710a;310a;246a;505a;770t;772t;780t;785t;852c;852j');
			define('ALL_FIELDS', '245a;245b;ocrr_ptrn;022a;260a;260b;362a;710a;310a;246a;505a;770t;772t;780t;785t;852c;852j');

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
			// var_dump($this->data['groups']);
			// var_dump($this->data['group_id']);die();
			$holdingssets = ($this->data['group_id'] != '') ? Group::find(Input::get('group_id'))->holdingssets() : Holdingsset::orderBy($orderby, $order);
					
			$state = Input::get('state');

			if (isset($state)) {
				if ($state == 'ok') 
					$holdingssets = $holdingssets->corrects()->ok();
				if ($state == 'pending') 
					$holdingssets = $holdingssets->corrects()->pendings();
				if ($state == 'annotated') 
					$holdingssets = $holdingssets->corrects()->pendings()->annotated();	
				if ($state == 'incorrects') 
					$holdingssets = $holdingssets->incorrects();
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
						// $owners = $uUserLibrary-> holdings() -> whereIsOwner('t')->lists('id');
						// $auxs = $uUserLibrary-> holdings() -> whereIsAux('t')->lists('id');
						// $holdings = $uUserLibrary->holdings()->whereIn('id', array_unique(array_merge($owners, $auxs)));
					}		
				}

				$openfilter = 0;
				// Verify if some value for advanced search exists.
				if ($holdings == -1) $holdings = DB::table('holdings')->orderBy('is_owner', 'DESC');
				foreach ($allsearchablefields as $field) {
					$value = Input::get('f'.$field);
					if ($value != '') {
						$orand = Input::get('OrAndFilter')[$openfilter-1];
						$holdings = ($orand == 'OR') ? 	$holdings->OrWhereRaw( sprintf( Input::get('f'.$field.'format'), 'LOWER('.'f'.$field.')', pg_escape_string(addslashes(strtolower( Input::get('f'.$field) ) ) )) ) :  
																						$holdings->WhereRaw( sprintf( Input::get('f'.$field.'format'), 'LOWER('.'f'.$field.')', pg_escape_string(addslashes(strtolower( Input::get('f'.$field) ) ) ) ) );  
						$openfilter++; 
					}
				}
				if ($openfilter == 0)  $this->data['is_filter'] = false;
				$holList = $holdings->select('holdings.holdingsset_id')->lists('holdings.holdingsset_id');
			  $ids = (count($holList) > 0) ? $holList : [-1];
			  $holdingssets = $holdingssets->whereIn('holdingssets.id', $ids);
			  unset($holdings);
			}
			define(HOS_PAGINATE, 20);
			$this->data['holdingssets'] = $holdingssets->orderBy($orderby, $order)->with('holdings')->paginate(HOS_PAGINATE);
			unset($holdingssets);

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
		$this -> data['holdings']  = recall_holdings($id);
		return View::make('holdingssets.recallingholdings', $this -> data);
	}

	/* ---------------------------------------------------------------------------------
	Recall Holdings from a specific holding
	--------------------------------------
	Params:
		$id: Holding id
-----------------------------------------------------------------------------------*/
	public function getSimilaritySearch($id) {
		$this -> data['holdings']  = similarity_search($id);
		return View::make('holdingssets.similarityresults', $this -> data);
	}

/* ---------------------------------------------------------------------------------
	Create a new HOS from only from a Holding
	------------------------------------------
	Params:
		$id: Holding id
-----------------------------------------------------------------------------------*/
	public function putNewHOS($id) {

		if (Input::has('holding_id')) {
			$ids = Input::get('holding_id');
			$newhos_id = createNewHos($ids[0]);
			Holding::whereIn('id', $ids)->update(['holdingsset_id'=>$newhos_id]);
			Holdingsset::find($newhos_id)->update(['holdings_number' => count($ids), 'groups_number'=>0]);
			holdingsset_recall($newhos_id);
		}
		else {
			$newhos_id = createNewHos($id);
		}

		$holdingsset_id = Input::get('holdingsset_id');
		holdingsset_recall($holdingsset_id);
		$holdingssets[] = Holdingsset::find($holdingsset_id);
		$holdingssets[] = Holdingsset::find($newhos_id);
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

		$unsetcurrentowner = $holdingsset -> holdings()->update(['is_owner' => 'f', 'force_owner' => false]);
		$holding = Holding::find($id)->update(['is_owner'=>'t', 'is_aux'=>'f', 'force_owner' => true, 'force_aux' => false]);

		holdingsset_recall($holdingsset_id);
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
	public function putForceAux($id) {
		// aux_ptrn-- poner en 1 los marcados
		// ocrr_ptrn-- poner en 1 los marcados

		// is_aux -> t
		// is_owner -> false

		// weight Cantidad de 1
		// ocrr_nr Cantidad de ocurrencias

		$holdingsset_id = Input::get('holdingsset_id');
		$holding = Holding::find($id)->update(['is_aux'=>'t', 'is_owner'=>'f', 'ocrr_ptrn'=> Input::get('newptrn'), 'aux_ptrn'=> Input::get('newptrn'), 'weight' => Input::get('count'), 'ocrr_nr' => Input::get('count'), 'force_aux' => true, 'force_owner' => false]);
		holdingsset_recall($holdingsset_id);
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
		DB::delete('delete from group_holdingsset where holdingsset_id = ? AND group_id = ?', array($id, Input::get('group_id')));
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
		$holding = Holding::find($id)->update(['f866aupdated'=>$new866a]);
		return Response::json( ['save866afield' => [$id]] );
	}	
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
		$params: Parameters to used in recall
						- force_owner(int: Holding id): Fix a Holdings that has to be owner of the HOS
						- force_aux(int: Holding id): Fix a Holdings thet has to be owner of the HOS
-----------------------------------------------------------------------------------*/
function holdingsset_recall($id) {

}


/* Obtain a new prtn 
-----------------------------------------------------------------------------------*/
function getNewPtrn($hol_ptrn) {
		$ta_hol_arr[0]['ptrn']= array();
		//si tiene algo se parte por el ;
		$ta_arr[0]['ptrn_arr'] = (preg_match('/\w/',$hol_ptrn))?explode(';',$hol_ptrn):array();
		if ($ta_arr[0]['ptrn_arr']){
			$ptrn_amnt	= sizeOf($ta_arr[0]['ptrn_arr']);
			for ($l=0; $l<$ptrn_amnt; $l++){
				$ptrn_piece = $ta_arr[0]['ptrn_arr'][$l]; //preservar el valor original
				//aqui se quita la j que no sirve pa comparar
				$ptrn_piece = preg_replace('/[j]/', ' ', $ptrn_piece);
				$ptrn_piece = preg_replace('/\s$/', '',$ptrn_piece);
				$ptrn_piece = preg_replace('/[n]/', '',$ptrn_piece); //<---------- parche!!!!!!!!!!!!!!!!!!!!!!!!!!!
				$ptrn_piece[16] = '-'; //esto es un parche pa poner el - que faltaba en el hol_nrm
				if (!preg_match('/\w/',$ptrn_piece)){
					//si el pedacito viene en blanco se borra
					unset($ta_arr[0]['ptrn_arr'][$l]);
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
						else array_push($ta_hol_arr[0]['ptrn'],$ptrn_chunks[$p]);
					}				
				}
			}
		}
		//aqui se escribe el ptrn	
		$ta_hol_arr[0]['ptrn']=array_unique($ta_hol_arr[0]['ptrn']);
		//aqui se ordenan los pedacitos del patron----------------------------
		$tmparr = $ta_hol_arr[0]['ptrn'];
		
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
		
		return $tmparr;
}

function createNewHos($id) {
	$holding = Holding::find($id);
	$lastId = Holdingsset::orderBy('id', 'DESC')->take(1)->get();
	$key = '';
	foreach ($lastId as $key) {
	}
	$hol_ptrn = $holding -> hol_nrm;
	$arr_ptrn = getNewPtrn($hol_ptrn);
	$newptrn = '';
	$p = 0;
	foreach ($arr_ptrn as $ptrn) {
		$p++;
		$newptrn .= $ptrn;
		if ($p < count($arr_ptrn)) $newptrn .= '|';
	}
	$newHos = new Holdingsset;
	$newHos ->	id 	= $key -> id + 1;
	$newHos ->	sys1 	= $holding -> sys2;
	$newHos ->	f245a = $holding -> f245a;
	$newHos ->	ptrn 	= $newptrn; 
	$newHos ->	holdings_number 	= 0; 
	$newHos ->	groups_number 		= 0; 
	$newHos ->	f008x 	= $holding -> f008x; 
	$newHos ->	save();
	$holding = Holding::find($id)->update(['holdingsset_id'=>$newHos -> id, 'is_owner' => 't', 'is_aux' => 'f']);
	return $newHos -> id;
}

function recall_holdings() {
	return 0;
}

function similarity_search() {
	return 0;
}