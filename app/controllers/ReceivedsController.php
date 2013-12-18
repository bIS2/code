<?php

class ReceivedsController extends BaseController {

	/**
	 * Received Repository
	 *
	 * @var Received
	 */
	protected $received;

	public function __construct(Received $received)
	{
		$this->received = $received;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$receiveds = $this->received->all();

		return View::make('receiveds.index', compact('receiveds'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('receiveds.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$input = Input::all();
		$validation = Validator::make($input, Received::$rules);

		if ($validation->passes())
		{
			$this->received->create($input);

			return Redirect::route('receiveds.index');
		}

		return Redirect::route('receiveds.create')
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
		$received = $this->received->findOrFail($id);

		return View::make('receiveds.show', compact('received'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$received = $this->received->find($id);

		if (is_null($received))
		{
			return Redirect::route('receiveds.index');
		}

		return View::make('receiveds.edit', compact('received'));
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
		$validation = Validator::make($input, Received::$rules);

		if ($validation->passes())
		{
			$received = $this->received->find($id);
			$received->update($input);

			return Redirect::route('receiveds.show', $id);
		}

		return Redirect::route('receiveds.edit', $id)
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
		$this->received->find($id)->delete();

		return Redirect::route('receiveds.index');
	}

}
