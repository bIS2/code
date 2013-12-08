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
		/* SEARCH ADVANCED FIELDS OPTIONS
		----------------------------------------------------------------*/
		define('ALL_SEARCHEABLESFIELDS', '022a;245a;245b;008x;245c;310a;362a;710a;780t;785t;852b;852h;008y');

		// Is Filter
		$allsearchablefields = ALL_SEARCHEABLESFIELDS;
		$allsearchablefields = explode(';', $allsearchablefields);
		$is_filter = false;
		foreach ($allsearchablefields as $field) {
			$value = Input::get('f'.$field);
			if ($value != '') {
				$is_filter = true;
			}
		}
		$this->data['is_filter'] = $is_filter;
		
		/* SHOW/HIDE FIELDS IN HOLDINGS TABLES DECLARATION
		-----------------------------------------------------------*/
		define('DEFAULTS_FIELDS', '245a;245b;008x;ocrr_ptrn;sys2;260a;260b;710a;310a');
		define('ALL_FIELDS', '022a;245a;245b;008x;ocrr_ptrn;sys2;245c;310a;362a;710a;780t;785t;852b;852h;008y');

		if (!isset($_COOKIE[Auth::user()->username.'_fields_to_show'])) {
			if (Session::get(Auth::user()->username.'_fields_to_show') == 'ocrr_ptrn;sys2') {
			  setcookie(Auth::user()->username.'_fields_to_show', DEFAULTS_FIELDS, time() + (86400 * 30));
			  Session::put(Auth::user()->username.'_fields_to_show', DEFAULTS_FIELDS);
			}
			else {
				setcookie(Auth::user()->username.'_fields_to_show', Session::get(Auth::user()->username.'_fields_to_show'), time() + (86400 * 30));
			}
		}

		if ((Session::get(Auth::user()->username.'_fields_to_show') == 'ocrr_ptrn;sys2') || (Session::get(Auth::user()->username.'_fields_to_show') == '')) {
		  setcookie(Auth::user()->username.'_fields_to_show', DEFAULTS_FIELDS, time() + (86400 * 30));
		  Session::put(Auth::user()->username.'_fields_to_show', DEFAULTS_FIELDS);
		}

		// Groups
		$this->data['groups'] = Auth::user()->groups;
		$group_id = Input::get('group_id');
		$this->data['group_id'] = $group_id;

		$holdingssets = (Input::has('group_id')) ? 
		Group::find(Input::get('group_id'))->holdingssets()->orderBy('id', 'ASC') :	
		Holdingsset::orderBy('id', 'ASC');
				

		$state = Input::get('state');

		if (Input::has('group_id')) {
			if (isset($state)) {
				$holdingssets = ($state == 'ok') ? 
				$holdingssets = Group::find(Input::get('group_id'))->holdingssets()->ok()->orderBy('id', 'ASC') :
				$holdingssets = Group::find(Input::get('group_id'))->holdingssets()->pendings()->orderBy('id', 'ASC');
			}
			else {				
				$holdingssets = Group::find(Input::get('group_id'))->holdingssets()->orderBy('id', 'ASC');
			}
		}
		else {	
			if (isset($state)) {
				$holdingssets = ($state == 'ok') ? 
				$holdingssets =	Holdingsset::orderBy('id', 'ASC')->ok() :
				$holdingssets =	Holdingsset::orderBy('id', 'ASC')->pendings();
			}
			else {				
				$holdingssets =	Holdingsset::orderBy('id', 'ASC');	
			}
		}

		if ($this->data['is_filter']) {

			$holdings= DB::table('holdings');
			$openfilter = 0;

			foreach ($allsearchablefields as $field) {
				$value = Input::get('f'.$field);
				if ($value != '') {
					if ( Input::has('f'.$field) )  { $holdings = $holdings->whereRaw( sprintf( Input::get('f'.$field.'format'), 'LOWER('.'f'.$field.')', strtolower( Input::get('f'.$field) ) ) );  $openfilter++; }
				}
			}
			if (( Input::has('owner')) && (!(Input::has('aux')))) $holdings = $holdings->whereIsOwner('t')->where('sys2','like', Auth::user()->library()->first()->code."%");
			if (( Input::has('aux')) && (!(Input::has('owner')))) $holdings = $holdings->whereIsAux('t')->where('sys2','like', Auth::user()->library()->first()->code."%");
			if (( Input::has('owner')) && (Input::has('aux'))) $holdings = $holdings->whereIsAux('t')->orWhere('is_owner','=', 't')->where('sys2','like', Auth::user()->library()->first()->code."%");

			if ($openfilter == 0)  $this->data['is_filter'] = false;

		  $ids = $holdings->count() > 0 ? $holdings->lists('holdingsset_id') : [-1];

		  $holdingssets = $holdingssets->whereIn('id', $ids);
		}

		$this->data['holdingssets'] = $holdingssets->paginate(20);

		if (isset($_GET['page']))  {
				$this->data['page'] = $_GET['page'];
				return View::make('holdingssets/hos', $this->data);
			}
			 else  { 
			 	$this->data['page'] = 1;
			 	return View::make('holdingssets/index', $this->data);
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
			setcookie(Auth::user()->username.'_fields_to_show', $fieldlist, time() + (86400 * 30));
			Session::put(Auth::user()->username.'_fields_to_show', $fieldlist);
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
  	return View::make('holdingssets.show');
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
		$groupsids = Session::get(Auth::user()->username.'_groups_to_show');
		$newgroupsids = str_replace($id, '', $groupsids);
		$newgroupsids = str_replace(';;', ';', $newgroupsids);
	 	Session::put(Auth::user()->username.'_groups_to_show', $newgroupsids);
		// $group = Group::find($id)->delete();
		return Response::json( ['groupDelete' => [$id]] );
	}	

/* ---------------------------------------------------------------------------------
	Lock a determinate Holding
	--------------------------------------
	Params:
		$id: Holding id 
-----------------------------------------------------------------------------------*/
	public function putLock($id) {
		$holding = Holding::find($id);
		$value = ( $holding->locked ) ? false : true;

		if ($holding->update(['locked'=>$value]))
			return ($value) ? Response::json( ['lock' => [$id]] ) : Response::json( ['unlock' => [$id]] );
	}	


/* ---------------------------------------------------------------------------------
	Get Holding Data from Original System
	--------------------------------------
	Params:
		$id: Holding id
-----------------------------------------------------------------------------------*/
	public function getFromLibrary($id) {
		$this->data['holding'] = Holding::find($id)->sys2;
		$this->data['library'] = Library::orderBy('code', 'ASC')->libraryperholding(substr($this->data['holding'], 0, 4));
		$this->data['holding'] = substr($this->data['holding'], 4, 9);
		return View::make('holdingssets.externalholding', $this -> data);
	}

/* ---------------------------------------------------------------------------------
	Create a new HOS from only from a Holding
	------------------------------------------
	Params:
		$id: Holding id
-----------------------------------------------------------------------------------*/
	public function putNewHOS($id) {
		$holding 	= Holding::find($id);

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
		$newHos ->	ok 		= false;
		$newHos ->	save();
		$holding = Holding::find($id)->update(['holdingsset_id'=>$newHos -> id]);
		return Response::json( ['newhosok' => [$id]] );
	}	

	public function putForceOwner($id) {
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
	Update the 866a from a holding
	--------------------------------------
	Params:
		$id: Holding id 
-----------------------------------------------------------------------------------*/
	public function putUpdateField866aHolding($id) {
		$new866a = Input::get('new866a');
		$holding = Holding::find($id)->update(['f866a'=>$new866a]);
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