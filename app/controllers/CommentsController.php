<?php

/*
*
*	Controls workflow comments in Holdings. 
* 	A Holding can be commented by Speichuser receiving lists in case you have a problem
*
*/


class CommentsController extends BaseController {

	/**
	 * Comment Repository
	 *
	 * @var Comment
	 */
	protected $comment;

	public function __construct(Comment $comment)
	{
		$this->comment = $comment;
	}

	/**
	 * Display a listing of the comments.
	 *
	 * @return Response
	 */
	public function index()
	{
		$comments = $this->comment->all();

		return View::make('comments.index', compact('comments'));
	}

	/**
	 * Show the form for creating a new comment.
	 *
	 * @return Response
	 */
	public function create() {

		$comment = Comment::firstOrNew([ 'holding_id'=> Input::get('holding_id'), 'user_id' => Auth::user()->id ]);

		$data['comment'] = $comment;
		return View::make('comments.create', $data);
	}

	/**
	 * Store a newly created comment in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$input = Input::all();
		$validation = Validator::make($input, Comment::$rules);

		if ($validation->passes())
		{
			$this->comment->create($input);

			return Response::json( ['commented' => $input['holding_id']] );
		}

		return Redirect::route('comments.create')
			->withInput()
			->withErrors($validation)
			->with('message', 'There were validation errors.');
	}

	/**
	 * Display the specified comment.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$comment = $this->comment->findOrFail($id);

		return View::make('comments.show', compact('comment'));
	}

	/**
	 * Show the form for editing the specified comment.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$comment = $this->comment->find($id);

		if (is_null($comment))
		{
			return Redirect::route('comments.index');
		}

		return View::make('comments.edit', compact('comment'));
	}

	/**
	 * Update the specified comment in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$input = array_except(Input::all(), '_method');
		$validation = Validator::make($input, Comment::$rules);

		if ($validation->passes())
		{
			$comment = $this->comment->find($id);
			$comment->update($input);

			return Response::json( ['commented' => $input['holding_id']] );
		}

		return Redirect::route('comments.edit', $id)
			->withInput()
			->withErrors($validation)
			->with('message', 'There were validation errors.');
	}

	/**
	 * Remove the specified comment from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$this->comment->find($id)->delete();

		return Redirect::route('comments.index');
	}

}
