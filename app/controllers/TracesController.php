<?php

class TracesController extends BaseController {

	/**
	 * Trace Repository
	 *
	 * @var Trace
	 */
	protected $trace;

	public function __construct(Trace $trace)
	{
		$this->trace = $trace;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$traces = $this->trace->all();

		return View::make('traces.index', compact('traces'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('traces.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$input = Input::all();
		$validation = Validator::make($input, Trace::$rules);

		if ($validation->passes())
		{
			$this->trace->create($input);

			return Redirect::route('traces.index');
		}

		return Redirect::route('traces.create')
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
		$trace = $this->trace->findOrFail($id);

		return View::make('traces.show', compact('trace'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$trace = $this->trace->find($id);

		if (is_null($trace))
		{
			return Redirect::route('traces.index');
		}

		return View::make('traces.edit', compact('trace'));
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
		$validation = Validator::make($input, Trace::$rules);

		if ($validation->passes())
		{
			$trace = $this->trace->find($id);
			$trace->update($input);

			return Redirect::route('traces.show', $id);
		}

		return Redirect::route('traces.edit', $id)
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
		$this->trace->find($id)->delete();

		return Redirect::route('traces.index');
	}

}
