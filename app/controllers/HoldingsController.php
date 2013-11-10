<?php

class HoldingsController extends BaseController {

   /**
     * Post Model
     * @var hos
     */
    public $data;

    public function __construct() {

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
    $state =  (Input::has('state')) ? Input::get('state') : 'pendings'; 

		if ( Input::has('hlist_id') ) {
			$holdings = Hlist::find(Input::get('hlist_id') )->holdings();
		} else {
			$holdingssets_ids = Holdingsset::whereOk(true)->lists('id');
			$holdings = Holding::whereIn('holdingsset_id',$holdingssets_ids);
		}

		if ( $state=='ok2' ) $holdings = $holdings->ok2();
		if ( $state=='tagged' )	$holdings = $holdings->pendings();
		if ( $state=='pendings' )	$holdings = $holdings->whereOk2(0);
		if ( $state=='orphan' )	$holdings = $holdings;

		// $this->data['tags'] 		= Tag::all(	);
		$this->data['hlist'] 		= $hlist;
		$this->data['holdings'] = $holdings->paginate(10);

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

	public function putOK($id){
		$holding = Holding::find($id);
		return ($holding->update(['ok2'=>true])) ? Response::json( [ 'remove' => [$id]] ) : Response::json( ['error' => [$id]] );
	}

	public function postTagged($id){

		$tags = Input::get('tags');
		$holding = Holding::find($id);

		foreach ($tags as $tag) {
			if (isset( $tag['tag_id']) )
				$holding->tags()->attach( $tag['tag_id'],[ 'content'=>$tag['content'] ] );
		}
	}

	public function getConfirmed(){

	}

}