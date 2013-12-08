<?php

class DeliveriesController extends BaseController {

	/**
	 * Delivery Repository
	 *
	 * @var Delivery
	 */
	protected $delivery;

	public function __construct(Delivery $delivery)
	{
		$this->delivery = $delivery;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$deliveries = $this->delivery->all();

		return View::make('deliveries.index', compact('deliveries'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('deliveries.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$input = Input::all();
		$validation = Validator::make($input, Delivery::$rules);

		if ($validation->passes()){
			$delivery = $this->delivery->create($input);
			return Response::json( [ 'remove' => $delivery->holding_id ] );
		}

		return Redirect::route('deliveries.create')
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
		$delivery = $this->delivery->findOrFail($id);

		return View::make('deliveries.show', compact('delivery'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$delivery = $this->delivery->find($id);

		if (is_null($delivery))
		{
			return Redirect::route('deliveries.index');
		}

		return View::make('deliveries.edit', compact('delivery'));
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
		$validation = Validator::make($input, Delivery::$rules);

		if ($validation->passes())
		{
			$delivery = $this->delivery->find($id);
			$delivery->update($input);

			return Redirect::route('deliveries.show', $id);
		}

		return Redirect::route('deliveries.edit', $id)
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
		$this->delivery->find($id)->delete();

		return Redirect::route('deliveries.index');
	}

}
