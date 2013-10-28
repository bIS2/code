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
		$holdingssets = (Input::has('group_id')) ? Group::find(Input::get('group_id'))->holdingssets()->paginate(100) :	Holdingsset::paginate(100);

		$this->data['holdingssets'] = $holdingssets;
		return View::make('holdingssets.index', $this->data);
		
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

}
