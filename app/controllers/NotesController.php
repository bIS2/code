<?php

class NotesController extends BaseController {

	/**
	 * note Repository
	 *
	 * @var note
	 */
	protected $note;

	public function __construct(note $note)
	{
		$this->note = $note;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$notes = $this->note->all();

		return View::make('notes.index', compact('notes'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$data['holding'] = Holding::find( Input::get('holding_id') );
		// echo var_dump($data['holding']->notes->find(1) );
		return View::make('notes.create',$data);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{

		$notes = Input::get('notes');
		$holding = Holding::find(Input::get('holding_id'));
		
		// delete all notes to insert de new
		//$holding->notes()->detach();

		foreach ($notes as $note) {
			if (isset( $note['tag_id']) )
				$holding->notes()->attach( $note['note_id'],[ 'content'=>$note['content'] ] );
		}
		return Response::json( ['note' => $holding->id] );

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$note = $this->note->findOrFail($id);

		return View::make('notes.show', compact('note'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$note = $this->note->find($id);

		if (is_null($note))
		{
			return Redirect::route('notes.index');
		}

		return View::make('notes.edit', compact('note'));
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
		$validation = Validator::make($input, note::$rules);

		if ($validation->passes())
		{
			$note = $this->note->find($id);
			$note->update($input);

			return Redirect::route('notes.show', $id);
		}

		return Redirect::route('notes.edit', $id)
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
		$this->note->find($id)->delete();

		return Redirect::route('notes.index');
	}

}
