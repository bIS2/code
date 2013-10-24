<?php

class HolsController extends BaseController {


   /**
     * Post Model
     * @var hos
     */
    protected $hos;

    /**
     * Inject the models.
     * @param Post $post
     * @param User $user
     */
    public function __construct(Hoss $hos)
    {
        parent::__construct();
        $this->hos = $hos;
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		$hos = $this->hos->orderBy('id', 'ASC')->get();
		$hols = Hols::orderBy('id', 'ASC')->get()->take(100);
		$status = '';
		return View::make('hols/index', array('hos' => $hos, 'posts' => $hols, 'status' => $status));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('hols/index', array('posts' => $hols));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		return View::make('hols/index', array('posts' => $hols));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		return View::make('hols/index', array('posts' => $hols));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		extract($_POST[]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}