<?php
/*
*
*	Controls workflow with Hos Group
*
*/

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
	 * Display a listing of the Hos Gruop.
	 *
	 * @return Response
	 */
	public function index()
	{
		$groups = Auth::user()->groups;
		return View::make('groups.index', compact('groups'));
	}

	/**
	 * Show the form for creating a new Hos Gruop.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('groups.create');
	}

	/**
	 * Store a newly created Hos Gruop in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$group = new Group([ 'name' => Input::get('name'), 'user_id' => Auth::user()->id ]);
		$validation = Validator::make($group->toArray(), Group::$rules);

		if ($validation->passes()) {
			if (Input::has('joining')) {
				if (Input::has('group_id')) {
					$group->save();
					$groups_ids = !is_array(Input::get('group_id')) ? [$groups_ids] : Input::get('group_id');
					// die(var_dump($groups_ids));
					foreach ($groups_ids as $group_id) {
						$old_group = Group::find($group_id);
						$holdingssets_ids = $old_group->holdingssets()->select('holdingssets.id')->lists('holdingssets.id');
						if (count($holdingssets_ids) > 0) {
							$group->holdingssets()->attach($holdingssets_ids);
							$old_group->holdingssets()->decrement('groups_number');
							$old_group->holdingssets()->detach($holdingssets_ids);					
						}
						$old_group->delete();
					}
					$group->holdingssets()->increment('groups_number');
				}
				return Redirect::route('groups.index');
			}
			elseif(Input::has('deleting')) {
				if (Input::has('group_id')) {
					$groups_ids = !is_array(Input::get('group_id')) ? [$groups_ids] : Input::get('group_id');
					foreach ($groups_ids as $group_id) {
						$group_to_delete = Group::find($group_id);
						$holdingssets_ids = $group_to_delete->holdingssets()->select('holdingssets.id')->lists('holdingssets.id');
						if (count($holdingssets_ids) > 0) {
							$group_to_delete->holdingssets()->decrement('groups_number');
							$group_to_delete->holdingssets()->detach($holdingssets_ids);
						}
						$group_to_delete->delete();
					}
				}
				return Redirect::route('groups.index');
			}
			else {
				$group->save();
				$group->holdingssets()->attach(Input::get('holdingsset_id'));
				$group->holdingssets()->increment('groups_number');
				return Redirect::route('sets.index', ['group_id'=>$group->id]);
			}
		}

		return Redirect::route('sets.index')
			->withInput()
			->withErrors($validation)
			->with('message', 'There were validation errors.');
	}

	/**
	 * Display the specified Hos Gruop.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		return Redirect::route('sets.index', ['group_id'=>$id]);
	}

	/**
	 * Show the form for editing the specified Hos Gruop.
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
	 * Update the specified Hos Gruop in storage.
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

			return Redirect::route('groups.index')
				->with('success', trans('groups.group_update_successfully'));;
		}

		return Redirect::route('groups.edit', $id)
			->withInput()
			->withErrors($validation)
			->with('message', 'There were validation errors.');
	}

	/**
	 * Remove the specified Hos Gruop from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$group_to_delete = $this->group->find($id);
		$holdingssets_ids = $group_to_delete->holdingssets()->select('holdingssets.id')->lists('holdingssets.id');
		$group_to_delete->holdingssets()->decrement('groups_number');
		$group_to_delete->holdingssets()->detach($holdingssets_ids);
		$group_to_delete->delete();
		return Response::json( ['remove' => [$id]] );
	}

	
	/**
	 * Attach exists Hos to Group.
	 *
	 * @param  int  $id
	 * @return Response JSON
	 */
	public function postAttach($id){
		$group->holdings()->attach($holdings_ids);		
		return Response::json( ['remove' => $holdings_ids] );
	}

	/**
	 * Detach Hos from Group.
	 *
	 * @param  int  $id
	 * @return Response JSON
	 */
	public function postDetach($id){
		$group->holdings()->detach($holdings_ids);		
		return Response::json( ['remove' => [$holdings_ids]] );
	}

}
