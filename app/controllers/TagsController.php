<?php

class TagsController extends BaseController {

	/**
	 * Tag Repository
	 *
	 * @var Tag
	 */
	protected $tag;

	public function __construct(Tag $tag)
	{
		$this->tag = $tag;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$tags = $this->tag->all();

		return View::make('tags.index', compact('tags'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$data['holding'] = Holding::find( Input::get('holding_id') );
		return View::make('tags.create',$data);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{

		$tags = Input::get('tags');
		$holding = Holding::find(Input::get('holding_id'));

		foreach ($tags as $tag) {
			if (isset( $tag['tag_id']) )
				$holding->tags()->attach( $tag['tag_id'],[ 'content'=>$tag['content'] ] );
		}
		return Response::json( ['tag' => $holding->id] );

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$tag = $this->tag->findOrFail($id);

		return View::make('tags.show', compact('tag'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$tag = $this->tag->find($id);

		if (is_null($tag))
		{
			return Redirect::route('tags.index');
		}

		return View::make('tags.edit', compact('tag'));
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
		$validation = Validator::make($input, Tag::$rules);

		if ($validation->passes())
		{
			$tag = $this->tag->find($id);
			$tag->update($input);

			return Redirect::route('tags.show', $id);
		}

		return Redirect::route('tags.edit', $id)
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
		$this->tag->find($id)->delete();

		return Redirect::route('tags.index');
	}

}
