<?php

class HoldingsController extends BaseController {

   /**
     * Post Model
     * @var hos
     */
    public $data;

    public function __construct() {
 			$this->beforeFilter('auth_like_storeman', ['except' => ['show']]);
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function Index()
	{

		/* SHOW/HIDE FIELDS IN HOLDINGS TABLES DECLARATION
			-----------------------------------------------------------*/
			define('DEFAULTS_FIELDS', 'size;852b;852h;866a;ocrr_ptrn;245a;245b;022a;362a;866z;008x;exists_online;is_current;has_incomplete_vols');
			define('ALL_FIELDS',      'size;852b;852h;866a;ocrr_ptrn;245a;245b;022a;260a;260b;362a;710a;310a;246a;505a;770t;772t;780t;785t;852c;852j;866z;008x;exists_online;is_current;has_incomplete_vols');

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


		$this->data['allsearchablefields'] = ['size','022a','245a','245b','245c','246a','260a','260b','300a','300b','300c','310a','362a','500a','505a','710a','770t','772t','780t','785t','852b','852c','852h','852j','866a','866z', '008x', 'exists_online', 'is_current', 'has_incomplete_vols'];

		$holdings = ( Input::has('hlist_id') ) ?	Hlist::find( Input::get('hlist_id') )->holdings() : Holding::init();

    $this->data['hlists'] = Hlist::my()->get();
    $this->data['hlist'] = (Input::has('hlist_id')) ? Hlist::find(Input::get('hlist_id')) : false;

    $this->data['is_all'] = !(Input::has('corrects') || Input::has('tagged') || Input::has('pendings') || Input::has('unlist') || Input::has('owner') || Input::has('aux')|| Input::has('deliveries') );

		if ( Input::has('tagged') )			$holdings = $holdings->annotated(Input::get('tagged'));	
		if ( Input::has('pendings') )		$holdings = $holdings->pendings();
		if ( Input::has('unlist') )			$holdings = $holdings->orphans();
		if ( Input::has('owner') )			$holdings = $holdings->owner();
		if ( Input::has('aux') )			$holdings = $holdings->aux();

		if ( Input::has('receiveds'))		$holdings = $holdings->defaults()->receiveds();
		if ( Input::has('deliveries') )		$holdings = $holdings->defaults()->deliveries();
		if ( Input::has('reviseds') )		$holdings = $holdings->defaults()->reviseds();
		if ( Input::has('commenteds') )		$holdings = $holdings->defaults()->commenteds();

		if ( Input::has('trasheds') )		$holdings = Holding::defaults()->withState('trash');
		if ( Input::has('burneds') )		$holdings = Holding::defaults()->withState('burn');

		// $holdings = ( Input::has('reviseds') || (Auth::user()->hasRole('postuser'))) ? $holdings->reviseds()->corrects() : $holdings->noreviseds();

		// Apply filter.
		$is_filter = false;

		foreach ($this->data['allsearchablefields'] as $field) {

			$field = (!(($field == 'exists_online') || ($field == 'is_current') || ($field == 'has_incomplete_vols') || ($field == 'size'))) ? 'f'.$field : $field;

			if ( Input::has($field) )  {

				$is_filter 	= true;
				$orand 			= Input::get('OrAndFilter')[$openfilter-1];
				$format 		= Input::get( $field.'format' );
				$compare = (($field != 'exists_online') && ($field != 'is_current') && ($field != 'has_incomplete_vols') && ($field != 'size') && ($field != '008x') ) ? 'LOWER('.$field.')' : $field;

				$holdings = ($orand == 'OR') ? 	
					$holdings->OrWhereRaw( sprintf( $format, $compare, pg_escape_string(addslashes(strtolower( Input::get($field) ) ) )) ) :  
				  $holdings->WhereRaw( sprintf( $format, $compare, pg_escape_string(addslashes(strtolower( Input::get($field) ) ) ) ) );  

			}

		}

		$this->data['is_filter'] 	= $is_filter;
		$this->data['sql'] 				= sprintf( $format, $compare, $value );
		$this->data['holdings'] 	= $holdings->paginate(25);
		$queries = DB::getQueryLog();
		$this->data['last_query'] = $queries;			

		// CONDITIONS
		// filter by holdingsset ok
		//  and holdings in their library
		$view = (Input::has('view')) ? Input::get('view') : 'index';
		// var_dump($this->data);die();
		return View::make('holdings/'.$view, $this->data);

	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('holdings/index', array('posts' => $holdings));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
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
			// Session::put($uUserName.'_sortinghos_by', Input::get('sortinghos_by'));
			// Session::put($uUserName.'_sortinghos', Input::get('sortinghos'));
			return Redirect::to(Input::get('urltoredirect'));
		}
		else {	
			return View::make('holdings/index', array('posts' => $holdings));
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
		$this->data['holding'] = Holding::find($id);
		return View::make('holdings/show', $this->data);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$holding = Holding::find($id);
		$holding->size=Input::get('value');
		$holding->save();
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */

	// Custom method

	public function postOK($id){
		$holding = Holding::find($id);
		$ok = Ok::whereHoldingId($id);

		if ( $ok->count()>0 ){
			$ok->delete();
			$return = [ 'ko'=>$id  ];
		}
		else {
			$ok = new Ok( ['user_id' =>Auth::user()->id ] );
			$holding->ok()->save($ok);
			$return = [ 'ok'=>$id ];
		}

		return  Response::json( $return ) ;
	}

/* ---------------------------------------------------------------------------------
	Del Hlist Tab from HOLS View
	--------------------------------------
	Params:
		$id: HOLS HList id 
-----------------------------------------------------------------------------------*/
	public function putDelTabhlist($id) {
		$uUserName = Auth::user()->username;
		$groupsids = Session::get($uUserName.'_hlists_to_show');
		$newgroupsids = str_replace($id, '', $groupsids);
		$newgroupsids = str_replace(';;', ';', $newgroupsids);
	 	Session::put($uUserName.'_hlists_to_show', $newgroupsids);
		// $group = Group::find($id)->delete();
		return Response::json( ['hlistDelete' => [$id]] );
	}	

}