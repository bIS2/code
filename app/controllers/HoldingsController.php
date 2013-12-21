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
		$this->data['allsearchablefields'] = ['Size','852b','852h','866a','245a','866z'];

		$holdings = ( Input::has('hlist_id') ) ?	Hlist::find( Input::get('hlist_id') )->holdings() : Holding::init();

    $this->data['hlists'] = Hlist::my()->get();
    $this->data['hlist'] = (Input::has('hlist_id')) ? Hlist::find(Input::get('hlist_id')) : false;

    $this->data['is_all'] = !(Input::has('corrects') || Input::has('tagged') || Input::has('pendings') || Input::has('unlist') || Input::has('owner') || Input::has('aux') );

		if ( Input::has('corrects') ) 	$holdings = $holdings->corrects();
		if ( Input::has('tagged') )			$holdings = $holdings->annotated(Input::get('tagged'));	
		if ( Input::has('pendings') )		$holdings = $holdings->pendings();
		if ( Input::has('unlist') )			$holdings = $holdings->orphans();
		if ( Input::has('owner') )			$holdings = $holdings->owner();
		if ( Input::has('aux') )				$holdings = $holdings->aux();


		// Apply filter.
		foreach ($this->data['allsearchablefields'] as $field) {

			if ( Input::has('f'.$field) ) {

				$orand = Input::has('OrAndFilter'.$field) ? Input::get('OrAndFilter'.$field) : 'and';

				$holdings = ($orand == 'OR') ? 
						$holdings->OrWhereRaw( sprintf( Input::get('f'.$field.'format'), 'LOWER('.'f'.$field.')', pg_escape_string(addslashes(strtolower( Input::get('f'.$field) ) ) )) ) :  
						$holdings->WhereRaw( sprintf( Input::get('f'.$field.'format'), 'LOWER('.'f'.$field.')', pg_escape_string(addslashes(strtolower( Input::get('f'.$field) ) ) ) ) );  
			}
		}

		$this->data['is_filter'] = Input::has('Size') || Input::has('f852b') || Input::has('f852h') || Input::has('f866a') || Input::has('f245a') || Input::has('f866z') ;
		$this->data['holdings'] = $holdings->paginate(25);

		// CONDITIONS
		// filter by holdingsset ok
		//  and holdings in their library
		$view = (Input::has('view')) ? Input::get('view') : 'index';
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
		return View::make('holdings/index', array('posts' => $holdings));
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

}