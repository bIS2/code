<?php
/*
*
*	Controls workflow with Library
*
*/
class LibrariesController extends BaseController {

	/**
	 * Library Repository
	 *
	 * @var Library
	 */
	protected $library;

	public function __construct(Library $library)
	{
		$this->beforeFilter( 'auth' );
		$this->library = $library;
	}

	/**
	 * Display a listing of the Library.
	 *
	 * @return Response
	 */
	public function index()
	{
		$libraries = $this->library->all();

		return View::make('libraries.index', compact('libraries'));
	}

	/**
	 * Show the form for creating a new Library.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('libraries.create');
	}

	/**
	 * Store a newly created Library in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$input = Input::all();
		$validation = Validator::make($input, Library::$rules);

		if ($validation->passes())
		{
			$this->library->create($input);

			return Redirect::route('libraries.index');
		}

		return Redirect::route('libraries.create')
			->withInput()
			->withErrors($validation)
			->with('message', 'There were validation errors.');
	}

	/**
	 * Display the specified Library.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$library = $this->library->findOrFail($id);

		return View::make('libraries.show', compact('library'));
	}

	/**
	 * Show the form for editing the specified Library.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$library = $this->library->find($id);

		if (is_null($library))
		{
			return Redirect::route('libraries.index');
		}

		return View::make('libraries.edit', compact('library'));
	}

	/**
	 * Update the specified Library in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$input = array_except(Input::all(), '_method');
		$validation = Validator::make($input, Library::$rules);

		if ($validation->passes())
		{
			$library = $this->library->find($id);
			$library->update($input);

			return Redirect::route('libraries.show', $id);
		}

		return Redirect::route('libraries.edit', $id)
			->withInput()
			->withErrors($validation)
			->with('message', 'There were validation errors.');
	}

	/**
	 * Remove the specified Library from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$this->library->find($id)->delete();

		return Redirect::route('libraries.index');
	}

}
