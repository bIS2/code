<?php

class RevisedsController extends BaseController {

	/**
	 * Revised Repository
	 *
	 * @var Revised
	 */
	protected $revised;

	public function __construct(Revised $revised)
	{
		$this->revised = $revised;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$reviseds = $this->revised->all();

		return View::make('reviseds.index', compact('reviseds'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('reviseds.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$input = Input::all();
		$validation = Validator::make($input, Revised::$rules);

		if ($validation->passes())
		{
			$revised = $this->revised->create($input);
			return Response::json( [ 'remove_by_holdingsset' => $revised->holding->holdingsset_id ] );
		}

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$revised = $this->revised->findOrFail($id);

		return View::make('reviseds.show', compact('revised'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$revised = $this->revised->find($id);

		if (is_null($revised))
		{
			return Redirect::route('reviseds.index');
		}

		return View::make('reviseds.edit', compact('revised'));
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
		$validation = Validator::make($input, Revised::$rules);

		if ($validation->passes())
		{
			$revised = $this->revised->find($id);
			$revised->update($input);

			return Redirect::route('reviseds.show', $id);
		}

		return Redirect::route('reviseds.edit', $id)
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
		$this->revised->find($id)->delete();

		return Redirect::route('reviseds.index');
	}

}
