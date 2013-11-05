<?php

class HlistsController extends BaseController {

	/**
	 * Hlist Repository
	 *
	 * @var Hlist
	 */
	protected $hlist;

	public function __construct(Hlist $hlist)
	{
		$this->hlist = $hlist;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$hlists = $this->hlist->all();
		return View::make('hlists.index', compact('hlists'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('hlists.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$hlist = new Hlist([ 'name' => Input::get('name'), 'user_id' => Auth::user()->id ]);
		$validation = Validator::make($hlist->toArray(), Hlist::$rules);

		if ($validation->passes()) {
			$hlist->save();
			$hlist->holdings()->attach(Input::get('holdings_id'));
			return Redirect::route('holdings.index', ['list_id'=>$hlist->id]);
		}

		return Redirect::route('hlists.create')
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
		// $hlist = $this->hlist->findOrFail($id);
		// return View::make('hlists.show', compact('hlist'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$hlist = $this->hlist->find($id);

		if (is_null($hlist))
		{
			return Redirect::route('hlists.index');
		}

		return View::make('hlists.edit', compact('hlist'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$input = array_except(Input::all(), '_method');
		$validation = Validator::make($input, Hlist::$rules);

		if ($validation->passes())
		{
			$hlist = $this->hlist->find($id);
			$hlist->update($input);

			return Redirect::route('hlists.show', $id);
		}

		return Redirect::route('hlists.edit', $id)
			->withInput()
			->withErrors($validation)
			->with('message', 'There were validation errors.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$this->hlist->find($id)->delete();
		return Redirect::route('hlists.index');
	}

	public function postAttach($id){
		$hlist->holdings()->attach($holdings_ids);		
		return Response::json( ['remove' => $holdings_ids] );
	}

	public function postDetach($id){
		$hlist->holdings()->detach($holdings_ids);		
		return Response::json( ['remove' => [$holdings_ids]] );
	}

}
