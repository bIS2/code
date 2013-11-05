<?php

class HoldingsController extends BaseController {

   /**
     * Post Model
     * @var hos
     */
    public $data;

    public function __construct() {
    	$this->beforeFilter( 'auth' );
    	$this->data['lists'] = Auth::user()->hlists;
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function Index()
	{



		if (Input::has('list_id'))
			$holdings = Hlist::find(Input::get('list_id'))->holdings()->paginate(100);
		else
		{
			$hs = DB::table('holdingssets')->where('ok',true)->lists('id');
			//$holdings = Holding::paginate(100);
			$holdings = Holding::whereRaw('holdingsset_id in ('.implode(',',$hs).')')->paginate(20);
			$this->data['holdings'] = $holdings;
		}



		// CONDITIONS
		// filter by holdingsset ok
		//  and holdings in their library
		return View::make('holdings/index', $this->data);

	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('holdings/index', array('posts' => $holdings));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		return View::make('holdings/index', array('posts' => $holdings));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$this->data['holding'] = Holding::find($id);
		return View::make('holdings/show', $this->data);
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

	public function postMove($id)
	{
		//
	}

}