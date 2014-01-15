<?php

class StatesController extends BaseController {

	/**
	 * State Repository
	 *
	 * @var State
	 */
	protected $state;

	public function __construct(State $state)
	{
		$this->state = $state;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$states = $this->state->all();

		return View::make('states.index', compact('states'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('states.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$input = Input::all();
		$validation = Validator::make($input, State::$rules);

		if ($validation->passes())
		{
			$this->state->create($input);

			return Response::json( [ $input['state'] => $input['holding_id'] ]  );
		}

		return Redirect::route('states.create')
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
		$state = $this->state->findOrFail($id);

		return View::make('states.show', compact('state'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$state = $this->state->find($id);

		if (is_null($state))
		{
			return Redirect::route('states.index');
		}

		return View::make('states.edit', compact('state'));
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
		$validation = Validator::make($input, State::$rules);

		if ($validation->passes())
		{
			$state = $this->state->find($id);
			$state->update($input);

			return Redirect::route('states.show', $id);
		}

		return Redirect::route('states.edit', $id)
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
		$this->state->find($id)->delete();

		return Redirect::route('states.index');
	}

}
