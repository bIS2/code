<?php

class ReservesController extends BaseController {

	/**
	 * Reserf Repository
	 *
	 * @var Reserf
	 */
	protected $reserve;

	public function __construct(Reserf $reserve)
	{
		$this->reserve = $reserve;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$reserves = $this->reserve->all();

		return View::make('reserves.index', compact('reserves'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('reserves.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$input = Input::all();
		$validation = Validator::make($input, Reserf::$rules);

		if ($validation->passes())
		{
			$this->reserve->create($input);

			return Redirect::route('reserves.index');
		}

		return Redirect::route('reserves.create')
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
		$reserve = $this->reserve->findOrFail($id);

		return View::make('reserves.show', compact('reserve'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$reserve = $this->reserve->find($id);

		if (is_null($reserve))
		{
			return Redirect::route('reserves.index');
		}

		return View::make('reserves.edit', compact('reserve'));
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
		$validation = Validator::make($input, Reserf::$rules);

		if ($validation->passes())
		{
			$reserve = $this->reserve->find($id);
			$reserve->update($input);

			return Redirect::route('reserves.show', $id);
		}

		return Redirect::route('reserves.edit', $id)
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
		$this->reserve->find($id)->delete();

		return Redirect::route('reserves.index');
	}

}
