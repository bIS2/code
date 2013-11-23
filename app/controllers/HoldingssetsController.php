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
		// Is Filter
		$this->data['is_filter'] = Input::has('owner') || Input::has('aux') ||  Input::has('f852b') || Input::has('f852h') || Input::has('f245a') || Input::has('f362a') || Input::has('f866a') || Input::has('f866z');
		
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

			if ( Input::has('f852b') )  $holdings = $holdings->whereRaw( sprintf( Input::get('f852bformat'), 'LOWER(f852b)', strtolower( Input::get('f852b') ) ) );
			if ( Input::has('f852h') ) 	$holdings = $holdings->whereRaw( sprintf( Input::get('f852hformat'), 'LOWER(f852h)', strtolower( Input::get('f852h') ) ) );
			if ( Input::has('f245a') )  $holdings = $holdings->whereRaw( sprintf( Input::get('f245aformat'), 'LOWER(f245a)', strtolower( Input::get('f245a') ) ) );
			if ( Input::has('f362a') ) 	$holdings = $holdings->whereRaw( sprintf( Input::get('f362aformat'), 'LOWER(f362a)', strtolower( Input::get('f362a') ) ) );
			if ( Input::has('f866a') ) 	$holdings = $holdings->whereRaw( sprintf( Input::get('f866aformat'), 'LOWER(f866a)', strtolower( Input::get('f866a') ) ) );
			if ( Input::has('f866z') ) 	$holdings = $holdings->whereRaw( sprintf( Input::get('f866zformat'), 'LOWER(f866z)', strtolower( Input::get('f866z') ) ) );
			
			if (( Input::has('owner')) && (!(Input::has('aux')))) $holdings = $holdings->whereIsOwner('t')->where('sys2','like', Auth::user()->library()->first()->code."%");
			if (( Input::has('aux')) && (!(Input::has('owner')))) $holdings = $holdings->whereIsAux('t')->where('sys2','like', Auth::user()->library()->first()->code."%");
			
			if (( Input::has('owner')) && (Input::has('aux'))) $holdings = $holdings->whereIsAux('t')->orWhere('is_owner','=', 't')->where('sys2','like', Auth::user()->library()->first()->code."%");
		    



		  $ids = $holdings->count() > 0 ? $holdings->lists('holdingsset_id') : [-1];
		  $holdingssets = $holdingssets->whereIn('id', $ids);
		}



		$this->data['holdingssets'] = $holdingssets->paginate(20);

		if (isset($_GET['page']))  {
				$this->data['page'] = $_GET['page'];
				return View::make('holdingssets/pages', $this->data);
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
		//
	}

	// Set/Unset Ok to HOS
	public function putOk($id) {
		$holdingsset = Holdingsset::find($id);
		$value = ( $holdingsset->ok ) ? false : true;

		if ($holdingsset->update(['ok'=>$value]))
			return ($value) ? Response::json( ['ok' => [$id]] ) : Response::json( ['ko' => [$id]] );
		//
	}	

	// Lock/Unlock Holding
	public function putLock($id) {
		$holding = Holding::find($id);
		$value = ( $holding->locked ) ? false : true;

		if ($holding->update(['locked'=>$value]))
			return ($value) ? Response::json( ['lock' => [$id]] ) : Response::json( ['unlock' => [$id]] );
	}	

	// Lock/Unlock Holding
	public function putNewHOS($id) {
		return Response::json( ['newhosok' => [$id]] );
	}	


	public function putDelGroup($id) {
		$group = Group::find($id)->delete();
		return Response::json( ['groupDelete' => [$id]] );
	}	

	// Set/Unset Ok to HOS
	public function getFromLibrary($id) {
		$this->data['holding'] = Holding::find($id)->sys2;
		$this->data['library'] = Library::orderBy('code', 'ASC')->libraryperholding(substr($this->data['holding'], 0, 4));
		$this->data['holding'] = substr($this->data['holding'], 4, 9);
		return View::make('holdingssets.externalholding', $this -> data);
	}	
}
