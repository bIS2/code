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
		// $holdingssets = (Input::has('group_id')) ? Group::find(Input::get('group_id'))->holdingssets()->paginate(100) :	Holdingsset::paginate(100);

		// $this->data['holdingssets'] = $holdingssets;
		// return View::make('holdingssets.index', $this->data);
		$this->data['groups'] = Auth::user()->groups;

		$holdingssets = (Input::has('group_id')) ? 
				Group::find(Input::get('group_id'))->holdingssets()->orderBy('id', 'ASC')->paginate(20) :	
				Holdingsset::orderBy('id', 'ASC')->paginate(20);
				
		$group_id = Input::get('group_id');

		//$holdingssets = DB::table('holdingssets')->take(10)->get();
		$this->data['holdingssets'] = $holdingssets;
		$this->data['group_id'] = $group_id;

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


	public function putDelGroup($id) {
		$group = Group::find($id)->delete();
		return Response::json( ['groupDelete' => [$id]] );
	}	


}
