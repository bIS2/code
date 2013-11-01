<?php

class HoldingssetsController extends BaseController {
 	protected $layout = 'layouts.default';


    public function __construct() {
    	$this->datos['groups'] = Auth::user()->groups;
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

		$holdingssets = (Input::has('group_id')) ? Group::find(Input::get('group_id'))->holdingssets()->orderBy('id', 'ASC')->paginate(20) :	Holdingsset::orderBy('id', 'ASC')->paginate(20);
		$this->datos['holdingssets'] = $holdingssets;
		// var_dump($this->datos);
		if (isset($_GET['page']))  {
				$this->datos['page'] = $_GET['page'];
				return View::make('holdingssets.pages', ['holdingssets' => $holdingssets, 'page' => $page, 'groups' => Auth::user()->groups ]/*$this->datos*/);
			}
			 else  { 
			 	$this->datos['page'] = 1;
			 	return View::make('holdingssets.index',['holdingssets' => $holdingssets, 'page' => 1, 'groups' => Auth::user()->groups ] /*$this->datos*/);
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
