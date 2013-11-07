<?php

class HoldingsController extends BaseController {

   /**
     * Post Model
     * @var hos
     */
    public $data;

    public function __construct() {
    	$this->beforeFilter( 'auth' );

    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function Index()
	{


    $this->data['hlists'] = Auth::user()->hlists;
    $hlist = false;
    
		if (Input::has('hlist_id')) {

			$hlist = Hlist::find(Input::get('hlist_id'));
			$holdings = $hlist->holdings()->paginate(100);

		} else {

			$hs = DB::table('holdingssets')->where('ok',true)->lists('id');
			//$holdings = Holding::paginate(100);
			$holdings = Holding::whereRaw('holdingsset_id in ('.implode(',',$hs).')')->paginate(20);
		}
		$this->data['tags'] = Tag::all();
		$this->data['hlist'] = $hlist;
		$this->data['holdings'] = $holdings;



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