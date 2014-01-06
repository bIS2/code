<?php

class IncorrectsController extends BaseController {

	/**
	 * Incorrect Repository
	 *
	 * @var Incorrect
	 */
	protected $incorrect;

	public function __construct(Incorrect $incorrect)
	{
		$this->incorrect = $incorrect;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$incorrects = $this->incorrect->all();

		return View::make('incorrects.index', compact('incorrects'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('incorrects.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$holdingsset_id = Input::get('holdingsset_id');
		$incorrect_id = Incorrect::whereHoldingssetId($holdingsset_id)->lists('id');
		if (count($incorrect_id) > 0) {
			Incorrect::find($incorrect_id[0])->delete();
			$ret = ['correct' => $holdingsset_id];
		} else {
			Incorrect::create([ 'holdingsset_id' => $holdingsset_id, 'user_id' => Auth::user()->id ]);
			$ret = ['incorrect' => $holdingsset_id];
		}
		$holdingssets[] = Holdingsset::find($holdingsset_id);
		$newset = View::make('holdingssets/hos', ['holdingssets' => $holdingssets]);
		return $newset;
		// return Response::json( $ret );
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$incorrect = $this->incorrect->findOrFail($id);

		return View::make('incorrects.show', compact('incorrect'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$incorrect = $this->incorrect->find($id);

		if (is_null($incorrect))
		{
			return Redirect::route('incorrects.index');
		}

		return View::make('incorrects.edit', compact('incorrect'));
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
		$validation = Validator::make($input, Incorrect::$rules);

		if ($validation->passes())
		{
			$incorrect = $this->incorrect->find($id);
			$incorrect->update($input);

			return Redirect::route('incorrects.show', $id);
		}

		return Redirect::route('incorrects.edit', $id)
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
		$this->incorrect->find($id)->delete();

		return Redirect::route('incorrects.index');
	}

}
