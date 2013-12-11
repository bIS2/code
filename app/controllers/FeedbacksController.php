<?php

class FeedbacksController extends BaseController {

	/**
	 * Feedback Repository
	 *
	 * @var Feedback
	 */
	protected $feedback;

	public function __construct(Feedback $feedback)
	{
		$this->feedback = $feedback;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$feedbacks = $this->feedback->all();

		return View::make('feedbacks.index', compact('feedbacks'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('feedbacks.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$input = Input::all();
		$validation = Validator::make($input, Feedback::$rules);

		if ($validation->passes())
		{
			$this->feedback->create($input);

			return Redirect::route('feedbacks.index');
		}

		return Redirect::route('feedbacks.create')
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
		$feedback = $this->feedback->findOrFail($id);

		return View::make('feedbacks.show', compact('feedback'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$feedback = $this->feedback->find($id);

		if (is_null($feedback))
		{
			return Redirect::route('feedbacks.index');
		}

		return View::make('feedbacks.edit', compact('feedback'));
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
		$validation = Validator::make($input, Feedback::$rules);

		if ($validation->passes())
		{
			$feedback = $this->feedback->find($id);
			$feedback->update($input);

			return Redirect::route('feedbacks.show', $id);
		}

		return Redirect::route('feedbacks.edit', $id)
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
		$this->feedback->find($id)->delete();

		return Redirect::route('feedbacks.index');
	}

}
