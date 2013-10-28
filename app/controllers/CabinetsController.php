<?php

class CabinetsController extends BaseController {

	/**
	 * Cabinet Repository
	 *
	 * @var Cabinet
	 */
	protected $cabinet;

	public function __construct(Cabinet $cabinet)
	{
		$this->cabinet = $cabinet;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$cabinets = $this->cabinet->all();

		return View::make('cabinets.index', compact('cabinets'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{

		return View::make('cabinets.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$this->cabinet->name = Input::get('name');
		$this->cabinet->user_id = Auth::user()->id;

		$validation = Validator::make($this->cabinet->toArray(), Cabinet::$rules);

		if ($validation->passes()) {
			$this->cabinet->save();
			$this->cabinet->holdings()->attach(Input::get('holding_id'));
			return Redirect::route('cabinets.index');
		}

		return Redirect::route('cabinets.create')
			->withInput()
			->withErrors($validation)
			->with('message', 'There were validation errors.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$cabinet = $this->cabinet->findOrFail($id);

		return View::make('cabinets.show', compact('cabinet'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$cabinet = $this->cabinet->find($id);

		if (is_null($cabinet))
		{
			return Redirect::route('cabinets.index');
		}

		return View::make('cabinets.edit', compact('cabinet'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, $action='update') {

/*		$input = array_except(Input::all(), '_method');
		$validation = Validator::make($input, Cabinet::$rules);
*/
		$cabinet = Cabinet::find($id);

		$holdings_ids = Input::get('holding_id');

		if (Input::get("attach")==true) 
			$cabinet->holdings()->attach($holdings_ids);		
		else
			$cabinet->holdings()->detach($holdings_ids);		

		return Response::json( ['remove' => $holdings_ids] );

/*
		if ($validation->passes())
		{
			$cabinet = $this->cabinet->find($id);
			$cabinet->update($input);

			return Redirect::route('cabinets.show', $id);
		}

		return Redirect::route('cabinets.edit', $id)
			->withInput()
			->withErrors($validation)
			->with('message', 'There were validation errors.');
*/	
		}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$this->cabinet->find($id)->delete();

		return Redirect::route('cabinets.index');
	}

	public function postAttach($id){
		$cabinet->holdings()->attach($holdings_ids);		
		return Response::json( ['remove' => $holdings_ids] );
	}

	public function postDetach($id){
		$cabinet->holdings()->detach($holdings_ids);		
		return Response::json( ['remove' => $holdings_ids] );
	}


}
