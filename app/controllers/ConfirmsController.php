<?php

class ConfirmsController extends BaseController {

	/**
	 * Confirm Repository
	 *
	 * @var Confirm
	 */
	protected $confirm;

	public function __construct(Confirm $confirm)
	{
		$this->confirm = $confirm;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$confirms = $this->confirm->all();

		return View::make('confirms.index', compact('confirms'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('confirms.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$holdingsset_id = Input::get('holdingsset_id');
		$confirm_id = Confirm::whereHoldingssetId($holdingsset_id)->lists('id');

		if ( count($confirm_id) > 0 ){
			// Confirm::whereHoldingssetId($holdingsset_id)->delete();
			// DB::table('confirms')->where('holdingsset_id', '=', $holdingsset_id)->delete();
		  	Confirm::find($confirm_id[0])->delete();
			Revised::whereIn('holding_id', Holdingsset::find($holdingsset_id)->holdings()->lists('id'))->delete();
			Holdingsset::find($holdingsset_id)->update(['state' => 'blank']);

			$ret = ['ko' => $holdingsset_id];
		} else {
			Confirm::create([ 'holdingsset_id' => $holdingsset_id, 'user_id' => Auth::user()->id ]);
			$ret = ['ok' => $holdingsset_id];
			Holdingsset::find($holdingsset_id)->update(['state' => 'ok']);
		}
		// Delete all notes from holdings HOS, if exists
		$ids = Holdingsset::find($holdingsset_id)->holdings()->select('id')->lists('id');
		// $affectedRows = Note::whereIn('holding_id', $ids)->delete();
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
		$confirm = $this->confirm->findOrFail($id);

		return View::make('confirms.show', compact('confirm'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$confirm = $this->confirm->find($id);

		if (is_null($confirm))
		{
			return Redirect::route('confirms.index');
		}

		return View::make('confirms.edit', compact('confirm'));
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
		$validation = Validator::make($input, Confirm::$rules);

		if ($validation->passes())
		{
			$confirm = $this->confirm->find($id);
			$confirm->update($input);

			return Redirect::route('confirms.show', $id);
		}

		return Redirect::route('confirms.edit', $id)
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
		$this->confirm->find($id)->delete();

		return Redirect::route('confirms.index');
	}

}
