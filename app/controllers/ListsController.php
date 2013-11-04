<?php

class ListsController extends BaseController {

	/**
	 * List Repository
	 *
	 * @var List
	 */
	protected $list;

	public function __construct(List $list)
	{
		$this->list = $list;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$lists = $this->list->all();

		return View::make('lists.index', compact('lists'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('lists.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$input = Input::all();
		$validation = Validator::make($input, List::$rules);

		if ($validation->passes())
		{
			$this->list->create($input);

			return Redirect::route('lists.index');
		}

		return Redirect::route('lists.create')
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
		$list = $this->list->findOrFail($id);

		return View::make('lists.show', compact('list'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$list = $this->list->find($id);

		if (is_null($list))
		{
			return Redirect::route('lists.index');
		}

		return View::make('lists.edit', compact('list'));
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
		$validation = Validator::make($input, List::$rules);

		if ($validation->passes())
		{
			$list = $this->list->find($id);
			$list->update($input);

			return Redirect::route('lists.show', $id);
		}

		return Redirect::route('lists.edit', $id)
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
		$this->list->find($id)->delete();

		return Redirect::route('lists.index');
	}

}
