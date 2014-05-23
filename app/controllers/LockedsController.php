<?php
/*
*
*	Controls workflow with Holdings Locked
*
*/
class LockedsController extends BaseController {

	/**
	 * Locked Repository
	 *
	 * @var Locked
	 */
	protected $locked;

	public function __construct(Locked $locked)
	{
		$this->locked = $locked;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$lockeds = $this->locked->all();

		return View::make('lockeds.index', compact('lockeds'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('lockeds.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$holdingsset_id = Input::get('holdingsset_id');
		$holding_id = Input::get('holding_id');
		if (Auth::user()->hasRole('resuser')) {
			if ( Locked::whereHoldingId($holding_id)->exists() ) {
				if ( Locked::whereUserId(Auth::user()->id)->whereHoldingId($holding_id)->exists() ) {
					$currentstatus = Holding::find($holding_id)->state;
					$newstate = str_replace('_reserved', '', $currentstatus);
					Holding::find($holding_id)->update(['state'=>$newstate]);	
					Locked::whereHoldingId($holding_id)->delete();
					$ret = ['unlock' => $holding_id];
				}
				else {
					$ret = ['denied' => $holding_id];
				}
			}
		}
		else {
			$ret = ['denied' => $holding_id];
		}
		
		if ($ret['denied'] != $holding_id) holdingsset_recall($holdingsset_id);

		$holdingssets[] = Holdingsset::find($holdingsset_id);
		$newset = View::make('holdingssets/hos', ['holdingssets' => $holdingssets]);
		return $newset;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$locked = $this->locked->findOrFail($id);

		return View::make('lockeds.show', compact('locked'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$locked = $this->locked->find($id);

		if (is_null($locked))
		{
			return Redirect::route('lockeds.index');
		}

		return View::make('lockeds.edit', compact('locked'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id) {
		$holding_id = $id;
		$holdingsset_id = Input::get('pk');
		if (Auth::user()->hasRole('resuser')) {

			// var_dump($holding_id);

			if ( Locked::whereHoldingId($holding_id)->exists() ) {
				if ( Locked::whereUserId(Auth::user()->id)->whereHoldingId($holding_id)->exists() ) {
					$currentstatus = Holding::find($holding_id)->state;
					$newstate = str_replace('_reserved', '', $currentstatus);
					Holding::find($holding_id)->update(['state'=>$newstate]);				
					Locked::whereHoldingId($holding_id)->delete();	
					$ret = ['unlock' => $holding_id];
				}
				else {
					$ret = ['denied' => $holding_id];
				}
			} else {
				$currentstatus = Holding::find($holding_id)->state;
				$newstate = $currentstatus."_reserved";
				Holding::find($holding_id)->update(['state'=>$newstate]);
				$locked_hol = Locked::create([ 'holding_id' => $holding_id, 'user_id' => Auth::user()->id, 'comments' => Input::get('value') ]);
				$ret = ['lock' => $holding_id];
			}	
		}
		else {
			$ret = ['denied' => $holding_id];
		}

		if (($ret['denied'] != $holding_id) && ((Holding::find($holding_id)->is_onwer == 1) || (Holding::find($holding_id)->is_onwer == 't') || (Holding::find($holding_id)->is_aux == 1) || (Holding::find($holding_id)->is_aux == 't'))) holdingsset_recall($holdingsset_id);

		$holdingssets[] = Holdingsset::find($holdingsset_id);
		$newset = View::make('holdingssets/hos', ['holdingssets' => $holdingssets]);
		return $newset;
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$this->locked->find($id)->delete();

		return Redirect::route('lockeds.index');
	}

}
