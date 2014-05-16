<?php

/*
*
*	Controls the workflow lists Holding when delivered.
*
*/

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
	 * Display a listing of the Delivered List of Holding.
	 *
	 * @return Response
	 */
	public function index()
	{
		$deliveries = $this->delivery->all();

		return View::make('deliveries.index', compact('deliveries'));
	}

	/**
	 * Show the form for creating a new Delivered List of Holding.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('deliveries.create');
	}

	/**
	 * Store a newly created Delivered List of Holding in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$input = Input::all();
		$validation = Validator::make($input, Delivery::$rules);

		if ($validation->passes()){
			$delivery = $this->delivery->create($input);
			return Response::json( [ 
				'delivered' => $delivery->hlist_id,
				'state' => trans('states.delivery')
				] );
		}

/*		return Redirect::route('deliveries.create')
			->withInput()
			->withErrors($validation)
			->with('message', 'There were validation errors.');*/
	}

	/**
	 * Display the specified Delivered List of Holding.
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
	 * Show the form for editing the specified Delivered List of Holding.
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
	 * Update the specified Delivered List of Holding in storage.
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
	 * Remove the specified Delivered List of Holding from storage.
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
