<?php

class HoldingssetsController extends BaseController {
 	protected $layout = 'layouts.default';


    public function __construct() {
    	$this->data['groups'] = Auth::user()->groups;
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

		$holdingssets = (Input::has('group_id')) ? 
				Group::find(Input::get('group_id'))->holdingssets()->orderBy('id', 'ASC')->paginate(20) :	
				Holdingsset::orderBy('id', 'ASC')->paginate(20);

		//$holdingssets = DB::table('holdingssets')->take(10)->get();
		$this->data['holdingssets'] = $holdingssets;

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
		return Response::json( ['ok' => ['id'=>$id,'class'=>'btn-danger']] );
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
	public function putOK($id) {

		if (Holdingsset::find($id)->update(['ok'=>true]))
			return Response::json( ['remove' => [$id]] );
		//
	}	

}
