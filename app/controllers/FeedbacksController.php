<?php
/*
*
*	Controls workflow with Feedbacks
*
*/
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
	 * Display a listing of the feedback.
	 *
	 * @return Response
	 */
	public function index()
	{
		if (Input::has('q')) 
			$this->feedback = $this->feedback->where('content','like', '%'.Input::get('q').'%');
				
		$feedbacks = $this->feedback->paginate(25);

		return View::make('feedbacks.index', compact('feedbacks'));
	}

	/**
	 * Show the form for creating a new feedback.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('feedbacks.create');
	}

	/**
	 * Store a newly created feedback in storage.
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

			return Response::json([ 'hide_feedback' => true ]);
		}

		// return Redirect::route('feedbacks.create')
		// 	->withInput()
		// 	->withErrors($validation)
		// 	->with('message', 'There were validation errors.');
	}

	/**
	 * Display the specified feedback.
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
	 * Show the form for editing the specified feedback.
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
	 * Update the specified feedback in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
/*		$input = array_except(Input::all(), '_method');
		$validation = Validator::make($input, Feedback::$rules);
*/
			$feedback = $this->feedback->find($id);
			$feedback->update( ['content' => Input::get('value') ]);

/*			return Redirect::json('admin.feedbacks.show', $id);

		return Redirect::route('admin.feedbacks.edit', $id)
			->withInput()
			->withErrors($validation)
			->with('message', 'There were validation errors.');
*/	}

	/**
	 * Remove the specified feedback from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$this->feedback->find($id)->delete();
		return  Response::json( [ 'remove'=>$id  ] ) ;
	}

}
