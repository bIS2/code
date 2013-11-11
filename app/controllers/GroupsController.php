<?php

class GroupsController extends BaseController {

	/**
	 * Group Repository
	 *
	 * @var Group
	 */
	protected $group;

	public function __construct(Group $group)
	{
		$this->group = $group;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$groups = $this->group->all();
		return View::make('groups.index', compact('groups'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('groups.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$group = new Group([ 'name' => Input::get('name'), 'user_id' => Auth::user()->id ]);
		$validation = Validator::make($group->toArray(), Group::$rules);

		if ($validation->passes()) {
			$group->save();
			$group->holdingssets()->attach(Input::get('holdingsset_id'));
			return Redirect::route('sets.index', ['group_id'=>$group->id]);
		}

		return Redirect::route('groups.create')
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
		return Redirect::route('sets.index', ['group_id'=>$id]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$group = $this->group->find($id);

		if (is_null($group))
		{
			return Redirect::route('groups.index');
		}

		return View::make('groups.edit', compact('group'));
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
		$validation = Validator::make($input, Group::$rules);

		if ($validation->passes())
		{
			$group = $this->group->find($id);
			$group->update($input);

			return Redirect::route('groups.show', $id);
		}

		return Redirect::route('groups.edit', $id)
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
		$this->group->find($id)->delete();
		return Redirect::route('groups.index');
	}

	public function postAttach($id){
		$group->holdings()->attach($holdings_ids);		
		return Response::json( ['remove' => $holdings_ids] );
	}

	public function postDetach($id){
		$group->holdings()->detach($holdings_ids);		
		return Response::json( ['remove' => [$holdings_ids]] );
	}

}
