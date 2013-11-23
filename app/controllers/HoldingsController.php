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
		$holdings = ( Input::has('hlist_id') ) ?	Hlist::find( Input::get('hlist_id') )->holdings() : Holding::verified(); ;

    $this->data['hlists'] = Auth::user()->hlists;
    $this->data['hlist'] = (Input::has('hlist_id')) ? Hlist::find(Input::get('hlist_id')) : false;

    $this->data['is_all'] = !(Input::has('corrects') || Input::has('tagged') || Input::has('pendings') || Input::has('unlist') || Input::has('owner') || Input::has('aux') );

		if ( Input::has('corrects') ) 	$holdings = $holdings->corrects();
		if ( Input::has('tagged') )			$holdings = $holdings->annotated(Input::get('tagged'));	
		if ( Input::has('pendings') )		$holdings = $holdings->pendings();
		if ( Input::has('unlist') )			$holdings = $holdings->orphans();
		if ( Input::has('owner') )			$holdings = $holdings->owner();
		if ( Input::has('aux') )				$holdings = $holdings->aux();

		if ( Input::has('f852b') )  $holdings = $holdings->whereRaw( sprintf( Input::get('f852bformat'), 'LOWER(f852b)', strtolower( Input::get('f852b') ) ) );
		if ( Input::has('f852h') ) 	$holdings = $holdings->whereRaw( sprintf( Input::get('f852hformat'), 'LOWER(f852h)', strtolower( Input::get('f852h') ) ) );
		if ( Input::has('f245a') )  $holdings = $holdings->whereRaw( sprintf( Input::get('f245aformat'), 'LOWER(f245a)', strtolower( Input::get('f245a') ) ) );
		if ( Input::has('f362a') ) 	$holdings = $holdings->whereRaw( sprintf( Input::get('f362aformat'), 'LOWER(f362a)', strtolower( Input::get('f362a') ) ) );
		if ( Input::has('f866a') ) 	$holdings = $holdings->whereRaw( sprintf( Input::get('f866aformat'), 'LOWER(f866a)', strtolower( Input::get('f866a') ) ) );
		if ( Input::has('f866z') ) 	$holdings = $holdings->whereRaw( sprintf( Input::get('f866zformat'), 'LOWER(f866z)', strtolower( Input::get('f866z') ) ) );

		$this->data['is_filter'] = Input::has('f852b') || Input::has('f852h') || Input::has('f245a') || Input::has('f362a') || Input::has('f866a') || Input::has('f866z');
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
		extract($_POST[]);
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