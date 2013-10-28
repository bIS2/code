<?php

class Comments_categoriesController extends BaseController {

	/**
	 * Comments_category Repository
	 *
	 * @var Comments_category
	 */
	protected $comments_category;

	public function __construct(Comments_category $comments_category)
	{
		$this->comments_category = $comments_category;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$comments_categories = $this->comments_category->all();

		return View::make('comments_categories.index', compact('comments_categories'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('comments_categories.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$input = Input::all();
		$validation = Validator::make($input, Comments_category::$rules);

		if ($validation->passes())
		{
			$this->comments_category->create($input);

			return Redirect::route('comments_categories.index');
		}

		return Redirect::route('comments_categories.create')
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
		$comments_category = $this->comments_category->findOrFail($id);

		return View::make('comments_categories.show', compact('comments_category'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$comments_category = $this->comments_category->find($id);

		if (is_null($comments_category))
		{
			return Redirect::route('comments_categories.index');
		}

		return View::make('comments_categories.edit', compact('comments_category'));
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
		$validation = Validator::make($input, Comments_category::$rules);

		if ($validation->passes())
		{
			$comments_category = $this->comments_category->find($id);
			$comments_category->update($input);

			return Redirect::route('comments_categories.show', $id);
		}

		return Redirect::route('comments_categories.edit', $id)
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
		$this->comments_category->find($id)->delete();

		return Redirect::route('comments_categories.index');
	}

}
