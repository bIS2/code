<?php

class HolbisController extends BaseController {


   /**
     * Post Model
     * @var holgroups
     */
    protected $holgroups;

    /**
     * Inject the models.
     * @param Post $post
     * @param User $user
     */
    public function __construct(Holgroup $holgroups)
    {
        parent::__construct();
        $this->holgroups = $holgroups;
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		$hg = $this->holgroups->orderBy('id', 'ASC')->get();
		$holbis = Holbis::orderBy('id', 'ASC')->get()->take(100);
		return View::make('holbis/index', array('hg' => $hg, 'posts' => $holbis));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('holbis/index', array('posts' => $holbis));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		return View::make('holbis/index', array('posts' => $holbis));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		return View::make('holbis/index', array('posts' => $holbis));
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
		//
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